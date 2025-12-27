<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
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
        ]);

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
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
