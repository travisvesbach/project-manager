<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;
use App\Models\Activity;
use App\Traits\RecordsActivity;
use App\Notifications\InvitedToProject;

class Project extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'layout'
    ];

    protected $appends = [
        'path',
        'all_users'
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
        return $this->hasMany(Task::class)->with('activity', 'users');
    }

    public function updateTaskWeights($order = false) {
        if($order) {
            foreach($order as $sectionArray) {
                $section = $this->sections->where('id', $sectionArray[0])->first();
                foreach($sectionArray[1] as $taskArray) {
                    $task = $this->tasks->where('id', $taskArray[0])->first();
                    $task->section_id = $section->id;
                    $task->weight = $taskArray[1];
                    $task->save();

                }
            }
        }
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
            // used when a section is deleted
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
        $user->notify(new InvitedToProject($this));
    }

    public function uninvite(User $user) {
        $this->users()->detach($user->id);
        $this->recordActivity('left_project', $user->id);
        foreach($this->tasks as $task) {
            $task->uninvite($user);
        }
    }

    public function users() {
        return $this->belongsToMany(User::class)->withTimestamps()->orderBy('name', 'ASC');
    }

    public function getAllUsersAttribute() {
        return array_merge([$this->owner], $this->users->toArray());
    }

}
