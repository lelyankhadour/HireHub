<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(4),
'budget_amount' => $this->faker->numberBetween(50, 500),
            'deadline' => $this->faker->dateTimeBetween('+3 days', '+30 days'),

            'status' => 'open', 

            // 'client_id' => 1, 
            'client_id' => \App\Models\User::where('role', 'client')->inRandomOrder()->first()->id,

        ];
    }
}
