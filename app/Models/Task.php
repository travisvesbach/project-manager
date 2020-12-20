<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'completed',
        'project_id'
    ];

    protected $appends = [
        'path'
    ];

    protected $casts = [
        'description' => 'array'
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
}
