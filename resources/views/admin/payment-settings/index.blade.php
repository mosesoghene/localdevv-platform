<x-admin-layout>
    <x-slot name="header">Payment Settings</x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">
        @foreach($providers as $provider)
            @php
                $setting = $settings->get($provider);
                $isEnabled = $setting?->is_enabled ?? false;
                $isTestMode = $setting?->is_test_mode ?? true;
            @endphp

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold capitalize">{{ $provider }}</h3>
                            <p class="text-sm text-gray-600">
                                Configure {{ ucfirst($provider) }} payment gateway
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            @if($isEnabled)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Active</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">Inactive</span>
                            @endif
                            
                            @if($setting)
                                <form action="{{ route('admin.payment-settings.toggle', $provider) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 {{ $isEnabled ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white rounded-md font-semibold transition">
                                        {{ $isEnabled ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.payment-settings.update', $provider) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Public Key -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Public Key</label>
                                <input type="text" name="public_key" value="{{ $setting?->public_key }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Secret Key -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Secret Key</label>
                                <input type="password" name="secret_key" value="{{ $setting?->secret_key }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            @if($provider === 'moniepoint')
                            <!-- Merchant ID (Moniepoint only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Merchant ID</label>
                                <input type="text" name="merchant_id" value="{{ $setting?->merchant_id }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @endif

                            @if($provider === 'flutterwave')
                            <!-- Encryption Key (Flutterwave only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Encryption Key</label>
                                <input type="text" name="encryption_key" value="{{ $setting?->encryption_key }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            @endif

                            <!-- Test Mode -->
                            <div class="flex items-center">
                                <input type="checkbox" name="is_test_mode" id="test_mode_{{ $provider }}" value="1" 
                                    {{ $isTestMode ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="test_mode_{{ $provider }}" class="ml-2 block text-sm text-gray-700">
                                    Enable Test Mode
                                </label>
                            </div>

                            <!-- Is Enabled -->
                            <div class="flex items-center">
                                <input type="checkbox" name="is_enabled" id="enabled_{{ $provider }}" value="1" 
                                    {{ $isEnabled ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="enabled_{{ $provider }}" class="ml-2 block text-sm text-gray-700">
                                    Enable this provider (disables others)
                                </label>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex">
            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Important Notes:</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Only one payment provider can be active at a time</li>
                        <li>Use Test Mode for development and testing</li>
                        <li>Never share your secret keys publicly</li>
                        <li>Webhook URL: <code class="bg-yellow-100 px-2 py-1 rounded">{{ url('/webhook/payment/{provider}') }}</code></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
