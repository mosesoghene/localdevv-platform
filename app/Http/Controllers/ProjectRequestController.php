<?php

namespace App\Http\Controllers;

use App\Models\ProjectRequest;
use Illuminate\Http\Request;

class ProjectRequestController extends Controller
{
    public function store(Request $request)
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
