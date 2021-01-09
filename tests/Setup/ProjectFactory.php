<?php

namespace Tests\Setup;

use App\Models\Project;
use App\Models\Section;
use App\Models\Task;
use App\Models\User;

class ProjectFactory
{
    protected $sectionsCount = 0;
    protected $tasksCount = 0;
    protected $user;

    public function withSections($count) {
        $this->sectionsCount = $count;
        return $this;
    }

    public function withTasks($count) {
        $this->tasksCount = $count;
        return $this;
    }

    public function ownedBy($user) {
        $this->user = $user;
        return $this;
    }

    public function create() {
        $project = Project::factory()->create([
            'owner_id' => $this->user ?? User::factory()
        ]);

        Section::factory()->count($this->sectionsCount)->create([
            'project_id' => $project->id
        ]);

        Task::factory()->count($this->tasksCount)->create([
            'project_id' => $project->id
        ]);

        return $project;
    }
}
