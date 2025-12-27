<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Portfolio</h2></x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.portfolios.update', $portfolio) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title *</label>
                            <input type="text" name="title" value="{{ old('title', $portfolio->title) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slug *</label>
                            <input type="text" name="slug" value="{{ old('slug', $portfolio->slug) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Project Type *</label>
                            <select name="project_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="web_development" {{ $portfolio->project_type == 'web_development' ? 'selected' : '' }}>Web Development</option>
                                <option value="mobile_app" {{ $portfolio->project_type == 'mobile_app' ? 'selected' : '' }}>Mobile App</option>
                                <option value="custom_software" {{ $portfolio->project_type == 'custom_software' ? 'selected' : '' }}>Custom Software</option>
                                <option value="consulting" {{ $portfolio->project_type == 'consulting' ? 'selected' : '' }}>Consulting</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Client Name</label>
                            <input type="text" name="client_name" value="{{ old('client_name', $portfolio->client_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Project URL</label>
                            <input type="url" name="project_url" value="{{ old('project_url', $portfolio->project_url) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Completion Date</label>
                            <input type="date" name="completion_date" value="{{ old('completion_date', $portfolio->completion_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ $portfolio->is_featured ? 'checked' : '' }} class="rounded">
                            <label class="ml-2 text-sm text-gray-700">Featured</label>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">Description *</label>
                        <textarea name="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $portfolio->description) }}</textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.portfolios.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancel</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
