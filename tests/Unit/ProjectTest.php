<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Project;
use App\Models\Section;
use App\Models\User;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_has_a_path() {

        $project = Project::factory()->create();

        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    /** @test **/
    public function it_belongs_to_an_owner() {
        $project = Project::factory()->create();

        $this->assertInstanceOf('App\Models\User', $project->owner);
    }

    /** @test **/
    public function it_can_invite_a_user() {
        $project = Project::factory()->create();

        $project->invite($user = User::factory()->create());

        $this->assertTrue($project->users->contains($user));
    }

    /** @test **/
    public function it_can_uninvite_a_user() {
        $project = Project::factory()->create();

        $project->invite($user = User::factory()->create());

        $this->assertTrue($project->users->contains($user));

        $project->uninvite($user);

        $this->assertFalse($project->fresh()->users->contains($user));
    }
}
