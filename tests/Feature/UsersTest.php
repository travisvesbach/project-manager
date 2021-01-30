<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UsersTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test **/
    public function first_user_is_an_admin() {
        $user = User::factory()->create();
        $this->assertEquals('admin', $user->role);
    }

    /** @test **/
    public function users_added_after_the_first_are_not_admins() {
        $this->assertEquals('admin', User::factory()->create()->role);
        $this->assertNotEquals('admin', User::factory()->create()->role);
        $this->assertNotEquals('admin', User::factory()->create()->role);
    }
}
