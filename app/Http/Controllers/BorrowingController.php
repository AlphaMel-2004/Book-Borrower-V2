<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Fine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin) {
            // Admin sees all borrowings
            $borrowings = Borrowing::with(['user', 'book'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Regular user sees only their borrowings
            $borrowings = $user->borrowings()
                ->with('book')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('borrowings.index', compact('borrowings'));
    }

    public function borrow(Request $request, Book $book)
    {
        if ($book->copies_available <= 0) {
            return redirect()->back()->with('error', 'No copies available for borrowing.');
        }

        $maxBorrows = 3;
        $activeBorrows = Auth::user()->borrowings()->whereNull('returned_at')->count();
        if ($activeBorrows >= $maxBorrows) {
            return redirect()->back()->with('error', 'You have reached the maximum number of concurrent borrowings.');
        }

        try {
            DB::beginTransaction();

            $book->decrement('copies_available');
            
            Borrowing::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'due_at' => now()->addDays(14), // 2 weeks borrowing period
                'returned_at' => null
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Book borrowed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to borrow book. Please try again.');
        }
    }

    public function returnBook(Request $request, Borrowing $borrowing)
    {
        try {
            DB::beginTransaction();

            $book = $borrowing->book;
            $book->increment('copies_available');

            $borrowing->returned_at = now();
            $borrowing->save();

            // Check if book is overdue and create fine if needed
            if ($borrowing->due_at->lt($borrowing->returned_at)) {
                $daysOverdue = $borrowing->due_at->diffInDays($borrowing->returned_at);
                $fineAmount = $daysOverdue * 1.00; // $1 per day overdue

                Fine::create([
                    'borrowing_id' => $borrowing->id,
                    'amount' => $fineAmount,
                    'paid' => false
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Book returned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to return book. Please try again.');
        }
    }

    public function renew(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->due_at->lt(now())) {
            return redirect()->back()->with('error', 'Cannot renew overdue borrowing.');
        }

        $borrowing->due_at = $borrowing->due_at->addDays(14); // Add another 2 weeks
        $borrowing->save();

        return redirect()->back()->with('success', 'Book renewed successfully.');
    }
}
