<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\Request as BookRequest;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View
    {
        $stats = [
            'total_books' => Book::count(),
            'total_users' => User::where('is_admin', false)->count(),
            'active_borrowings' => Borrowing::whereNull('returned_at')->count(),
            'overdue_borrowings' => Borrowing::where('status', 'overdue')->count(),
            'pending_requests' => BookRequest::where('status', 'pending')->count(),
            'total_fines' => Fine::sum('amount'),
            'unpaid_fines' => Fine::where('paid', false)->sum('amount'),
            'books_out' => Book::sum('copies_total') - Book::sum('copies_available'),
        ];

        $recentBorrowings = Borrowing::with(['user', 'book'])
            ->latest()
            ->take(5)
            ->get();

        $pendingRequests = BookRequest::with(['user', 'book'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBorrowings', 'pendingRequests'));
    }
}
