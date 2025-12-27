<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Event;
use App\Models\Portfolio;
use App\Models\ProjectRequest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->latest()
            ->take(8)
            ->get();

        $upcomingEvents = Event::where('is_published', true)
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(3)
            ->get();

        $featuredPortfolios = Portfolio::where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home', compact('featuredProducts', 'upcomingEvents', 'featuredPortfolios'));
    }

    public function storeProjectRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'project_type' => 'required|string|max:255',
            'budget_range' => 'nullable|string|max:255',
            'timeline' => 'nullable|string|max:255',
            'description' => 'required|string',
        ]);

        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        ProjectRequest::create($validated);

        return back()->with('success', 'Your project request has been submitted successfully!');
    }
}
