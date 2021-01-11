<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;
use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;

class TasksTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test **/
    public function guests_cannot_add_tasks_to_projects() {
        $project = ProjectFactory::create();

        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    /** @test **/
    public function only_the_owner_of_a_project_can_add_tasks() {
        $this->signIn();

        $project = ProjectFactory::create();


        $this->post($project->path() . '/tasks', ['name' => 'Test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['name' => "Task test"]);
    }

    /** @test **/
    public function a_project_can_have_tasks() {
        $this->signIn();

        $project = ProjectFactory::create();

        $attributes = ['name' => 'Test task'];

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['name']);
    }

    /** @test **/
    public function a_task_can_be_updated() {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), $attributes = [
                'name' => 'changed name'
            ]);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test **/
    public function a_task_can_be_completed() {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), $attributes = [
                'name' => 'changed name',
                'completed' => true
            ]);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test **/
    public function a_task_can_be_marked_as_incomplete() {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), $attributes = [
                'name' => 'changed name',
                'completed' => true
            ]);

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), $attributes = [
                'name' => 'changed name',
                'completed' => false
            ]);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test **/
    public function only_the_owner_of_a_project_may_update_a_task() {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), ['name' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['name' => 'changed']);
    }

    /** @test **/
    public function a_task_requires_a_name() {
        $project = ProjectFactory::withTasks(1)->create();

        $attributes = Task::factory()->raw(['name' => '']);

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('name');
    }

    /** @test **/
    public function a_section_can_have_tasks() {
        $this->signIn();

        $project = ProjectFactory::create();

        $attributes = ['name' => 'Test task', 'section_id' => $project->sections[0]->id];

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', $attributes);

        $this->assertCount(1, $project->fresh()->sections[0]->tasks);
    }

    /** @test **/
    public function a_task_is_assigned_to_the_first_section_in_the_project_if_a_section_is_not_specified() {
        $project = ProjectFactory::create();

        $project->addTask(['name' => 'some task']);

        $this->assertNotNull($project->tasks[0]->section);
        $this->assertEquals(1, $project->tasks[0]->section->weight);
    }

    /** @test **/
    public function a_user_can_delete_a_task() {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::withTasks(1)->create();

        $task = $project->tasks->first();

        $this->actingAs($project->owner)
            ->delete($task->path())
            ->assertRedirect($project->path());

        $this->assertDatabaseMissing('tasks', $task->only('id'));
    }

    /** @test **/
    public function a_task_is_assigned_the_next_available_weight() {
        $project = ProjectFactory::withTasks(1)->create();

        $this->assertEquals(1, $project->tasks->last()->weight);

        $project->addTask(['name' => 'Some Task']);

        $this->assertEquals(2, $project->fresh()->tasks->last()->weight);
    }

    /** @test **/
    public function task_weights_are_updated_in_section_when_a_task_is_deleted() {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::withTasks(3)->create();

        $this->assertCount(3, $project->tasks);
        $this->assertEquals(3, $project->tasks->last()->weight);

        $this->actingAs($project->owner)
            ->delete($project->tasks->where('weight', 2)->first()->path())
            ->assertRedirect($project->path());

        $project->refresh();

        $this->assertCount(2, $project->tasks);
        $this->assertEquals(2, $project->tasks->last()->weight);
    }
}
