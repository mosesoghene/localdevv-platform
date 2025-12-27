<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LocalDevv Platform') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900">
                        LocalDevv
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900">Products</a>
                    <a href="{{ route('service-plans.index') }}" class="text-gray-700 hover:text-gray-900">Service Plans</a>
                    <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-gray-900">Events</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">Professional Web Development Solutions</h1>
                <p class="text-xl mb-8 text-blue-100">Premium scripts, themes, and expert services for your digital success</p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('products.index') }}" class="px-8 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-100">Browse Products</a>
                    <a href="{{ route('service-plans.index') }}" class="px-8 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-400">View Services</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Showcase -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Our Services</h2>
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">Premium Scripts</h3>
                    <p class="text-gray-600 text-sm">Ready-to-use PHP & JavaScript solutions</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">Custom Development</h3>
                    <p class="text-gray-600 text-sm">Tailored solutions for your needs</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">Priority Support</h3>
                    <p class="text-gray-600 text-sm">Expert assistance when you need it</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold mb-2">Installation Services</h3>
                    <p class="text-gray-600 text-sm">Professional setup and configuration</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    @if($featuredProducts->count() > 0)
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-3xl font-bold">Featured Products</h2>
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-700">View All →</a>
            </div>
            <div class="grid md:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                {{ ucfirst($product->product_type) }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $product->category->name }}</span>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('products.show', $product) }}" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Upcoming Events -->
    @if($upcomingEvents->count() > 0)
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold mb-12 text-center">Upcoming Events</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($upcomingEvents as $event)
                <div class="border rounded-lg p-6 hover:shadow-lg transition-shadow">
                    <div class="text-blue-600 text-sm font-semibold mb-2">{{ $event->event_date->format('M d, Y') }}</div>
                    <h3 class="font-semibold text-xl mb-3">{{ $event->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($event->description, 100) }}</p>
                    @if($event->external_url)
                    <a href="{{ $event->external_url }}" target="_blank" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Learn More →
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Featured Portfolio -->
    @if($featuredPortfolios->count() > 0)
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold mb-12 text-center">Featured Work</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($featuredPortfolios as $portfolio)
                <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    @if($portfolio->thumbnail)
                    <img src="{{ $portfolio->thumbnail }}" alt="{{ $portfolio->title }}" class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-indigo-600"></div>
                    @endif
                    <div class="p-6">
                        <h3 class="font-semibold text-lg mb-2">{{ $portfolio->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($portfolio->description, 100) }}</p>
                        @if($portfolio->project_url)
                        <a href="{{ $portfolio->project_url }}" target="_blank" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            View Project →
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Project Request CTA -->
    <div class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Have a Custom Project in Mind?</h2>
            <p class="text-xl mb-8 text-blue-100">Let's discuss how we can bring your vision to life</p>
            <button onclick="document.getElementById('projectRequestModal').classList.remove('hidden')" class="px-8 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-100">
                Request a Quote
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white font-semibold mb-4">LocalDevv Platform</h3>
                    <p class="text-sm">Professional web development solutions and services.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Products</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products.index') }}?type=script" class="hover:text-white">Scripts</a></li>
                        <li><a href="{{ route('products.index') }}?type=theme" class="hover:text-white">Themes</a></li>
                        <li><a href="{{ route('products.index') }}?type=plugin" class="hover:text-white">Plugins</a></li>
                        <li><a href="{{ route('products.index') }}?type=template" class="hover:text-white">Templates</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Services</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('service-plans.index') }}" class="hover:text-white">Priority Support</a></li>
                        <li><a href="{{ route('service-plans.index') }}" class="hover:text-white">Installation</a></li>
                        <li><a href="{{ route('service-plans.index') }}" class="hover:text-white">Maintenance</a></li>
                        <li><a href="{{ route('service-plans.index') }}" class="hover:text-white">VIP Support</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('events.index') }}" class="hover:text-white">Events</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white">Login</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white">Sign Up</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-sm text-center">
                <p>&copy; {{ date('Y') }} LocalDevv Platform. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Project Request Modal -->
    <div id="projectRequestModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Request a Project Quote</h3>
                <button onclick="document.getElementById('projectRequestModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('project-requests.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Name *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email *</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input type="text" name="phone" class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Project Type *</label>
                    <select name="project_type" required class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select type...</option>
                        <option value="website">Website</option>
                        <option value="web_app">Web Application</option>
                        <option value="mobile_app">Mobile App</option>
                        <option value="ecommerce">E-commerce</option>
                        <option value="custom">Custom Solution</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Budget Range</label>
                    <select name="budget_range" class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select range...</option>
                        <option value="under_1000">Under $1,000</option>
                        <option value="1000_5000">$1,000 - $5,000</option>
                        <option value="5000_10000">$5,000 - $10,000</option>
                        <option value="over_10000">Over $10,000</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Description *</label>
                    <textarea name="description" required rows="4" class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-semibold">
                    Submit Request
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
    @endif
</body>
</html>
