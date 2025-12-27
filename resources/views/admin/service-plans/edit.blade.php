<x-admin-layout>
    <x-slot name="header">Edit Service Plan: {{ $servicePlan->name }}</x-slot>

    
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.service-plans.update', $servicePlan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name *</label>
                            <input type="text" name="name" value="{{ old('name', $servicePlan->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Slug *</label>
                            <input type="text" name="slug" value="{{ old('slug', $servicePlan->slug) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Plan Type *</label>
                            <select name="plan_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="priority_support" {{ $servicePlan->plan_type == 'priority_support' ? 'selected' : '' }}>Priority Support</option>
                                <option value="installation_service" {{ $servicePlan->plan_type == 'installation_service' ? 'selected' : '' }}>Installation Service</option>
                                <option value="maintenance_plan" {{ $servicePlan->plan_type == 'maintenance_plan' ? 'selected' : '' }}>Maintenance Plan</option>
                                <option value="vip_support" {{ $servicePlan->plan_type == 'vip_support' ? 'selected' : '' }}>VIP Support</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Billing Interval *</label>
                            <select name="billing_interval" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="monthly" {{ $servicePlan->billing_interval == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ $servicePlan->billing_interval == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="annually" {{ $servicePlan->billing_interval == 'annually' ? 'selected' : '' }}>Annually</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price ($) *</label>
                            <input type="number" name="price" value="{{ old('price', $servicePlan->price) }}" step="0.01" min="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ $servicePlan->is_active ? 'checked' : '' }} class="rounded">
                            <label class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                    </div>
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">Description *</label>
                        <textarea name="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $servicePlan->description) }}</textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('admin.service-plans.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">Cancel</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Update</button>
                    </div>
                </form>
            </div>
        </x-admin-layout>
