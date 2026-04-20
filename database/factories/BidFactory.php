<?php

namespace Database\Factories;

use App\Models\Bid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bid>
 */
class BidFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'amount' => $this->faker->numberBetween(20, 300),
            'delivery_days' => $this->faker->numberBetween(1, 14),
            'cover_letter' => $this->faker->paragraph(3),

            'project_id' => 1, 
            'freelancer_id' => 1, 
        ];
    }
}
