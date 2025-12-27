<x-admin-layout>
    <x-slot name="header">Edit Product: {{ $product->name }}</x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Product Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700">Slug *</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('slug')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                                <select name="category_id" id="category_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Product Type -->
                            <div>
                                <label for="product_type" class="block text-sm font-medium text-gray-700">Product Type *</label>
                                <select name="product_type" id="product_type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Type</option>
                                    <option value="script" {{ old('product_type', $product->product_type) == 'script' ? 'selected' : '' }}>Script</option>
                                    <option value="theme" {{ old('product_type', $product->product_type) == 'theme' ? 'selected' : '' }}>Theme</option>
                                    <option value="plugin" {{ old('product_type', $product->product_type) == 'plugin' ? 'selected' : '' }}>Plugin</option>
                                    <option value="template" {{ old('product_type', $product->product_type) == 'template' ? 'selected' : '' }}>Template</option>
                                </select>
                                @error('product_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price ($) *</label>
                                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Version -->
                            <div>
                                <label for="version" class="block text-sm font-medium text-gray-700">Version</label>
                                <input type="text" name="version" id="version" value="{{ old('version', $product->version) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('version')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Demo URL -->
                            <div>
                                <label for="demo_url" class="block text-sm font-medium text-gray-700">Demo URL</label>
                                <input type="url" name="demo_url" id="demo_url" value="{{ old('demo_url', $product->demo_url) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('demo_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Documentation URL -->
                            <div>
                                <label for="documentation_url" class="block text-sm font-medium text-gray-700">Documentation URL</label>
                                <input type="url" name="documentation_url" id="documentation_url" value="{{ old('documentation_url', $product->documentation_url) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('documentation_url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                            <textarea name="description" id="description" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div class="mt-6">
                            <label for="file" class="block text-sm font-medium text-gray-700">Product File (ZIP/RAR/7Z) <span class="text-gray-500">Max: 500MB - Leave empty to keep current file</span></label>
                            <p class="text-sm text-gray-500 mb-2">Current file: {{ $product->file_path }}</p>
                            <input type="file" name="file" id="file" accept=".zip,.rar,.7z"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Featured Image Upload -->
                        <div class="mt-6">
                            <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image <span class="text-gray-500">Max: 2MB - Leave empty to keep current image</span></label>
                            @if($product->featured_image)
                                <div class="mt-2 mb-3">
                                    <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="max-w-xs rounded-md border border-gray-300">
                                    <p class="text-sm text-gray-500 mt-1">Current image</p>
                                </div>
                            @endif
                            <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="image-preview" class="mt-2 hidden">
                                <img src="" alt="Preview" class="max-w-xs rounded-md border border-gray-300">
                                <p class="text-sm text-gray-500 mt-1">New image preview</p>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.products.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-generate slug from name
        document.getElementById('name').addEventListener('input', function(e) {
            const slug = e.target.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
            document.getElementById('slug').value = slug;
        });

        // Image preview
        document.getElementById('featured_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-admin-layout>
