<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Inertia\Inertia;

class ProjectsController extends Controller
{
    public function index() {
        $projects = auth()->user()->allProjects();

        return Inertia::render('Projects/Index', compact('projects'));
    }

    public function show(Request $request, Project $project) {
        $this->authorize('update', $project);

        $project->load(['tasks', 'activity', 'sections'])->get();

        $users = null;
        if(auth()->user()->id == $project->owner->id) {
            $users = User::select([
                    'users.id',
                    'users.name',
                    'users.email',
                    'users.role'
                ])
                // ->where('users.id', '!=', $project->owner_id)
                ->orderBy('name', 'ASC')
                ->get();
        }
        $taskToShow = null;
        if(request()->session()->get('task_to_show')) {
            $taskToShow = request()->session()->get('task_to_show');
        }

        return Inertia::render('Projects/Show', compact(['project', 'users', 'taskToShow']));
    }

    public function create() {
        return Inertia::render('Projects/Create');
    }

    public function store() {
        $attributes = request()->validate([
            'name' => 'required',
            'description' => 'required',
            'layout' => 'required'
        ]);

        $project = auth()->user()->projects()->create($attributes);

        return redirect($project->path())->with(['flash_message' => 'project created', 'flash_status' => 'success']);
    }

    public function update(Project $project) {
        $this->authorize('update', $project);

        $project->update(request([
            'name',
            'description',
            'layout'
        ]));

        return redirect($project->path())->with(['flash_message' => 'project updated', 'flash_status' => 'success']);
    }

    public function updateSectionWeights(Request $request, Project $project) {
        $this->authorize('update', $project);

        $project->updateSectionWeights($request->input('sections_array'));

        return redirect($project->path());
    }

    public function updateTaskWeights(Request $request, Project $project) {
        $this->authorize('update', $project);

        $project->updateTaskWeights($request->input('tasks_array'));

        return redirect($project->path());
    }

    public function destroy(Project $project) {
        $this->authorize('update', $project);

        $project->delete();

        return redirect('/projects')->with(['flash_message' => 'project deleted', 'flash_status' => 'danger']);
    }
}
