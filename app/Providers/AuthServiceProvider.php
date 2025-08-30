<?php

namespace App\Providers;

use App\Models\Book;
use App\Policies\BookPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Book::class => BookPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function ($user) {
            if ($user->isAdmin) {
                return true;
            }
        });

        Gate::define('borrow-books', function ($user) {
            $unpaidFines = $user->fines()
                ->whereHas('borrowing', function ($query) {
                    $query->where('returned_at', null);
                })
                ->where('paid', false)
                ->sum('amount');
            return $unpaidFines <= config('bookborrower.max_unpaid_fines');
        });
    }
}
