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
        $this->post('users', $attributes = [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'role' => 'User',
            ])
            ->assertRedirect('login');
        $this->assertCount(0, User::all());

        $other_user = User::factory()->create();
        $this->patch('users/' . $other_user->id, $attributes)->assertRedirect('login');
        $this->delete('users/' . $other_user->id)->assertRedirect('login');
        $this->assertCount(1, User::all());
    }

    /** @test **/
    public function non_admins_cannot_manage_users() {
        $admin_user = User::factory()->create();
        $this->signIn();

        $this->get('/users')->assertStatus(403);
        $this->get('/users/create')->assertStatus(403);
        $this->post('users', $attributes = [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'role' => 'User',
            ])
            ->assertStatus(403);
        $this->assertCount(2, User::all());

        $other_user = User::factory()->create();
        $this->patch('users/' . $other_user->id, $attributes)->assertStatus(403);
        $this->delete('users/' . $other_user->id)->assertStatus(403);
        $this->assertCount(3, User::all());
    }

    /** @test **/
    public function an_admin_can_create_users() {
        $admin_user = User::factory()->create();

        $this->actingAs($admin_user)
            ->post('users', [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'role' => 'User',
            ])
            ->assertRedirect('users');

        $this->assertCount(2, User::all());
    }

    /** @test **/
    public function an_admin_can_update_users() {
        $admin_user = User::factory()->create();

        $user_to_update = User::factory()->create();

        $this->actingAs($admin_user)
            ->patch('users/' . $user_to_update->id, [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'role' => 'User'
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
