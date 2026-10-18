<?php

namespace App\Traits;

use App\Models\Project;
use App\Http\Middleware\EnsureProjectAccess;

trait HasProjectContext
{
    /**
     * Get the active Project instance for the current request.
     */
    protected function currentProject(): ?Project
    {
        return request()->attributes->get('current_project') ?: EnsureProjectAccess::current();
    }

    /**
     * Get the active Project ID for the current request.
     */
    protected function currentProjectId(): ?int
    {
        return $this->currentProject()?->id;
    }
}
