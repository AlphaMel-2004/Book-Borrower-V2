<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        $copiesTotal = fake()->numberBetween(1, 5);
        
        return [
            'title' => fake()->sentence(4),
            'author' => fake()->name(),
            'isbn' => fake()->unique()->isbn13(),
            'description' => fake()->paragraph(),
            'copies_total' => $copiesTotal,
            'copies_available' => fake()->numberBetween(0, $copiesTotal),
            'published_at' => fake()->dateTimeBetween('-30 years', '-1 year'),
        ];
    }
}
