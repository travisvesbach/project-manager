<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;


class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_user_has_projects() {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test **/
    public function a_user_has_all_projects() {
        $john = $this->signIn();

        ProjectFactory::ownedBy($john)->create();

        $this->assertCount(1, $john->allProjects());

        $jane = User::factory()->create();
        $bill = User::factory()->create();

        $project = tap(ProjectFactory::ownedBy($jane)->create())->invite($bill);

        $this->assertCount(1, $john->allProjects());

        $project->invite($john);

        $this->assertCount(2, $john->allProjects());
    }
}
