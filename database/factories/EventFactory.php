<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(). ' Event',
            'description' => $this->faker->sentence(6),
            'start_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'event_start_date' => $this->faker->dateTimeBetween('+2 months', '+3 months'),
            'event_end_date' => $this->faker->dateTimeBetween('+3 months', '+4 months'),
            'location' => $this->faker->city(),
            'fees' => $this->faker->numberBetween(100, 1000),
            'capacity' => $this->faker->numberBetween(50, 500),
        ];
    }
}
