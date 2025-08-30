<?php

namespace Database\Factories;

use App\Models\Borrowing;
use Illuminate\Database\Eloquent\Factories\Factory;

class FineFactory extends Factory
{
    public function definition(): array
    {
        $paid = fake()->boolean(30);
        
        return [
            'borrowing_id' => Borrowing::factory()->overdue(),
            'amount' => fake()->randomFloat(2, 1, 50),
            'paid' => $paid,
            'paid_at' => $paid ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }

    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'paid' => true,
                'paid_at' => fake()->dateTimeBetween('-1 month', 'now'),
            ];
        });
    }
}
