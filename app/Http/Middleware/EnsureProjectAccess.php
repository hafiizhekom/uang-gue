<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProjectAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        $projectId = $request->header('X-Project-Id')
            ?: $request->header('x-project-id')
            ?: $request->input('project_id');

        if ($projectId) {
            $project = $user->projects()->where('projects.id', $projectId)->first();

            if (!$project) {
                return response()->json([
                    'status'  => 'Fail',
                    'message' => 'You do not have access to this project.',
                ], 403);
            }
        } else {
            // Default to user's first project if available
            $project = $user->projects()->first();

            if (!$project) {
                return response()->json([
                    'status'  => 'Fail',
                    'message' => 'No active project found. Please create or join a project first.',
                ], 400);
            }
        }

        $request->attributes->set('current_project', $project);
        app()->instance('current_project', $project);

        $response = $next($request);

        // Include the active project ID in response header
        if (method_exists($response, 'header')) {
            $response->header('X-Project-Id', (string) $project->id);
        }

        return $response;
    }

    /**
     * Get the currently active project from the app container.
     */
    public static function current(): ?Project
    {
        if (app()->bound('current_project')) {
            return app('current_project');
        }

        return null;
    }
}
