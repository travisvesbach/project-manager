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
        $project = ProjectFactory::create();

        $project->invite($user = User::factory()->create());
        $this->assertCount(1, $user->notifications);
        $this->assertEquals($project->id, $user->notifications->first()->data['project_id']);
    }
}
