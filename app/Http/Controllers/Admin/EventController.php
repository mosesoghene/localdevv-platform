<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\FileService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    public function index()
    {
        $events = Event::latest()->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'event_type' => 'required|in:webinar,workshop,meetup,conference',
            'max_attendees' => 'nullable|integer|min:1',
            'registration_url' => 'nullable|url',
            'is_published' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Upload featured image if provided
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $this->fileService->uploadImage($request->file('featured_image'), 'events');
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:events,slug,' . $event->id,
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'event_type' => 'required|in:webinar,workshop,meetup,conference',
            'max_attendees' => 'nullable|integer|min:1',
            'registration_url' => 'nullable|url',
            'is_published' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Upload new featured image if provided
        if ($request->hasFile('featured_image')) {
            if ($event->featured_image) {
                $this->fileService->deleteImage($event->featured_image);
            }
            $validated['featured_image'] = $this->fileService->uploadImage($request->file('featured_image'), 'events');
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        if ($event->featured_image) {
            $this->fileService->deleteImage($event->featured_image);
        }
        
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
