<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Subscriptions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($subscriptions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($subscriptions as $subscription)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $subscription->servicePlan->name }}</h3>
                                    @if($subscription->status === 'active')
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">Active</span>
                                    @elseif($subscription->status === 'cancelled')
                                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">Cancelled</span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">{{ ucfirst($subscription->status) }}</span>
                                    @endif
                                </div>

                                <div class="space-y-2 mb-4 text-sm text-gray-700">
                                    <p><strong>Started:</strong> {{ $subscription->starts_at->format('M d, Y') }}</p>
                                    <p><strong>{{ $subscription->status === 'active' ? 'Renews' : 'Expires' }}:</strong> {{ $subscription->expires_at->format('M d, Y') }}</p>
                                    <p><strong>Price:</strong> ${{ number_format($subscription->servicePlan->price, 2) }}/month</p>
                                    @if($subscription->cancelled_at)
                                        <p><strong>Cancelled:</strong> {{ $subscription->cancelled_at->format('M d, Y') }}</p>
                                    @endif
                                </div>

                                @if($subscription->status === 'active')
                                    <form action="{{ route('subscriptions.cancel', $subscription) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this subscription?');">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md transition">
                                            Cancel Subscription
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Subscriptions</h3>
                        <p class="mt-2 text-sm text-gray-500">You don't have any subscriptions yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('service-plans.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition">
                                Browse Service Plans
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
