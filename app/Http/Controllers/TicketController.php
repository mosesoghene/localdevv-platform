<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use App\Models\TicketPurchase;
use App\Models\Order;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Show event tickets for purchase
     */
    public function showEventTickets(Event $event)
    {
        $ticketTypes = $event->activeTicketTypes()
            ->where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return view('tickets.event-tickets', compact('event', 'ticketTypes'));
    }

    /**
     * Show checkout for ticket purchase
     */
    public function checkout(TicketType $ticketType, Request $request)
    {
        $quantity = $request->input('quantity', 1);

        // Validate availability
        if (!$ticketType->isAvailableForSale()) {
            return redirect()->back()->with('error', 'This ticket type is not available for sale.');
        }

        if (!$ticketType->hasAvailableQuantity($quantity)) {
            return redirect()->back()->with('error', 'Not enough tickets available.');
        }

        if ($quantity > $ticketType->max_per_order) {
            return redirect()->back()->with('error', "Maximum {$ticketType->max_per_order} tickets per order.");
        }

        $gateway = PaymentService::getGateway();
        
        if (!$gateway) {
            return redirect()->back()->with('error', 'Payment gateway not configured.');
        }

        return view('tickets.checkout', compact('ticketType', 'quantity'));
    }

    /**
     * Initialize ticket payment
     */
    public function initializePayment(Request $request, TicketType $ticketType)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'attendee_name' => 'required|string|max:255',
            'attendee_email' => 'required|email',
            'attendee_phone' => 'nullable|string|max:20',
        ]);

        $quantity = $request->quantity;

        // Re-check availability
        if (!$ticketType->isAvailableForSale() || !$ticketType->hasAvailableQuantity($quantity)) {
            return back()->with('error', 'Tickets no longer available.');
        }

        if ($quantity > $ticketType->max_per_order) {
            return back()->with('error', "Maximum {$ticketType->max_per_order} tickets per order.");
        }

        $gateway = PaymentService::getGateway();
        
        if (!$gateway) {
            return back()->with('error', 'Payment gateway not configured.');
        }

        $totalAmount = $ticketType->price * $quantity;
        $reference = 'TKT-' . strtoupper(Str::random(12)) . '-' . time();

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'ticket_type_id' => $ticketType->id,
            'quantity' => $quantity,
            'amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => PaymentService::getEnabledProvider(),
            'payment_reference' => $reference,
            'metadata' => [
                'attendee_name' => $request->attendee_name,
                'attendee_email' => $request->attendee_email,
                'attendee_phone' => $request->attendee_phone,
            ],
        ]);

        // Initialize payment
        $result = $gateway->initializePayment([
            'amount' => $totalAmount,
            'email' => $request->attendee_email,
            'name' => $request->attendee_name,
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
            'metadata' => [
                'order_id' => $order->id,
                'ticket_type_id' => $ticketType->id,
                'event_id' => $ticketType->event_id,
                'quantity' => $quantity,
            ],
        ]);

        if ($result['status'] === 'success') {
            return redirect($result['authorization_url']);
        }

        $order->delete();
        return back()->with('error', $result['message'] ?? 'Payment initialization failed.');
    }

    /**
     * Show user's tickets
     */
    public function myTickets()
    {
        $tickets = TicketPurchase::where('user_id', Auth::id())
            ->with(['event', 'ticketType'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tickets.my-tickets', compact('tickets'));
    }

    /**
     * Show ticket details
     */
    public function showTicket(TicketPurchase $ticket)
    {
        // Ensure user owns this ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to ticket.');
        }

        return view('tickets.show', compact('ticket'));
    }
}
