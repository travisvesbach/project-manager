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
        $this->assertEquals('Administrator', $user->role);
    }

    /** @test **/
    public function users_added_after_the_first_are_not_admins() {
        $this->assertEquals('Administrator', User::factory()->create()->role);
        $this->assertNotEquals('Administrator', User::factory()->create()->role);
        $this->assertNotEquals('Administrator', User::factory()->create()->role);
    }

    /** @test **/
    public function guests_cannot_manage_users() {
        $this->get('/users')->assertRedirect('login');
        $this->get('/users/create')->assertRedirect('login');
        $this->post('users', [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ])
            ->assertRedirect('login');

        $this->assertCount(0, User::all());
    }

    /** @test **/
    public function non_admins_cannot_manage_users() {
        $admin_user = User::factory()->create();
        $this->signIn();

        $this->get('/users')->assertStatus(403);
        $this->get('/users/create')->assertStatus(403);
        $this->post('users', [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ])
            ->assertStatus(403);

        $this->assertCount(2, User::all());
    }

    /** @test **/
    public function an_admin_can_create_users() {
        $admin_user = User::factory()->create();

        $this->actingAs($admin_user)
            ->post('users', [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ])
            ->assertRedirect('users');

        $this->assertCount(2, User::all());
    }

    /** @test **/
    public function an_admin_can_delete_users() {
        $admin_user = User::factory()->create();
        $delete_user = User::factory()->create();
        $this->assertCount(2, User::all());

        $this->actingAs($admin_user)
            ->delete('users/' . $delete_user->id)
            ->assertRedirect('users');

        $this->assertCount(1, User::all());
    }
}
