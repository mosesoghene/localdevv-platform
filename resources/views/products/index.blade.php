<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products Catalog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('products.index') }}" class="grid md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Types</option>
                                <option value="script" {{ request('type') == 'script' ? 'selected' : '' }}>Script</option>
                                <option value="theme" {{ request('type') == 'theme' ? 'selected' : '' }}>Theme</option>
                                <option value="plugin" {{ request('type') == 'plugin' ? 'selected' : '' }}>Plugin</option>
                                <option value="template" {{ request('type') == 'template' ? 'selected' : '' }}>Template</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Count -->
            <div class="mb-6">
                <p class="text-gray-600">Showing {{ $products->count() }} of {{ $products->total() }} products</p>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                            <div class="p-6">
                                <!-- Type Badge -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        {{ ucfirst($product->product_type) }}
                                    </span>
                                    @if($product->version)
                                        <span class="text-xs text-gray-500">v{{ $product->version }}</span>
                                    @endif
                                </div>

                                <!-- Product Name -->
                                <h3 class="font-semibold text-lg mb-2 text-gray-900">{{ $product->name }}</h3>

                                <!-- Category -->
                                <p class="text-sm text-gray-600 mb-2">{{ $product->category->name }}</p>

                                <!-- Description -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($product->description, 100) }}</p>

                                <!-- Price & Action -->
                                <div class="flex items-center justify-between mt-4 pt-4 border-t">
                                    <span class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                    <a href="{{ route('products.show', $product) }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No products found</h3>
                    <p class="mt-2 text-gray-500">Try adjusting your filters or search query.</p>
                    <a href="{{ route('products.index') }}" class="mt-6 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
