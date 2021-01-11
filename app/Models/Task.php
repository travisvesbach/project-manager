<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
use App\Models\Section;
use App\Models\Activity;
use App\Traits\RecordsActivity;

class Task extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'name',
        'description',
        'completed',
        'project_id',
        'section_id',
        'due_date',
        'weight'
    ];

    protected $appends = [
        'path'
    ];

    protected $casts = [
        'due_date' => 'date:Y-m-d',
        'description' => 'array',
        'completed' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'due_date'
    ];

    // updates the project's updated_at time wheneever this is updated
    protected $touches = ['project'];

    protected static function booted()
    {
        static::creating(function ($task) {
            // default to project's first section
            if(!$task->section_id) {
                $task->section_id = $task->project->sections->where('weight', 1)->first()->id;
            }
            // default weight to 1 or next available
            if(!$task->weight) {
                $task->weight = count($task->section->tasks) > 0 ? $task->section->tasks->sortByDesc('weight')->first()->weight + 1 : 1;
            }
        });
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function section() {
        return $this->belongsTo(Section::class);
    }

    public function path() {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }

    public function getPathAttribute() {
        return $this->path();
    }

    public function complete() {
        $this->update(['completed' => true]);

        $this->recordActivity('completed_task');
    }

    public function incomplete() {
        $this->update(['completed' => false]);

        $this->recordActivity('incompleted_task');
    }
}
