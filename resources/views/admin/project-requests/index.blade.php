<x-admin-layout>
    <x-slot name="header">Manage Project Requests</x-slot>

    
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($projectRequests as $request)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($request->description, 40) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucwords(str_replace('_', ' ', $request->project_type)) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.project-requests.update-status', $request) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs rounded-full border-0 
                                            {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $request->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $request->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $request->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                            <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="in_progress" {{ $request->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $request->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $request->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.project-requests.destroy', $request) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No project requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $projectRequests->links() }}</div>
            </div>
        </x-admin-layout>
