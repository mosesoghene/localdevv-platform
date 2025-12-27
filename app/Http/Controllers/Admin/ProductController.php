<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\FileService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:script,theme,plugin,template',
            'price' => 'required|numeric|min:0',
            'version' => 'nullable|string|max:50',
            'demo_url' => 'nullable|url',
            'documentation_url' => 'nullable|url',
            'features' => 'nullable|array',
            'requirements' => 'nullable|array',
            'file' => 'required|file|mimetypes:application/zip,application/x-rar-compressed,application/x-7z-compressed|max:512000',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Upload file
        $filePath = $this->fileService->uploadProductFile($request->file('file'));
        $validated['file_path'] = $filePath;

        // Upload featured image if provided
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $this->fileService->uploadImage($request->file('featured_image'), 'products');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:script,theme,plugin,template',
            'price' => 'required|numeric|min:0',
            'version' => 'nullable|string|max:50',
            'demo_url' => 'nullable|url',
            'documentation_url' => 'nullable|url',
            'features' => 'nullable|array',
            'requirements' => 'nullable|array',
            'file' => 'nullable|file|mimetypes:application/zip,application/x-rar-compressed,application/x-7z-compressed|max:512000',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Upload new file if provided
        if ($request->hasFile('file')) {
            // Delete old file
            $this->fileService->deleteFile($product->file_path);
            // Upload new file
            $validated['file_path'] = $this->fileService->uploadProductFile($request->file('file'));
        }

        // Upload new featured image if provided
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($product->featured_image) {
                $this->fileService->deleteImage($product->featured_image);
            }
            $validated['featured_image'] = $this->fileService->uploadImage($request->file('featured_image'), 'products');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Delete file
        $this->fileService->deleteFile($product->file_path);
        
        // Delete featured image if exists
        if ($product->featured_image) {
            $this->fileService->deleteImage($product->featured_image);
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
