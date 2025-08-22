<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' Department',
            'head_name' => fake()->name(),
            'head_email' => fake()->unique()->safeEmail(),
            'head_phone' => fake()->unique()->phoneNumber(),
            'fest_type' => fake()->randomElement(['dept_fest', 'association', 'clg_fest']),
            'is_active' => fake()->boolean(),
        ];
    }
}
