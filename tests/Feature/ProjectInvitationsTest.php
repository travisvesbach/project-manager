<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;

class ProjectInvitationsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test **/
    public function non_owners_cannot_invite_users() {
        $this->actingAs(User::factory()->create())
            ->post(ProjectFactory::create()->path() . '/invitations')
            ->assertStatus(403);
    }

    /** @test **/
    public function a_project_owner_can_invite_a_user() {
        $project = ProjectFactory::create();

        $userToInvite = User::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/invitations', [
                'email' => $userToInvite->email,
            ])
            ->assertRedirect($project->path());

        $this->assertTrue($project->users->contains($userToInvite));
    }

    /** @test **/
    public function the_email_address_must_be_associated_with_a_valid_user_account() {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/invitations', [
                'email' => 'no@example.com',
            ])
            ->assertSessionHasErrors([
                'email' => 'The user you are inviting must have an account.'
            ]);
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
}
