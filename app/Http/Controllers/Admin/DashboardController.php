<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ServicePlan;
use App\Models\Event;
use App\Models\Portfolio;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\ProjectRequest;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_service_plans' => ServicePlan::count(),
            'total_events' => Event::count(),
            'total_portfolios' => Portfolio::count(),
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_subscriptions' => Subscription::count(),
            'pending_project_requests' => ProjectRequest::where('status', 'pending')->count(),
            'revenue_this_month' => Order::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
        ];

        $recent_orders = Order::with('user', 'product')
            ->latest()
            ->take(5)
            ->get();

        $recent_subscriptions = Subscription::with('user', 'servicePlan')
            ->latest()
            ->take(5)
            ->get();

        $pending_requests = ProjectRequest::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'recent_subscriptions', 'pending_requests'));
    }
}
