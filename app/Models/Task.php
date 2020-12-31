<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;
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
        'due_date'
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

    public function project() {
        return $this->belongsTo(Project::class);
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
