<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-semibold text-2xl text-gray-800">Support Tickets</h2>
                    </div>

                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.support-tickets.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full rounded-md border-gray-300">
                                    <option value="all">All Status</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                <select name="priority" class="w-full rounded-md border-gray-300">
                                    <option value="all">All Priorities</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category" class="w-full rounded-md border-gray-300">
                                    <option value="all">All Categories</option>
                                    <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
                                    <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="billing" {{ request('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                                    <option value="product" {{ request('category') == 'product' ? 'selected' : '' }}>Product</option>
                                    <option value="service" {{ request('category') == 'service' ? 'selected' : '' }}>Service</option>
                                    <option value="event" {{ request('category') == 'event' ? 'selected' : '' }}>Event</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <div class="flex space-x-2">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ticket # or Subject" class="flex-1 rounded-md border-gray-300">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Tickets Table -->
                    @if($tickets->isEmpty())
                        <div class="text-center py-12">
                            <p class="text-gray-500">No support tickets found.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Replies</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tickets as $ticket)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                                {{ $ticket->ticket_number }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                                {{ $ticket->subject }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $ticket->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                                {{ $ticket->category }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $priorityColors = [
                                                        'low' => 'bg-gray-100 text-gray-800',
                                                        'normal' => 'bg-blue-100 text-blue-800',
                                                        'high' => 'bg-orange-100 text-orange-800',
                                                        'urgent' => 'bg-red-100 text-red-800',
                                                    ];
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $priorityColors[$ticket->priority] }}">
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusColors = [
                                                        'open' => 'bg-green-100 text-green-800',
                                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                                        'waiting' => 'bg-yellow-100 text-yellow-800',
                                                        'resolved' => 'bg-purple-100 text-purple-800',
                                                        'closed' => 'bg-gray-100 text-gray-800',
                                                    ];
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$ticket->status] }}">
                                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Unassigned' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->replies->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $ticket->created_at->diffForHumans() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.support-tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
