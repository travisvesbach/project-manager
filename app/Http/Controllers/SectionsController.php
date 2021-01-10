<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Project;
use App\Models\Task;
use App\Http\Requests\SectionRequest;

class SectionsController extends Controller
{
    public function store(SectionRequest $request, Project $project) {
        $project->addSection($request->validated());

        return redirect($project->path());
    }

    public function update(SectionRequest $request, Project $project, Section $section) {
        $section->update($request->validated());

        return redirect($project->path());
    }

    public function destroy(Project $project, Section $section) {
        $this->authorize('update', $project);

        $section->delete();

        $project->updateSectionWeights();

        return redirect($project->path());
    }
}
