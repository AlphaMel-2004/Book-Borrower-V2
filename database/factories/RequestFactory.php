<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequestFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'approved', 'rejected']);
        $processed = in_array($status, ['approved', 'rejected']);
        
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'status' => $status,
            'processed_by' => $processed ? User::factory()->admin() : null,
            'processed_at' => $processed ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'note' => $processed ? fake()->sentence() : null,
        ];
    }

    public function pending(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'processed_by' => null,
                'processed_at' => null,
                'note' => null,
            ];
        });
    }
}
