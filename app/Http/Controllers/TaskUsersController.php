<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Http\Requests\TaskUserRequest;

class TaskUsersController extends Controller
{
    public function store(TaskUserRequest $request, Project $project, Task $task) {
        $user = User::where('id', $request->validated('id'))->first();

        $task->invite($user);

        return redirect($task->path());
    }

    public function destroy(Project $project, Task $task, User $user) {
        $this->authorize('update', $project);

        $task->uninvite($user);

        return redirect($task->path());
    }
}
