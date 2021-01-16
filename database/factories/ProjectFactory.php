<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class ProjectFactory extends Factory
{
    use WithFaker;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(4),
            'description' => $this->faker->sentence(4),
            'owner_id' => User::factory()->create(),
            'layout' => 'list',
        ];
    }
}
