<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\Request;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Create regular users
        User::factory(5)->create();

        // Create sample books
        Book::factory(20)->create();

        // Create some borrowings
        Borrowing::factory(10)->create();

        // Create some requests
        Request::factory(5)->create();

        // Create some fines for overdue books
        Fine::factory(3)->create();
    }
}
