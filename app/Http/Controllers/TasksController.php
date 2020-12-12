<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Task;
use App\Models\Project;

class TasksController extends Controller
{
    public function create() {
        $projects = auth()->user()->projects()->orderBy('name')->with('tasks')->get();

        return Inertia::render('Tasks/Create', compact('projects'));
    }

    public function store(Project $project) {
        $this->authorize('update', $project);

        request()->validate(['name' => 'required']);

        $project->addTask(request('name'));

        return redirect($project->path());
    }

    public function update(Project $project, Task $task) {
        $this->authorize('update', $project);

        // request()->validate(['name' => 'required']);

        $task->update([
            'name' => request('name'),
            'description' => request('description'),
            'completed' => request('completed'),
        ]);

        return redirect($project->path());
    }
}
