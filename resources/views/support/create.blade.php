<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="font-semibold text-2xl text-gray-800 mb-6">Create Support Ticket</h2>

                    <form action="{{ route('support.store') }}" method="POST">
                        @csrf

                        <!-- Subject -->
                        <div class="mb-4">
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject *</label>
                            <input type="text" name="subject" id="subject" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('subject') }}">
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                            <select name="category" id="category" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical Support</option>
                                <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Billing & Payments</option>
                                <option value="product" {{ old('category') == 'product' ? 'selected' : '' }}>Product Related</option>
                                <option value="service" {{ old('category') == 'service' ? 'selected' : '' }}>Service Related</option>
                                <option value="event" {{ old('category') == 'event' ? 'selected' : '' }}>Event Related</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority -->
                        <div class="mb-4">
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority *</label>
                            <select name="priority" id="priority" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }} selected>Normal</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700">Message *</label>
                            <textarea name="message" id="message" rows="8" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Describe your issue in detail...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('support.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Submit Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
