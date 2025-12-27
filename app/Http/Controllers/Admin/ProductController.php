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
        ]);

        // Upload file
        $filePath = $this->fileService->uploadProductFile($request->file('file'));
        $validated['file_path'] = $filePath;

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
        ]);

        // Upload new file if provided
        if ($request->hasFile('file')) {
            // Delete old file
            $this->fileService->deleteFile($product->file_path);
            // Upload new file
            $validated['file_path'] = $this->fileService->uploadProductFile($request->file('file'));
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Delete file
        $this->fileService->deleteFile($product->file_path);
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
