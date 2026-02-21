<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\TicketResource;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        // return Ticket::query()
        //     ->with(['user', 'ticket_category'])
        //     ->when($request->status, fn ($q) =>
        //         $q->where('status', $request->status)
        //     )
        //     ->when($request->priority, fn ($q) =>
        //         $q->where('priority', $request->priority)
        //     )
        //     ->latest()->paginate(15);
        return TicketResource::collection(
            Ticket::query()
            ->with(['user', 'ticket_category'])
            ->when($request->status, fn ($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->priority, fn ($q) =>
                $q->where('priority', $request->priority)
            )
            ->latest()
            ->cursorPaginate(15)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:ticket_categories,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'created_by' => ['required', 'exists:users,id'],
            'status' => ['nullable', Rule::in(['open', 'in_progress', 'resolved', 'closed'])],
            'priority' => ['nullable', Rule::in(['low', 'medium', 'high', 'urgent'])],
        ]);

        $ticket = Ticket::create($data);

        return response()->json($ticket->load(['user', 'ticket_category']), 201);
    }

    public function show(Ticket $ticket)
    {
        return $ticket->load(['user', 'ticket_category', 'ticket_attachments']);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:ticket_categories,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'status' => ['nullable', Rule::in(['open', 'in_progress', 'resolved', 'closed'])],
            'priority' => ['nullable', Rule::in(['low', 'medium', 'high', 'urgent'])],
        ]);

        // auto set resolved_at
        if (
            isset($data['status']) &&
            $data['status'] === 'resolved' &&
            $ticket->resolved_at === null
        ) {
            $data['resolved_at'] = now();
        }

        if (
            isset($data['status']) &&
            $data['status'] !== 'resolved'
        ) {
            $data['resolved_at'] = null;
        }

        $ticket->update($data);

        return response()->json($ticket->fresh()->load(['user', 'ticket_category']));
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete(); // soft delete

        return response()->json([
            'message' => 'Ticket deleted'
        ]);
    }

    /**
     * Restore soft-deleted ticket
     */
    public function restore($id)
    {
        $ticket = Ticket::withTrashed()->findOrFail($id);
        $ticket->restore();

        return response()->json([
            'message' => 'Ticket restored'
        ]);
    }

    /**
     * Permanent delete
     */
    public function forceDelete($id)
    {
        $ticket = Ticket::withTrashed()->findOrFail($id);
        $ticket->forceDelete();

        return response()->json([
            'message' => 'Ticket permanently deleted'
        ]);
    }
}
