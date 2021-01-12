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

    public function show(Project $project) {
        $this->authorize('update', $project);

        $project->load(['tasks', 'activity', 'users', 'sections'])->get();

        $users = null;
        if(auth()->user()->id == $project->owner->id) {
            $users = User::select([
                    'users.id',
                    'users.name',
                    'users.email'
                ])
                ->where('users.id', '!=', $project->owner_id)
                ->orderBy('name', 'ASC')
                ->get();
        }

        return Inertia::render('Projects/Show', compact(['project', 'users']));
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

        return redirect($project->path());
    }

    public function update(Project $project) {
        $this->authorize('update', $project);

        $project->update(request([
            'name',
            'description',
            'layout'
        ]));

        return redirect($project->path());
    }

    public function updateSectionWeights(Request $request, Project $project) {
        $this->authorize('update', $project);

        $project->updateSectionWeights($request->input('ids_by_weight'));

        return redirect($project->path());
    }

    public function updateTaskWeights(Request $request, Project $project) {
        $this->authorize('update', $project);

        $project->updateTaskWeights($request->input('ids_by_weight'));

        return redirect($project->path());
    }

    public function destroy(Project $project) {
        $this->authorize('update', $project);

        $project->delete();

        return redirect('/projects');
    }
}
