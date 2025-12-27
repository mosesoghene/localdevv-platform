<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Complete Your Purchase</h3>

                    <!-- Product Info -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $product->category->name }}</p>
                            </div>
                            <span class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                        </div>
                        <p class="text-sm text-gray-700">{{ Str::limit($product->description, 120) }}</p>
                    </div>

                    <!-- Payment Form -->
                    <form action="{{ route('payment.product', $product) }}" method="POST">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t">
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-lg font-semibold">Total</span>
                                <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            </div>

                            <button type="submit" class="w-full px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-lg">
                                Proceed to Payment
                            </button>

                            <p class="text-sm text-gray-500 text-center mt-4">
                                You will be redirected to a secure payment page
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
