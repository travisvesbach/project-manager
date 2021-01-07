<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use App\Models\Task;
use App\Models\User;

class RecordActivityTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test **/
    public function creating_a_project() {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);
        tap($project->activity->last(), function($activity) {
            $this->assertEquals('created_project', $activity->description);

            $this->assertNull($activity->changes);
        });
    }

    /** @test **/
    public function updating_a_project() {
        $project = ProjectFactory::create();
        $originalName = $project->name;

        $project->update(['name' => 'changed']);

        $this->assertCount(2, $project->activity);
        tap($project->activity->last(), function($activity) use ($originalName) {
            $this->assertEquals('updated_project', $activity->description);

            $expected = [
                'before' => ['name' => $originalName],
                'after' => ['name' => 'changed'],
            ];

            $this->assertEquals($expected, $activity->changes);
        });
    }

    /** @test **/
    public function creating_a_task() {
        $project = ProjectFactory::create();

        $project->addTask(['name' => 'Some task']);

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function($activity) {
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('Some task', $activity->subject->name);
        });
    }

    /** @test **/
    public function updating_a_task() {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'name' => 'changed'
            ]);

        $this->assertCount(3, $project->activity);

        tap($project->activity->last(), function($activity) {
            $this->assertEquals('updated_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
            $this->assertEquals('changed', $activity->subject->name);
        });
    }

    /** @test **/
    public function completing_a_task() {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'name' => 'Some task',
                'completed' => true
            ]);

        $this->assertCount(3, $project->activity);

        tap($project->activity->last(), function($activity) {
            $this->assertEquals('updated_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test **/
    public function incompleting_a_task() {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'name' => 'Some task',
                'completed' => true
            ]);

        $this->assertCount(3, $project->activity);

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'name' => 'Some task',
                'completed' => false
            ]);

        $project->refresh();

        $this->assertCount(4, $project->activity);

        tap($project->activity->last(), function($activity) {
            $this->assertEquals('updated_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test **/
    public function deleting_a_task() {
        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activity);
        $this->assertEquals('deleted_task', $project->activity->last()->description);
    }

    /** @test **/
    public function inviting_a_user() {
        $project = ProjectFactory::create();

        $project->invite(User::factory()->create());
        $this->assertCount(2, $project->activity);
    }

    /** @test **/
    public function uninviting_a_user() {
        $project = ProjectFactory::create();

        $project->invite($user = User::factory()->create());
        $this->assertCount(2, $project->activity);

        $project->uninvite($user);
        $this->assertCount(3, $project->fresh()->activity);
    }
}
