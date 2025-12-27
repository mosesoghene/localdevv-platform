<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Services\FileService;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    public function index()
    {
        $portfolios = Portfolio::latest()->paginate(15);
        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:portfolios',
            'description' => 'required|string',
            'client_name' => 'nullable|string|max:255',
            'project_url' => 'nullable|url',
            'project_type' => 'required|in:web_development,mobile_app,custom_software,consulting',
            'technologies_used' => 'nullable|array',
            'completion_date' => 'nullable|date',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $this->fileService->uploadImage($request->file('featured_image'), 'portfolios');
        }

        Portfolio::create($validated);

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio created successfully!');
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.portfolios.edit', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:portfolios,slug,' . $portfolio->id,
            'description' => 'required|string',
            'client_name' => 'nullable|string|max:255',
            'project_url' => 'nullable|url',
            'project_type' => 'required|in:web_development,mobile_app,custom_software,consulting',
            'technologies_used' => 'nullable|array',
            'completion_date' => 'nullable|date',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($portfolio->featured_image) {
                $this->fileService->deleteImage($portfolio->featured_image);
            }
            $validated['featured_image'] = $this->fileService->uploadImage($request->file('featured_image'), 'portfolios');
        }

        $portfolio->update($validated);

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio updated successfully!');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->featured_image) {
            $this->fileService->deleteImage($portfolio->featured_image);
        }
        
        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio deleted successfully!');
    }
}
