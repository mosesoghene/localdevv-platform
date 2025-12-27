<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectRequest;
use Illuminate\Http\Request;

class ProjectRequestController extends Controller
{
    public function index()
    {
        $projectRequests = ProjectRequest::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.project-requests.index', compact('projectRequests'));
    }

    public function updateStatus(Request $request, ProjectRequest $projectRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $projectRequest->update($validated);

        return back()->with('success', 'Project request status updated successfully!');
    }

    public function destroy(ProjectRequest $projectRequest)
    {
        $projectRequest->delete();

        return redirect()->route('admin.project-requests.index')
            ->with('success', 'Project request deleted successfully!');
    }
}
