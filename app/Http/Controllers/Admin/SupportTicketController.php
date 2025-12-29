<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedTo', 'replies']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket_number', 'like', '%' . $request->search . '%')
                    ->orWhere('subject', 'like', '%' . $request->search . '%');
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.support-tickets.index', compact('tickets'));
    }

    public function show(SupportTicket $supportTicket)
    {
        $supportTicket->load(['replies.user', 'user', 'assignedTo']);
        $admins = User::where('is_admin', true)->get();

        return view('admin.support-tickets.show', compact('supportTicket', 'admins'));
    }

    public function reply(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        TicketReply::create([
            'support_ticket_id' => $supportTicket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_staff_reply' => true,
        ]);

        return redirect()
            ->route('admin.support-tickets.show', $supportTicket)
            ->with('success', 'Reply sent successfully!');
    }

    public function updateStatus(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,waiting,resolved,closed',
        ]);

        if ($validated['status'] === 'resolved') {
            $supportTicket->markAsResolved();
        } elseif ($validated['status'] === 'closed') {
            $supportTicket->markAsClosed();
        } else {
            $supportTicket->update(['status' => $validated['status']]);
        }

        return redirect()
            ->route('admin.support-tickets.show', $supportTicket)
            ->with('success', 'Ticket status updated successfully!');
    }

    public function assign(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $supportTicket->update([
            'assigned_to' => $validated['assigned_to'],
        ]);

        return redirect()
            ->route('admin.support-tickets.show', $supportTicket)
            ->with('success', 'Ticket assigned successfully!');
    }

    public function updatePriority(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $supportTicket->update(['priority' => $validated['priority']]);

        return redirect()
            ->route('admin.support-tickets.show', $supportTicket)
            ->with('success', 'Ticket priority updated successfully!');
    }
}
