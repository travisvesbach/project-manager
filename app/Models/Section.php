<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Task;
use App\Traits\RecordsActivity;

class Section extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'name',
        'project_id',
        'weight'
    ];

    protected $appends = [
        'path',
    ];

    // updates the project's updated_at time wheneever this is updated
    protected $touches = ['project'];

    protected static function booted()
    {
        static::creating(function ($section) {
            if(!$section->weight) {
                $section->weight = count($section->project->sections) > 0 ? $section->project->sections->sortByDesc('weight')->first()->weight + 1 : 1;
            }
        });
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class)->orderBy('weight', 'ASC')->with('activity');
    }

    public function updateTaskWeights() {
        // used when a task is deleted or completed
        foreach($this->tasks->where('completed', false) as $index => $task) {
            $task->weight = $index + 1;
            $task->save();
        }
    }

    public function path() {
        return "/projects/{$this->project->id}/sections/{$this->id}";
    }

    public function getPathAttribute() {
        return $this->path();
    }
}
