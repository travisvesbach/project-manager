<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\Project;
use App\Models\Section;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_task_belongs_to_a_project() {
        $task = Task::factory()->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }
    /** @test **/
    public function a_task_belongs_to_a_section() {
        $task = Task::factory()->create();

        $this->assertInstanceOf(Section::class, $task->section);
    }

    /** @test **/
    public function it_has_a_path() {
        $task = Task::factory()->create();

        $this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());
    }

    /** @test **/
    public function it_can_be_completed() {
        $task = Task::factory()->create();

        $this->assertFalse($task->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }

    /** @test **/
    public function it_can_be_marked_as_incomplete() {
        $task = Task::factory()->create(['completed' => true]);

        $this->assertTrue($task->completed);

        $task->incomplete();

        $this->assertFalse($task->fresh()->completed);
    }

    /** @test **/
    public function has_users() {
        $this->signIn();
        $task = Task::factory()->create();

        $this->assertCount(1, $task->users);
    }

    /** @test **/
    public function it_can_invite_a_user() {
        $project = ProjectFactory::withTasks(1)->create();
        $task = $project->tasks->first();

        $task->invite($user = User::factory()->create());
        $this->assertTrue($task->users->contains($user));
    }

    /** @test **/
    public function it_can_uninvite_a_user() {
        $project = ProjectFactory::withTasks(1)->create();
        $task = $project->tasks->first();

        $task->invite($user = User::factory()->create());
        $this->assertTrue($task->users->contains($user));

        $task->uninvite($user);
        $this->assertFalse($task->fresh()->users->contains($user));
    }
}
