<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Ticket Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="font-semibold text-2xl text-gray-800">{{ $supportTicket->subject }}</h2>
                            <p class="text-gray-600 mt-1">Ticket #{{ $supportTicket->ticket_number }}</p>
                        </div>
                        <div class="text-right">
                            @php
                                $statusColors = [
                                    'open' => 'bg-green-100 text-green-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'waiting' => 'bg-yellow-100 text-yellow-800',
                                    'resolved' => 'bg-purple-100 text-purple-800',
                                    'closed' => 'bg-gray-100 text-gray-800',
                                ];
                                $priorityColors = [
                                    'low' => 'bg-gray-100 text-gray-800',
                                    'normal' => 'bg-blue-100 text-blue-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'urgent' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusColors[$supportTicket->status] }}">
                                {{ ucfirst(str_replace('_', ' ', $supportTicket->status)) }}
                            </span>
                            <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $priorityColors[$supportTicket->priority] }}">
                                {{ ucfirst($supportTicket->priority) }} Priority
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm text-gray-600">
                        <div><strong>Category:</strong> {{ ucfirst($supportTicket->category) }}</div>
                        <div><strong>Created:</strong> {{ $supportTicket->created_at->format('M d, Y H:i') }}</div>
                        @if($supportTicket->assigned_to)
                            <div><strong>Assigned to:</strong> {{ $supportTicket->assignedTo->name }}</div>
                        @endif
                        @if($supportTicket->resolved_at)
                            <div><strong>Resolved:</strong> {{ $supportTicket->resolved_at->format('M d, Y H:i') }}</div>
                        @endif
                    </div>

                    @if(!$supportTicket->isClosed())
                        <div class="mt-4">
                            <form action="{{ route('support.close', $supportTicket) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to close this ticket?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Close Ticket
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Original Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                                {{ substr($supportTicket->user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-semibold">{{ $supportTicket->user->name }}</h4>
                                <span class="text-sm text-gray-500">{{ $supportTicket->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="mt-2 text-gray-700 whitespace-pre-line">{{ $supportTicket->message }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Replies -->
            @if($supportTicket->replies->count() > 0)
                <div class="space-y-4 mb-6">
                    @foreach($supportTicket->replies as $reply)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg {{ $reply->is_staff_reply ? 'border-l-4 border-blue-500' : '' }}">
                            <div class="p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full {{ $reply->is_staff_reply ? 'bg-blue-600' : 'bg-gray-600' }} flex items-center justify-center text-white font-bold">
                                            {{ substr($reply->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-lg font-semibold">
                                                {{ $reply->user->name }}
                                                @if($reply->is_staff_reply)
                                                    <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">Staff</span>
                                                @endif
                                            </h4>
                                            <span class="text-sm text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="mt-2 text-gray-700 whitespace-pre-line">{{ $reply->message }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Reply Form -->
            @if(!$supportTicket->isClosed())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Add Reply</h3>
                        <form action="{{ route('support.reply', $supportTicket) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <textarea name="message" rows="5" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Type your reply..."></textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('support.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Back to Tickets
                                </a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-gray-600">This ticket is closed. You cannot add more replies.</p>
                        <a href="{{ route('support.index') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Back to Tickets
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
