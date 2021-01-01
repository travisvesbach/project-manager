<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'description',
        'subject',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    protected $appends = [
        'timeSince',
        'createdAtFormatted',
        'descriptionFormatted',
    ];

    public function subject() {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function getTimeSinceAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->created_at)->format('M j, Y, h:i:s A');
    }

    public function getDescriptionFormattedAttribute() {
        $formatted = null;
        switch ($this->description) {
            case 'created_project':
                $formatted = 'created the project';
                break;

            case 'updated_project':
                if(count($this->getAttribute('changes')['after']) == 1) {
                    $formatted = 'updated the project\'s ' . key($this->getAttribute('changes')['after']);
                } else {
                    $formatted = 'updated the project';
                }
                break;

            case 'created_task':
                $formatted = 'created "' . $this->subject->name . '"';
                break;

            case 'updated_task':
                if(count($this->getAttribute('changes')['after']) == 1) {
                    $formatted = 'updated "' . $this->subject->name . '"\'s ' . key($this->getAttribute('changes')['after']);
                } else {
                    $formatted = 'updated "' . $this->subject->name . '"';
                }
                break;

            case 'deleted_task':
                $formatted = 'deleted "' . $this->subject->name . '"';
                break;

            case 'completed_task':
                $formatted = 'completed "' . $this->subject->name . '"';
                break;

            case 'incompleted_task':
                $formatted = 'marked "' . $this->subject->name . '" as incomplete';
                break;

            default:
                # code...
                break;
        }
        return $formatted;
    }
}
