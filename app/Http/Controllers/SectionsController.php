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

        return redirect($project->path())->with(['flash_message' => $request->input('name') . ' created', 'flash_status' => 'success']);
    }

    public function update(SectionRequest $request, Project $project, Section $section) {
        $section->update($request->validated());

        return redirect($project->path());
    }

    public function destroy(Request $request, Project $project, Section $section) {
        $this->authorize('update', $project);

        if($request && $request->filled('tasks') && $request->input('tasks') == 'keep') {
            $new_seciton = $project->sections->where('id', '!=', $section->id)->first();
            $weight = count($new_seciton->tasks) > 0 ? $new_seciton->tasks->last()->weight + 1 : 1;
            foreach($section->tasks as $task) {
                $task->section_id = $new_seciton->id;
                if(!$task->completed_at) {
                    $task->weight = $weight;
                    $weight++;
                }
                $task->save();
            }
        } else if($request && $request->filled('tasks') && $request->input('tasks') == 'delete') {
            foreach($section->tasks as $task) {
                $task->delete();
            }
        }

        $section->delete();

        $project->updateSectionWeights();

        return redirect($project->path())->with(['flash_message' => $section->name . ' deleted', 'flash_status' => 'danger']);
    }
}
