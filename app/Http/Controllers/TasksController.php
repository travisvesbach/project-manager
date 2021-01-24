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

    public function show(Project $project, Task $task) {
        return redirect()->route('projects.show', ['project' => $project])->with(['task_to_show' => $task->id]);
    }

    public function store(TaskRequest $request, Project $project) {
        $project->addTask($request->validated());

        return redirect($project->path());
    }

    public function update(TaskRequest $request, Project $project, Task $task) {
        $task->update($request->validated());

        return redirect($project->path());
    }

    public function destroy(Project $project, Task $task) {
        $this->authorize('update', $project);

        $section = $task->section;

        $task->delete();

        $section->updateTaskWeights();

        return redirect($project->path());
    }
}
