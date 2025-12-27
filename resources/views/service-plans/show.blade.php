<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $servicePlan->name }}
            </h2>
            <a href="{{ route('service-plans.index') }}" class="text-blue-600 hover:text-blue-700">
                ← Back to Plans
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <!-- Plan Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $servicePlan->name }}</h1>
                        <div class="mb-4">
                            <span class="text-5xl font-bold text-gray-900">${{ number_format($servicePlan->price, 2) }}</span>
                            <span class="text-gray-600 text-xl">/{{ $servicePlan->billing_interval }}</span>
                        </div>
                        <p class="text-xl text-gray-600">{{ $servicePlan->description }}</p>
                    </div>

                    <!-- Features -->
                    @if($servicePlan->features)
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">What's Included</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($servicePlan->features as $feature)
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 text-green-500 mr-3 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Limits -->
                    @if($servicePlan->limits)
                    <div class="mb-8 bg-gray-50 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Monthly Quotas</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($servicePlan->limits as $key => $value)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">{{ ucwords(str_replace('_', ' ', str_replace('_per_month', '', $key))) }}</span>
                                    <span class="font-bold text-blue-600 text-lg">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Subscribe Button -->
                    <div class="text-center">
                        @auth
                            <button class="px-8 py-4 bg-blue-600 text-white text-lg font-semibold rounded-lg hover:bg-blue-700">
                                Subscribe Now
                            </button>
                            <p class="mt-4 text-gray-600">Cancel anytime. No long-term contracts.</p>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                <p class="text-yellow-800 mb-4">Please <a href="{{ route('login') }}" class="font-semibold underline">login</a> or <a href="{{ route('register') }}" class="font-semibold underline">create an account</a> to subscribe to this plan.</p>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
