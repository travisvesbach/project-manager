<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;

class ProjectUsersTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test **/
    public function non_owners_cannot_invite_users() {
        $this->actingAs(User::factory()->create())
            ->post(ProjectFactory::create()->path() . '/users')
            ->assertStatus(403);
    }

    /** @test **/
    public function a_project_owner_can_invite_a_user() {
        $project = ProjectFactory::create();

        $userToInvite = User::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/users', [
                'id' => $userToInvite->id,
            ])
            ->assertRedirect($project->path());

        $this->assertTrue($project->users->contains($userToInvite));
    }

    /** @test **/
    public function the_id_must_be_associated_with_a_valid_user_account() {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/users', [
                'id' => 99,
            ])
            ->assertSessionHasErrors();
    }

    /** @test **/
    public function invited_user_may_update_project_details() {
        $project = ProjectFactory::create();

        $project->invite($user = User::factory()->create());

        $this->signIn($user);
        $this->patch($project->path(), $attributes = ['description' => 'changed'])
            ->assertRedirect($project->path());;

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test **/
    public function a_project_owner_can_uninvite_users() {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $project->invite($userToUninvite = User::factory()->create());

        $this->actingAs($project->owner)
            ->delete($project->path() . '/users/' . $userToUninvite->id)
            ->assertRedirect($project->path());

        $this->assertFalse($project->fresh()->users->contains($userToUninvite));
    }

    /** @test **/
    public function non_owners_cannot_uninvite_users() {
        $project = ProjectFactory::create();

        $project->invite($userToUninvite = User::factory()->create());

        $this->actingAs(User::factory()->create())
            ->delete($project->path() . '/users/' . $userToUninvite->id)
            ->assertStatus(403);

        $this->assertTrue($project->users->contains($userToUninvite));
    }
}
