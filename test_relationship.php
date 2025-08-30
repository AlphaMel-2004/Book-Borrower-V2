<?php

use App\Models\User;
use App\Models\Borrowing;

// Simple test to verify the relationship
$user = User::first();
if ($user) {
    echo "User found: " . $user->name . "\n";
    echo "Borrowings count: " . $user->borrowings()->count() . "\n";
    echo "Test successful - relationship works!\n";
} else {
    echo "No users found\n";
}
