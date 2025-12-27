<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TicketPurchase;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show user dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get active subscriptions
        $activeSubscriptions = $user->subscriptions()
            ->where('status', 'active')
            ->with('servicePlan')
            ->get();

        // Get purchased products count
        $purchasedProductsCount = $user->orders()
            ->where('status', 'completed')
            ->whereNotNull('product_id')
            ->count();

        // Get upcoming events (tickets)
        $upcomingTickets = $user->ticketPurchases()
            ->where('status', 'confirmed')
            ->whereHas('event', function($query) {
                $query->where('event_date', '>=', now());
            })
            ->with(['event', 'ticketType'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Recent orders
        $recentOrders = $user->orders()
            ->with(['product', 'servicePlan', 'ticketType'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Stats
        $stats = [
            'total_spent' => $user->orders()->where('status', 'completed')->sum('amount'),
            'active_subscriptions' => $activeSubscriptions->count(),
            'total_products' => $purchasedProductsCount,
            'total_tickets' => $user->ticketPurchases()->count(),
        ];

        return view('user.dashboard', compact(
            'activeSubscriptions',
            'upcomingTickets',
            'recentOrders',
            'stats'
        ));
    }

    /**
     * Show subscriptions
     */
    public function subscriptions()
    {
        $subscriptions = Auth::user()->subscriptions()
            ->with('servicePlan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.subscriptions', compact('subscriptions'));
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Subscription $subscription)
    {
        // Verify ownership
        if ($subscription->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($subscription->status !== 'active') {
            return redirect()->back()->with('error', 'Subscription is not active.');
        }

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Subscription cancelled successfully.');
    }

    /**
     * Show order history
     */
    public function orders()
    {
        $orders = Auth::user()->orders()
            ->with(['product', 'servicePlan', 'ticketType'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.orders', compact('orders'));
    }
}
