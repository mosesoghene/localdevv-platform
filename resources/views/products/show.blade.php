<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->name }}
            </h2>
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-700">
                ← Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Left Column - Product Image/Preview -->
                        <div>
                            <div class="bg-gradient-to-br from-blue-100 to-indigo-200 rounded-lg aspect-square flex items-center justify-center mb-4">
                                <svg class="w-32 h-32 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>

                            <!-- Product Info Badges -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                    {{ ucfirst($product->product_type) }}
                                </span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">
                                    {{ $product->category->name }}
                                </span>
                                @if($product->version)
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                    Version {{ $product->version }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Right Column - Product Details -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                            <!-- Price -->
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                <span class="text-gray-500 ml-2">One-time purchase</span>
                            </div>

                            <!-- Description -->
                            <div class="prose max-w-none mb-6">
                                <h3 class="text-lg font-semibold mb-2">Description</h3>
                                <p class="text-gray-700">{{ $product->description }}</p>
                            </div>

                            <!-- Purchase/Download Section -->
                            @auth
                                @if(auth()->user()->hasCompletedOrderForProduct($product->id))
                                    <!-- User already owns this product -->
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-green-800 font-medium">You own this product</span>
                                        </div>
                                    </div>
                                    <a href="#" class="block w-full px-6 py-3 bg-blue-600 text-white text-center font-semibold rounded-lg hover:bg-blue-700 mb-2">
                                        Download Now
                                    </a>
                                    <p class="text-sm text-gray-600 text-center">Access to lifetime updates</p>
                                @else
                                    <!-- Purchase button -->
                                    <button class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 mb-2">
                                        Purchase Now
                                    </button>
                                    <p class="text-sm text-gray-600 text-center">Secure payment via payment gateway</p>
                                @endif
                            @else
                                <!-- Not logged in -->
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                    <p class="text-yellow-800">Please <a href="{{ route('login') }}" class="font-semibold underline">login</a> or <a href="{{ route('register') }}" class="font-semibold underline">create an account</a> to purchase this product.</p>
                                </div>
                            @endauth

                            <!-- Features/What's Included -->
                            <div class="mt-8 border-t pt-6">
                                <h3 class="text-lg font-semibold mb-4">What's Included</h3>
                                <ul class="space-y-2">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-700">Complete source code</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-700">Documentation</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-700">Lifetime updates</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-gray-700">6 months support</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6">Related Products</h2>
                <div class="grid md:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        {{ ucfirst($related->product_type) }}
                                    </span>
                                </div>
                                <h3 class="font-semibold text-lg mb-2">{{ $related->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($related->description, 80) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold text-gray-900">${{ number_format($related->price, 2) }}</span>
                                    <a href="{{ route('products.show', $related) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                        View →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
