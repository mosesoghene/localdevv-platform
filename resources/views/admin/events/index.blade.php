<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Events</h2>
            <a href="{{ route('admin.events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">+ Add Event</a>
        </div>
    </x-slot>

    
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($events as $event)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $event->slug }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ ucfirst($event->event_type) }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->event_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($event->is_published)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.events.edit', $event) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No events found. <a href="{{ route('admin.events.create') }}" class="text-blue-600 hover:underline">Create one</a></td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $events->links() }}</div>
            </div>
        </x-admin-layout>
