<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;
use App\Models\Section;
use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;

class SectionsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test **/
    public function guests_cannot_add_sections_to_projects() {
        $project = ProjectFactory::create();

        $this->post($project->path() . '/sections')->assertRedirect('login');
    }

    /** @test **/
    public function a_project_can_have_sections() {
        $this->signIn();

        $project = ProjectFactory::create();

        $attributes = ['name' => 'Test section'];

        $this->actingAs($project->owner)
            ->post($project->path() . '/sections', $attributes = ['name' => 'Test section']);

        $this->get($project->path())
            ->assertSee($attributes['name']);
    }

    /** @test **/
    public function a_section_is_created_when_a_project_is_created() {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->sections);

        $this->assertDatabaseHas('sections', [
            'name' => 'Default Section',
            'project_id' => $project->id,
            'weight' => 1,
        ]);
    }

    /** @test **/
    public function a_section_can_be_updated() {
        $project = ProjectFactory::withSections(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->sections->first()->path(), $attributes = [
                'name' => 'changed name'
            ]);

        $this->assertDatabaseHas('sections', $attributes);
    }

    /** @test **/
    public function a_section_requires_a_name() {
        $project = ProjectFactory::withSections(1)->create();

        $attributes = Section::factory()->raw(['name' => '']);

        $this->actingAs($project->owner)
            ->post($project->path() . '/sections', $attributes)
            ->assertSessionHasErrors('name');
    }
}
