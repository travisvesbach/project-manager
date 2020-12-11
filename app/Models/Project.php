<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id'
    ];

    protected $appends = [
        'path'
    ];

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function addTask($name) {
        return $this->tasks()->create(compact('name'));
    }

    public function path() {
        return "/projects/{$this->id}";
    }

    public function getPathAttribute() {
        return $this->path();
    }
}
