<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;
use App\Models\Section;
use App\Models\Task;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;

class NotificationsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test **/
    public function inviting_a_user_to_a_project() {
        $this->signIn();

        $project = ProjectFactory::create();

        $project->invite($user = User::factory()->create());
        $this->assertCount(1, $user->notifications);
        $this->assertEquals($project->id, $user->notifications->first()->data['project_id']);
    }

    /** @test **/
    public function making_another_user_the_project_owner() {
        $this->signIn();

        $project = ProjectFactory::create();

        $project->invite($user = User::factory()->create());
        $this->assertCount(1, $user->notifications);

        $project->changeOwner($user);
        $this->assertCount(2, $user->fresh()->notifications);
    }

    /** @test **/
    public function can_be_marked_as_read() {
        $this->signIn();

        $project = ProjectFactory::create();

        $project->invite($user = User::factory()->create());
        $notification = $user->notifications->first();
        $this->assertNull($notification->read_at);

        $this->signIn($user);

        $this->patch('/notifications/' . $notification->id, ['read' => true]);

        $this->assertNotNull($notification->fresh()->read_at);
    }
}
