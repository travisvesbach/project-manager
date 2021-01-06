<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Http\Requests\ProjectInvitationRequest;

class ProjectInvitationsController extends Controller
{
    public function store(ProjectInvitationRequest $request, Project $project) {
        $user = User::where('id', $request->validated('id'))->first();

        $project->invite($user);

        return redirect($project->path());
    }
}
