<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Upcoming Events & Announcements</h1>
                <p class="text-xl text-gray-600">Stay updated with our latest events, webinars, and announcements</p>
            </div>

            @if($events->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($events as $event)
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                            @if($event->thumbnail)
                                <img src="{{ $event->thumbnail }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="p-6">
                                <!-- Event Type & Date -->
                                <div class="flex items-center justify-between mb-3">
                                    @if($event->event_type)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                            {{ ucfirst($event->event_type) }}
                                        </span>
                                    @endif
                                    <span class="text-sm text-gray-600">
                                        {{ $event->event_date->format('M d, Y') }}
                                    </span>
                                </div>

                                <!-- Event Title -->
                                <h3 class="font-semibold text-xl mb-3 text-gray-900">{{ $event->title }}</h3>

                                <!-- Description -->
                                @if($event->description)
                                    <p class="text-gray-600 mb-4">{{ Str::limit($event->description, 120) }}</p>
                                @endif

                                <!-- External Link -->
                                @if($event->external_url)
                                    <a href="{{ $event->external_url }}" target="_blank" class="text-blue-600 hover:text-blue-700 font-medium inline-flex items-center">
                                        Learn More
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    {{ $events->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No events at this time</h3>
                    <p class="mt-2 text-gray-500">Check back soon for upcoming events and announcements.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
