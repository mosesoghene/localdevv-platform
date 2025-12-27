<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Service Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Perfect Plan</h1>
                <p class="text-xl text-gray-600">Professional support and services to keep your projects running smoothly</p>
            </div>

            <!-- Service Plans Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($servicePlans->flatten() as $plan)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow {{ $plan->plan_type === 'vip_support' ? 'ring-2 ring-blue-500' : '' }}">
                        @if($plan->plan_type === 'vip_support')
                            <div class="bg-blue-600 text-white text-center py-2 text-sm font-semibold">
                                MOST POPULAR
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <!-- Plan Name -->
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                            
                            <!-- Price -->
                            <div class="mb-4">
                                <span class="text-4xl font-bold text-gray-900">${{ number_format($plan->price, 0) }}</span>
                                <span class="text-gray-600">/{{ $plan->billing_interval }}</span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 mb-6">{{ $plan->description }}</p>

                            <!-- Features -->
                            @if($plan->features)
                                <ul class="space-y-3 mb-6">
                                    @foreach($plan->features as $feature)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-gray-700 text-sm">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <!-- Limits/Quotas -->
                            @if($plan->limits)
                                <div class="border-t pt-4 mb-6">
                                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Monthly Quotas</p>
                                    @foreach($plan->limits as $key => $value)
                                        <p class="text-sm text-gray-600">
                                            {{ ucwords(str_replace('_', ' ', str_replace('_per_month', '', $key))) }}: 
                                            <span class="font-semibold">{{ $value }}</span>
                                        </p>
                                    @endforeach
                                </div>
                            @endif

                            <!-- CTA Button -->
                            <a href="{{ route('service-plans.show', $plan) }}" class="block w-full text-center px-6 py-3 {{ $plan->plan_type === 'vip_support' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-900 hover:bg-gray-800' }} text-white font-semibold rounded-lg transition-colors">
                                Select Plan
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- FAQ or Additional Info -->
            <div class="mt-16 bg-gray-50 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-center mb-8">Frequently Asked Questions</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold mb-2">Can I cancel anytime?</h3>
                        <p class="text-gray-600 text-sm">Yes, you can cancel your subscription at any time from your account dashboard.</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">What payment methods do you accept?</h3>
                        <p class="text-gray-600 text-sm">We accept all major credit cards and payment methods through our secure payment gateway.</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Do unused quotas roll over?</h3>
                        <p class="text-gray-600 text-sm">No, quotas reset at the beginning of each billing period.</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Can I upgrade my plan?</h3>
                        <p class="text-gray-600 text-sm">Yes, you can upgrade or downgrade your plan anytime from your account dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
