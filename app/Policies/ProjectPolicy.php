<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Project $project) {
        return $user->is($project->owner) || $project->users->contains($user);
    }

    public function delete(User $user, Project $project) {
        return $user->is($project->owner);
    }
}
