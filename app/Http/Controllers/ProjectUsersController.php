<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Http\Requests\ProjectUserRequest;
use Auth;

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
        if(Auth::user()->id == $user->id && $user->id != $project->owner_id) {
            $project->uninvite($user);
            return redirect('/projects')->with(['flash_message' => 'You left ' . $project->name, 'flash_status' => 'danger']);
        }
        $this->authorize('delete', $project);

        $project->uninvite($user);

        return redirect($project->path())->with(['flash_message' => $user->name . ' was removed from the project', 'flash_status' => 'danger']);
    }
}
