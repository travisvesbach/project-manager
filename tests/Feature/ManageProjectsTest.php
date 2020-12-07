<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test **/
    public function guests_cannot_manage_projects() {
        $project = Project::factory()->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    /** @test **/
    public function a_user_can_create_a_project() {
        // $this->withoutExceptionHandling();

        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'name' => $this->faker->sentence,
            'description' => $this->faker->sentence
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->get($project->path())
            ->assertSee($attributes['name'])
            ->assertSee($attributes['description']);
    }

    /** @test **/
    public function a_user_can_update_a_project() {
        $project = Project::factory()->create();

        $this->actingAs($project->owner)
            ->patch($project->path(), $attributes = ['description' => 'Changed'])
            ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test **/
    public function a_user_can_view_their_project() {
        $project = Project::factory()->create();

        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test **/
    public function a_user_can_delete_their_project() {
        $project = Project::factory()->create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description
        ]);
    }

    /** @test **/
    public function an_authenticated_user_cannot_view_the_projects_of_others() {
        $this->signIn();

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test **/
    public function an_authenticated_user_cannot_update_the_projects_of_others() {
        $this->signIn();

        $project = Project::factory()->create();

        $this->patch($project->path())->assertStatus(403);
    }

    /** @test **/
    public function an_authenticated_user_cannot_delete_the_projects_of_others() {
        $this->signIn();

        $project = Project::factory()->create();

        $this->delete($project->path())->assertStatus(403);
    }

    /** @test **/
    public function a_project_requires_a_name() {
        $this->signIn();

        $attributes = Project::factory()->raw(['name' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('name');
    }

    /** @test **/
    public function a_project_requires_a_description() {
        $this->signIn();

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
