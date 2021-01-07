<?php

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Support\Arr;

trait RecordsActivity
{
    public $oldAttributes = [];

    public static function bootRecordsActivity() {
        foreach (self::recordableEvents() as $event) {
            static::$event(function($model) use ($event) {
                $model->recordActivity($model->activityDescription($event));
            });

            if($event === 'updated') {
                static::updating(function($model) {
                    $model->oldAttributes = $model->getRawOriginal();
                });
            }
        }
    }

    // allows events to be overwritten using `protected static $recordableEvents = [];` in model
    protected static function recordableEvents() {
        if(isset(static::$recordableEvents)) {
            return static::$recordableEvents;
        }

        return ['created', 'updated', 'deleted'];
    }

    protected function activityDescription($description) {
        return $description . '_' . strtolower((class_basename($this)));
    }

    public function activity() {
        return $this->morphMany(Activity::class, 'subject')->with(['project', 'subject', 'user']);
    }

    public function recordActivity($description, $user_id = null) {
        $this->activity()->create([
            'user_id' => $user_id ?? $this->activityOwner()->id,
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id,
        ]);
    }

    protected function activityChanges() {
        if($this->wasChanged()) {
            return [
                'before' => Arr::except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
                'after' => Arr::except($this->getChanges(), 'updated_at'),
            ];
        }
    }

    protected function activityOwner() {
        if(auth()->check()) {
            return auth()->user();
        }

        return ($this->project ?? $this)->owner;
    }

}
