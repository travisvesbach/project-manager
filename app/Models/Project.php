<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;
use App\Models\Activity;
use App\Traits\RecordsActivity;

class Project extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'name',
        'description',
        'owner_id'
    ];

    protected $appends = [
        'path'
    ];

    protected static $recordableEvents = ['created', 'updated'];

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class)->with('activity');
    }

    public function addTask($attributes) {
        return $this->tasks()->create($attributes);
    }

    public function path() {
        return "/projects/{$this->id}";
    }

    public function getPathAttribute() {
        return $this->path();
    }

    // overwrite default from trait
    public function activity() {
        return $this->hasMany(Activity::class)->with(['subject', 'user']);
    }

}
