<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($tickets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($tickets as $ticket)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <!-- Event Info -->
                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $ticket->event->title }}</h3>
                                <p class="text-sm text-gray-600 mb-4">{{ $ticket->ticketType->name }}</p>

                                <!-- Ticket Code -->
                                <div class="bg-gray-100 rounded px-3 py-2 mb-4">
                                    <p class="text-xs text-gray-500">Ticket Code</p>
                                    <p class="font-mono font-bold text-lg">{{ $ticket->ticket_code }}</p>
                                </div>

                                <!-- Status Badge -->
                                <div class="mb-4">
                                    @if($ticket->status === 'confirmed')
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">Confirmed</span>
                                    @elseif($ticket->status === 'used')
                                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">Used</span>
                                    @elseif($ticket->status === 'pending')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full">Pending</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">{{ ucfirst($ticket->status) }}</span>
                                    @endif
                                </div>

                                <!-- Details -->
                                <div class="text-sm text-gray-700 space-y-1 mb-4">
                                    <p><strong>Attendee:</strong> {{ $ticket->attendee_name }}</p>
                                    <p><strong>Email:</strong> {{ $ticket->attendee_email }}</p>
                                    <p><strong>Price:</strong> ${{ number_format($ticket->unit_price, 2) }}</p>
                                    <p><strong>Event Date:</strong> {{ $ticket->event->event_date->format('M d, Y') }}</p>
                                </div>

                                <!-- View Button -->
                                <a href="{{ route('tickets.show', $ticket) }}" class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition">
                                    View Ticket
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Tickets Yet</h3>
                        <p class="mt-2 text-sm text-gray-500">You haven't purchased any event tickets yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('events.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition">
                                Browse Events
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
