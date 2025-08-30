<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BorrowingFactory extends Factory
{
    public function definition(): array
    {
        $borrowed_at = fake()->dateTimeBetween('-2 months', 'now');
        $status = fake()->randomElement(['borrowed', 'returned', 'overdue', 'cancelled']);
        
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'borrowed_at' => $borrowed_at,
            'due_at' => fake()->dateTimeBetween($borrowed_at, '+2 weeks'),
            'returned_at' => $status === 'returned' ? fake()->dateTimeBetween($borrowed_at, 'now') : null,
            'status' => $status,
        ];
    }

    public function returned(): static
    {
        return $this->state(function (array $attributes) {
            $borrowed_at = fake()->dateTimeBetween('-2 months', '-1 day');
            return [
                'status' => 'returned',
                'borrowed_at' => $borrowed_at,
                'returned_at' => fake()->dateTimeBetween($borrowed_at, 'now'),
            ];
        });
    }

    public function overdue(): static
    {
        return $this->state(function (array $attributes) {
            $borrowed_at = fake()->dateTimeBetween('-2 months', '-15 days');
            return [
                'status' => 'overdue',
                'borrowed_at' => $borrowed_at,
                'due_at' => fake()->dateTimeBetween($borrowed_at, '-1 day'),
                'returned_at' => null,
            ];
        });
    }
}
