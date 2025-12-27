<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function index(Event $event)
    {
        $ticketTypes = $event->ticketTypes()->orderBy('price', 'asc')->get();
        return view('admin.ticket-types.index', compact('event', 'ticketTypes'));
    }

    public function create(Event $event)
    {
        return view('admin.ticket-types.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'total_quantity' => 'required|integer|min:1',
            'max_per_order' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
            'sale_starts_at' => 'nullable|date',
            'sale_ends_at' => 'nullable|date|after:sale_starts_at',
        ]);

        $event->ticketTypes()->create($validated);

        return redirect()->route('admin.events.ticket-types.index', $event)
            ->with('success', 'Ticket type created successfully!');
    }

    public function edit(Event $event, TicketType $ticketType)
    {
        return view('admin.ticket-types.edit', compact('event', 'ticketType'));
    }

    public function update(Request $request, Event $event, TicketType $ticketType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'total_quantity' => 'required|integer|min:' . $ticketType->sold_quantity,
            'max_per_order' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean',
            'sale_starts_at' => 'nullable|date',
            'sale_ends_at' => 'nullable|date|after:sale_starts_at',
        ]);

        $ticketType->update($validated);

        return redirect()->route('admin.events.ticket-types.index', $event)
            ->with('success', 'Ticket type updated successfully!');
    }

    public function destroy(Event $event, TicketType $ticketType)
    {
        if ($ticketType->sold_quantity > 0) {
            return redirect()->back()->with('error', 'Cannot delete ticket type with sold tickets.');
        }

        $ticketType->delete();

        return redirect()->route('admin.events.ticket-types.index', $event)
            ->with('success', 'Ticket type deleted successfully!');
    }
}
