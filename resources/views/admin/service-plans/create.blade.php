<x-admin-layout>
    <x-slot name="header">Create Service Plan</x-slot>

    
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.service-plans.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slug *</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Plan Type *</label>
                            <select name="plan_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Select Type</option>
                                <option value="priority_support">Priority Support</option>
                                <option value="installation_service">Installation Service</option>
                                <option value="maintenance_plan">Maintenance Plan</option>
                                <option value="vip_support">VIP Support</option>
                            </select>
                            @error('plan_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Billing Interval *</label>
                            <select name="billing_interval" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="annually">Annually</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price ($) *</label>
                            <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked class="rounded">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">Description *</label>
                        <textarea name="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.service-plans.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancel</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('name').addEventListener('input', function(e) {
            document.getElementById('slug').value = e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        });
    </script>
</x-admin-layout>
