<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Inertia\Inertia;

class ProjectsController extends Controller
{
    public function index() {
        $projects = auth()->user()->projects()->orderBy('updated_at', 'desc')->get();

        return Inertia::render('Projects/Index', compact('projects'));
    }

    public function show(Project $project) {
        $this->authorize('update', $project);

        return Inertia::render('Projects/Show', compact('project'));
    }

    public function create() {
        return Inertia::render('Projects/Create');
    }

    public function store() {
        $attributes = request()->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $project = auth()->user()->projects()->create($attributes);

        return redirect($project->path());
    }

    public function update(Project $project) {
        $this->authorize('update', $project);

        $project->update(request(['notes']));

        // return redirect($project->path());
    }
}
