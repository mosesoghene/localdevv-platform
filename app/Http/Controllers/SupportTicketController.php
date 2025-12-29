<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // List all tickets for current user
    public function index()
    {
        $tickets = Auth::user()
            ->supportTickets()
            ->with('replies')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('support.index', compact('tickets'));
    }

    // Show create ticket form
    public function create()
    {
        return view('support.create');
    }

    // Store new ticket
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|in:general,technical,billing,product,service,event',
            'priority' => 'required|in:low,normal,high,urgent',
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'ticket_number' => SupportTicket::generateTicketNumber(),
            'subject' => $validated['subject'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'message' => $validated['message'],
            'status' => 'open',
        ]);

        return redirect()
            ->route('support.show', $ticket)
            ->with('success', 'Support ticket created successfully! Ticket #' . $ticket->ticket_number);
    }

    // Show single ticket with replies
    public function show(SupportTicket $supportTicket)
    {
        // Check authorization
        if ($supportTicket->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $supportTicket->load(['replies.user', 'user', 'assignedTo']);

        return view('support.show', compact('supportTicket'));
    }

    // Add reply to ticket
    public function reply(Request $request, SupportTicket $supportTicket)
    {
        // Check authorization
        if ($supportTicket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        TicketReply::create([
            'support_ticket_id' => $supportTicket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_staff_reply' => false,
        ]);

        // Reopen ticket if it was resolved or closed
        if ($supportTicket->status === 'resolved' || $supportTicket->status === 'closed') {
            $supportTicket->reopen();
        }

        return redirect()
            ->route('support.show', $supportTicket)
            ->with('success', 'Reply added successfully!');
    }

    // Close ticket
    public function close(SupportTicket $supportTicket)
    {
        // Check authorization
        if ($supportTicket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $supportTicket->markAsClosed();

        return redirect()
            ->route('support.index')
            ->with('success', 'Ticket closed successfully!');
    }
}
