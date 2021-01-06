<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Task;
use App\Models\Project;
use App\Http\Requests\TaskRequest;

class TasksController extends Controller
{
    public function create() {
        $projects = auth()->user()->projects()->orderBy('name')->with('tasks')->get();

        return Inertia::render('Tasks/Create', compact('projects'));
    }

    public function store(TaskRequest $request, Project $project) {
        $project->addTask($request->validated());

        return redirect($project->path());
    }

    public function update(TaskRequest $request, Project $project, Task $task) {
        $task->update($request->validated());

        return redirect($project->path());
    }

    public function complete(TaskRequest $request, Project $project, Task $task) {
        if(!$task->completed) {
            $task->complete();
        }
    }

    public function incomplete(TaskRequest $request, Project $project, Task $task) {
        if($task->completed) {
            $task->incomplete();
        }
    }
}
