<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Products -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 uppercase">Total Products</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_products'] }}</p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Service Plans -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 uppercase">Service Plans</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_service_plans'] }}</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 uppercase">Total Users</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue This Month -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 uppercase">Revenue (Month)</p>
                                <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['revenue_this_month'], 2) }}</p>
                            </div>
                            <div class="bg-yellow-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Subscriptions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 uppercase">Active Subscriptions</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['active_subscriptions'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Events -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 uppercase">Total Events</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_events'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Portfolios -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 uppercase">Portfolios</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_portfolios'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 uppercase">Pending Requests</p>
                                <p class="text-3xl font-bold text-red-600">{{ $stats['pending_project_requests'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-4 text-center transition">
                    <span class="font-semibold">+ Add Product</span>
                </a>
                <a href="{{ route('admin.service-plans.create') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-4 text-center transition">
                    <span class="font-semibold">+ Add Service Plan</span>
                </a>
                <a href="{{ route('admin.events.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-4 text-center transition">
                    <span class="font-semibold">+ Add Event</span>
                </a>
                <a href="{{ route('admin.portfolios.create') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg p-4 text-center transition">
                    <span class="font-semibold">+ Add Portfolio</span>
                </a>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>
                        @if($recent_orders->count() > 0)
                            <div class="space-y-3">
                                @foreach($recent_orders as $order)
                                    <div class="flex justify-between items-center border-b pb-2">
                                        <div>
                                            <p class="font-medium">{{ $order->product->name ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->user->name ?? 'Guest' }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold">${{ number_format($order->total_amount, 2) }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No recent orders</p>
                        @endif
                    </div>
                </div>

                <!-- Pending Project Requests -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Pending Project Requests</h3>
                        @if($pending_requests->count() > 0)
                            <div class="space-y-3">
                                @foreach($pending_requests as $request)
                                    <div class="border-b pb-2">
                                        <p class="font-medium">{{ $request->name }}</p>
                                        <p class="text-sm text-gray-600">{{ Str::limit($request->description, 60) }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('admin.project-requests.index') }}" class="text-blue-600 hover:underline text-sm mt-3 inline-block">View all requests →</a>
                        @else
                            <p class="text-gray-500">No pending requests</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
