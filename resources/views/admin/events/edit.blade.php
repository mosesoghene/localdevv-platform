<x-admin-layout>
    <x-slot name="header">Edit Event: {{ $event->title }}</x-slot>
    
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title *</label>
                            <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slug *</label>
                            <input type="text" name="slug" value="{{ old('slug', $event->slug) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event Type *</label>
                            <select name="event_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="webinar" {{ $event->event_type == 'webinar' ? 'selected' : '' }}>Webinar</option>
                                <option value="workshop" {{ $event->event_type == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="meetup" {{ $event->event_type == 'meetup' ? 'selected' : '' }}>Meetup</option>
                                <option value="conference" {{ $event->event_type == 'conference' ? 'selected' : '' }}>Conference</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event Date *</label>
                            <input type="date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event Time</label>
                            <input type="time" name="event_time" value="{{ old('event_time', $event->event_time) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" name="location" value="{{ old('location', $event->location) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Max Attendees</label>
                            <input type="number" name="max_attendees" value="{{ old('max_attendees', $event->max_attendees) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Registration URL</label>
                            <input type="url" name="registration_url" value="{{ old('registration_url', $event->registration_url) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_published" value="1" {{ $event->is_published ? 'checked' : '' }} class="rounded">
                            <label class="ml-2 text-sm text-gray-700">Published</label>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">Description *</label>
                        <textarea name="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $event->description) }}</textarea>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">Featured Image <span class="text-gray-500">Max: 2MB - Leave empty to keep current</span></label>
                        @if($event->featured_image)
                            <div class="mt-2 mb-3">
                                <img src="{{ asset('storage/' . $event->featured_image) }}" alt="{{ $event->title }}" class="max-w-xs rounded-md border border-gray-300">
                                <p class="text-sm text-gray-500 mt-1">Current image</p>
                            </div>
                        @endif
                        <input type="file" name="featured_image" id="featured_image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <div id="image-preview" class="mt-2 hidden">
                            <img src="" alt="Preview" class="max-w-xs rounded-md border border-gray-300">
                            <p class="text-sm text-gray-500 mt-1">New image preview</p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.events.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancel</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Update</button>
                    </div>
                </form>
            </div>
        </x-admin-layout>
