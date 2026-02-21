<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TicketCategoryController extends Controller
{
    public function index()
    {
        return TicketCategory::query()
            ->withCount('tickets')
            ->latest()
            ->paginate(15);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:ticket_categories,name'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'size:7'], // #FFFFFF
        ]);

        $category = TicketCategory::create($data);

        return response()->json($category, 201);
    }

    public function show(TicketCategory $ticketCategory)
    {
        return $ticketCategory->load('tickets');
    }

    public function update(Request $request, TicketCategory $ticketCategory)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ticket_categories', 'name')->ignore($ticketCategory->id),
            ],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'size:7'],
        ]);

        $ticketCategory->update($data);

        return response()->json($ticketCategory);
    }

    public function destroy(TicketCategory $ticketCategory)
    {
        // optional safety: block delete if still used
        if ($ticketCategory->tickets()->exists()) {
            return response()->json([
                'message' => 'Category still has tickets'
            ], 409);
        }

        $ticketCategory->delete(); // soft delete

        return response()->json([
            'message' => 'Ticket category deleted'
        ]);
    }

    /**
     * Restore soft-deleted category
     */
    public function restore($id)
    {
        $category = TicketCategory::withTrashed()->findOrFail($id);
        $category->restore();

        return response()->json([
            'message' => 'Ticket category restored'
        ]);
    }

    /**
     * Permanent delete
     */
    public function forceDelete($id)
    {
        $category = TicketCategory::withTrashed()->findOrFail($id);

        if ($category->tickets()->withTrashed()->exists()) {
            return response()->json([
                'message' => 'Category still referenced by tickets'
            ], 409);
        }

        $category->forceDelete();

        return response()->json([
            'message' => 'Ticket category permanently deleted'
        ]);
    }
}
