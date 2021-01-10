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
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->sections->first()->path(), $attributes = [
                'name' => 'changed name'
            ]);

        $this->assertDatabaseHas('sections', $attributes);
    }

    /** @test **/
    public function a_section_requires_a_name() {
        $project = ProjectFactory::create();

        $attributes = Section::factory()->raw(['name' => '']);

        $this->actingAs($project->owner)
            ->post($project->path() . '/sections', $attributes)
            ->assertSessionHasErrors('name');
    }

    /** @test **/
    public function a_section_is_assigned_the_next_available_weight() {
        $project = ProjectFactory::create();

        $this->assertEquals(1, $project->sections->last()->weight);

        $project->addSection(['name' => 'Some Section']);

        $this->assertEquals(2, $project->fresh()->sections->last()->weight);

    }

    /** @test **/
    public function section_weights_are_updated_when_a_section_is_deleted() {
        $project = ProjectFactory::withSections(2)->create();

        $this->assertCount(3, $project->sections);
        $this->assertEquals(3, $project->sections->last()->weight);

        $this->actingAs($project->owner)
            ->delete($project->sections->where('weight', 2)->first()->path())
            ->assertRedirect($project->path());

        $project->refresh();

        $this->assertCount(2, $project->sections);
        $this->assertEquals(2, $project->sections->last()->weight);
    }
}
