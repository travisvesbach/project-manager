<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Http\Requests\ProjectUserRequest;

class ProjectUsersController extends Controller
{
    public function store(ProjectUserRequest $request, Project $project) {
        $user = User::where('id', $request->validated('id'))->first();

        $project->invite($user);

        return redirect($project->path());
    }

    public function changeOwner(Request $request, Project $project, User $user) {
        $this->authorize('delete', $project);

        $project->changeOwner($user);

        return redirect($project->path());
    }

    public function destroy(Project $project, User $user) {
        $this->authorize('delete', $project);

        $project->uninvite($user);

        return redirect($project->path());
    }
}
