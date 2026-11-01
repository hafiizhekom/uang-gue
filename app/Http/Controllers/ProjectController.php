<?php

namespace App\Http\Controllers;

use App\Enums\ProjectRole;
use App\Http\Requests\StoreProjectMemberRequest;
use App\Http\Requests\StoreUpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use App\Services\UserSetupService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * List all projects the authenticated user belongs to.
     */
    public function index(Request $request)
    {
        $projects = $request->user()->projects()
            ->with(['creator'])
            ->withCount('users')
            ->latest()
            ->get();

        return $this->data(ProjectResource::collection($projects));
    }

    /**
     * Store a newly created project.
     */
    public function store(StoreUpdateProjectRequest $request, UserSetupService $setupService)
    {
        $validated = $request->validated();
        $user = $request->user();

        $project = Project::create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'created_by'  => $user->id,
        ]);

        // Attach creator as owner
        $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

        // Initialize starter pack master data & templates
        $setupService->initializeProjectData($project, $user);

        return $this->success(
            new ProjectResource($project->load(['creator', 'users'])),
            'Project created successfully',
            201
        );
    }

    /**
     * Display the specified project with its members.
     */
    public function show(Request $request, Project $project)
    {
        $user = $request->user();

        if (!$user->belongsToProject($project->id)) {
            return $this->error('You do not have access to this project.', 403);
        }

        return $this->data(new ProjectResource($project->load(['creator', 'users'])));
    }

    /**
     * Update the specified project.
     */
    public function update(StoreUpdateProjectRequest $request, Project $project)
    {
        $user = $request->user();

        // Check if user is owner of the project
        $membership = $project->users()->where('users.id', $user->id)->first();
        if (!$membership || $membership->pivot->role !== ProjectRole::OWNER->value) {
            return $this->error('Only project owners can update project details.', 403);
        }

        $validated = $request->validated();
        $project->update($validated);

        return $this->success(new ProjectResource($project->load(['creator', 'users'])), 'Project updated successfully');
    }

    /**
     * Soft delete the project.
     */
    public function destroy(Request $request, Project $project)
    {
        $user = $request->user();

        // Only owner can delete project
        $membership = $project->users()->where('users.id', $user->id)->first();
        if (!$membership || $membership->pivot->role !== ProjectRole::OWNER->value) {
            return $this->error('Only project owners can delete the project.', 403);
        }

        $project->delete();

        return $this->success(null, 'Project deleted successfully');
    }

    /**
     * Add a member to the project by email.
     */
    public function addMember(StoreProjectMemberRequest $request, Project $project)
    {
        $currentUser = $request->user();

        // Check if current user is owner
        $currentMembership = $project->users()->where('users.id', $currentUser->id)->first();
        if (!$currentMembership || $currentMembership->pivot->role !== ProjectRole::OWNER->value) {
            return $this->error('Only project owners can add members.', 403);
        }

        $validated = $request->validated();
        $targetUser = User::where('email', $validated['email'])->first();

        if ($project->users()->where('users.id', $targetUser->id)->exists()) {
            return $this->error('User is already a member of this project.', 422);
        }

        $project->users()->attach($targetUser->id, [
            'role' => $validated['role'] ?? ProjectRole::MEMBER->value,
        ]);

        return $this->success(new ProjectResource($project->load(['creator', 'users'])), 'Member added successfully');
    }

    /**
     * Remove a member from the project.
     */
    public function removeMember(Request $request, Project $project, User $user)
    {
        $currentUser = $request->user();

        $currentMembership = $project->users()->where('users.id', $currentUser->id)->first();
        if (!$currentMembership) {
            return $this->error('You do not have access to this project.', 403);
        }

        // Allow user to leave themselves OR owner to remove another member
        $isSelf = $currentUser->id === $user->id;
        $isOwner = $currentMembership->pivot->role === ProjectRole::OWNER->value;

        if (!$isSelf && !$isOwner) {
            return $this->error('Only project owners can remove other members.', 403);
        }

        // Owner cannot leave/be removed if they are the creator/only owner
        $targetMembership = $project->users()->where('users.id', $user->id)->first();
        if ($targetMembership && $targetMembership->pivot->role === ProjectRole::OWNER->value) {
            $ownerCount = $project->users()->wherePivot('role', ProjectRole::OWNER->value)->count();
            if ($ownerCount <= 1) {
                return $this->error('Cannot remove the only owner of the project.', 422);
            }
        }

        $project->users()->detach($user->id);

        return $this->success(null, 'Member removed successfully');
    }
}
