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

    protected static function booted()
    {
        static::created(function ($project) {
            $project->addSection([
                'name' => 'Default Section',
                'weight' => 1,
            ]);
            // remove default section's created activity
            $project->sections->first()->activity->first()->delete();
        });
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class)->with('activity');
    }

    public function addTask($attributes) {
        return $this->tasks()->create($attributes);
    }

    public function sections() {
        return $this->hasMany(Section::class)->orderBy('weight', 'ASC')->with('tasks');
    }

    public function addSection($attributes) {
        return $this->sections()->create($attributes);
    }

    public function updateSectionWeights($order = false) {
        if($order) {
            foreach($order as $index => $id) {
                $section = $this->sections->where('id', $id)->first();
                $section->weight = $index + 1;
                $section->save();
            }
        } else {
            // used when a project is deleted
            foreach($this->sections as $index => $section) {
                $section->weight = $index + 1;
                $section->save();
            }
        }
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

    public function invite(User $user) {
        $this->users()->attach($user);
        $this->recordActivity('joined_project', $user->id);
    }

    public function uninvite(User $user) {
        $this->users()->detach($user->id);
        $this->recordActivity('left_project', $user->id);
    }

    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps()->orderBy('name', 'ASC');
    }

}
