<?php

namespace App\Http\Controllers;

use App\Models\TicketAttachment;
use Illuminate\Http\Request;

class TicketAttachmentController extends Controller
{
    public function index(Request $request)
    {
        return TicketAttachment::query()
            ->with(['ticket', 'user'])
            ->when($request->ticket_id, fn ($q) =>
                $q->where('ticket_id', $request->ticket_id)
            )
            ->latest()
            ->paginate(20);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ticket_id' => ['required', 'exists:tickets,id'],
            'file_name' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:255'],
            'file_size' => ['required', 'integer', 'min:1'],
            'uploaded_by' => ['required', 'exists:users,id'],
        ]);

        // uploaded_at pakai unix timestamp (sesuai schema)
        $data['uploaded_at'] = time();

        $attachment = TicketAttachment::create($data);

        return response()->json(
            $attachment->load(['ticket', 'user']),
            201
        );
    }

    public function show(TicketAttachment $ticketAttachment)
    {
        return $ticketAttachment->load(['ticket', 'user']);
    }

    public function destroy(TicketAttachment $ticketAttachment)
    {
        $ticketAttachment->delete(); // soft delete (model sudah pakai SoftDeletes)

        return response()->json([
            'message' => 'Attachment deleted'
        ]);
    }

    /**
     * Restore soft-deleted attachment
     */
    public function restore($id)
    {
        $attachment = TicketAttachment::withTrashed()->findOrFail($id);
        $attachment->restore();

        return response()->json([
            'message' => 'Attachment restored'
        ]);
    }

    /**
     * Permanent delete
     */
    public function forceDelete($id)
    {
        $attachment = TicketAttachment::withTrashed()->findOrFail($id);
        $attachment->forceDelete();

        return response()->json([
            'message' => 'Attachment permanently deleted'
        ]);
    }
}
