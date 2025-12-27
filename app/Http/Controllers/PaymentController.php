<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ServicePlan;
use App\Models\TicketType;
use App\Models\TicketPurchase;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show checkout page for a product
     */
    public function checkoutProduct(Product $product)
    {
        $gateway = PaymentService::getGateway();
        
        if (!$gateway) {
            return redirect()->back()->with('error', 'No payment provider is currently enabled. Please contact support.');
        }

        return view('payment.checkout-product', compact('product', 'gateway'));
    }

    /**
     * Show checkout page for a service plan
     */
    public function checkoutServicePlan(ServicePlan $servicePlan)
    {
        $gateway = PaymentService::getGateway();
        
        if (!$gateway) {
            return redirect()->back()->with('error', 'No payment provider is currently enabled. Please contact support.');
        }

        return view('payment.checkout-service', compact('servicePlan', 'gateway'));
    }

    /**
     * Initialize payment for product purchase
     */
    public function initializeProductPayment(Request $request, Product $product)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
        ]);

        $gateway = PaymentService::getGateway();
        
        if (!$gateway) {
            return back()->with('error', 'Payment gateway not configured.');
        }

        // Generate unique reference
        $reference = 'PRD-' . strtoupper(Str::random(12)) . '-' . time();

        // Create pending order
        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'amount' => $product->price,
            'status' => 'pending',
            'payment_method' => PaymentService::getEnabledProvider(),
            'payment_reference' => $reference,
        ]);

        // Initialize payment
        $result = $gateway->initializePayment([
            'amount' => $product->price,
            'email' => $request->email,
            'name' => $request->name,
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
            'metadata' => [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'user_id' => Auth::id(),
            ],
        ]);

        if ($result['status'] === 'success') {
            return redirect($result['authorization_url']);
        }

        $order->delete();
        return back()->with('error', $result['message'] ?? 'Payment initialization failed.');
    }

    /**
     * Initialize payment for service plan subscription
     */
    public function initializeServicePayment(Request $request, ServicePlan $servicePlan)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
        ]);

        $gateway = PaymentService::getGateway();
        
        if (!$gateway) {
            return back()->with('error', 'Payment gateway not configured.');
        }

        $reference = 'SRV-' . strtoupper(Str::random(12)) . '-' . time();

        $order = Order::create([
            'user_id' => Auth::id(),
            'service_plan_id' => $servicePlan->id,
            'amount' => $servicePlan->price,
            'status' => 'pending',
            'payment_method' => PaymentService::getEnabledProvider(),
            'payment_reference' => $reference,
        ]);

        $result = $gateway->initializePayment([
            'amount' => $servicePlan->price,
            'email' => $request->email,
            'name' => $request->name,
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
            'metadata' => [
                'order_id' => $order->id,
                'service_plan_id' => $servicePlan->id,
                'user_id' => Auth::id(),
            ],
        ]);

        if ($result['status'] === 'success') {
            return redirect($result['authorization_url']);
        }

        $order->delete();
        return back()->with('error', $result['message'] ?? 'Payment initialization failed.');
    }

    /**
     * Handle payment callback from gateway
     */
    public function callback(Request $request)
    {
        $reference = $request->reference ?? $request->tx_ref ?? $request->trxref;

        if (!$reference) {
            return redirect()->route('dashboard')->with('error', 'Invalid payment reference.');
        }

        $gateway = PaymentService::getGateway();
        
        if (!$gateway) {
            return redirect()->route('dashboard')->with('error', 'Payment gateway error.');
        }

        // Verify payment
        $verification = $gateway->verifyPayment($reference);

        if ($verification['status'] !== 'success') {
            return redirect()->route('payment.failed')->with('error', 'Payment verification failed.');
        }

        // Check payment status
        $paymentStatus = $verification['payment_status'];
        
        if (strtolower($paymentStatus) === 'success' || strtolower($paymentStatus) === 'successful') {
            // Find and update order
            $order = Order::where('payment_reference', $reference)->first();

            if ($order && $order->status === 'pending') {
                DB::transaction(function () use ($order, $verification) {
                    $order->update([
                        'status' => 'completed',
                        'payment_data' => $verification['data'],
                    ]);

                    // Create subscription if service plan
                    if ($order->service_plan_id) {
                        $order->user->subscriptions()->create([
                            'service_plan_id' => $order->service_plan_id,
                            'order_id' => $order->id,
                            'starts_at' => now(),
                            'expires_at' => now()->addMonths(1),
                            'status' => 'active',
                        ]);
                    }

                    // Create ticket purchases if ticket order
                    if ($order->ticket_type_id) {
                        $ticketType = $order->ticketType;
                        $metadata = $order->metadata ?? [];

                        for ($i = 0; $i < $order->quantity; $i++) {
                            TicketPurchase::create([
                                'user_id' => $order->user_id,
                                'event_id' => $ticketType->event_id,
                                'ticket_type_id' => $ticketType->id,
                                'order_id' => $order->id,
                                'quantity' => 1,
                                'unit_price' => $ticketType->price,
                                'total_amount' => $ticketType->price,
                                'ticket_code' => TicketPurchase::generateTicketCode(),
                                'status' => 'confirmed',
                                'attendee_name' => $metadata['attendee_name'] ?? null,
                                'attendee_email' => $metadata['attendee_email'] ?? null,
                                'attendee_phone' => $metadata['attendee_phone'] ?? null,
                            ]);
                        }

                        // Update sold quantity
                        $ticketType->increment('sold_quantity', $order->quantity);
                    }
                });

                return redirect()->route('payment.success')->with('success', 'Payment successful!');
            }
        }

        return redirect()->route('payment.failed')->with('error', 'Payment was not successful.');
    }

    /**
     * Handle webhook from payment gateway
     */
    public function webhook(Request $request)
    {
        $provider = $request->route('provider');
        $gateway = PaymentService::getGatewayByProvider($provider);

        if (!$gateway) {
            return response()->json(['status' => 'error'], 400);
        }

        $result = $gateway->handleWebhook($request->all());

        if ($result['status'] === 'success') {
            $reference = $result['reference'];
            $paymentStatus = $result['payment_status'];

            if (strtolower($paymentStatus) === 'success' || strtolower($paymentStatus) === 'successful') {
                $order = Order::where('payment_reference', $reference)->first();

                if ($order && $order->status === 'pending') {
                    DB::transaction(function () use ($order, $result) {
                        $order->update([
                            'status' => 'completed',
                            'payment_data' => $result['data'],
                        ]);

                        if ($order->service_plan_id) {
                            $order->user->subscriptions()->create([
                                'service_plan_id' => $order->service_plan_id,
                                'order_id' => $order->id,
                                'starts_at' => now(),
                                'expires_at' => now()->addMonths(1),
                                'status' => 'active',
                            ]);
                        }

                        // Create ticket purchases if ticket order
                        if ($order->ticket_type_id) {
                            $ticketType = $order->ticketType;
                            $metadata = $order->metadata ?? [];

                            for ($i = 0; $i < $order->quantity; $i++) {
                                TicketPurchase::create([
                                    'user_id' => $order->user_id,
                                    'event_id' => $ticketType->event_id,
                                    'ticket_type_id' => $ticketType->id,
                                    'order_id' => $order->id,
                                    'quantity' => 1,
                                    'unit_price' => $ticketType->price,
                                    'total_amount' => $ticketType->price,
                                    'ticket_code' => TicketPurchase::generateTicketCode(),
                                    'status' => 'confirmed',
                                    'attendee_name' => $metadata['attendee_name'] ?? null,
                                    'attendee_email' => $metadata['attendee_email'] ?? null,
                                    'attendee_phone' => $metadata['attendee_phone'] ?? null,
                                ]);
                            }

                            $ticketType->increment('sold_quantity', $order->quantity);
                        }
                    });
                }
            }

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 400);
    }

    /**
     * Show payment success page
     */
    public function success()
    {
        return view('payment.success');
    }

    /**
     * Show payment failed page
     */
    public function failed()
    {
        return view('payment.failed');
    }
}
