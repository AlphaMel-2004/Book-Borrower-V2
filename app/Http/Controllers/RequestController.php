<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Request as BookRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only(['approve', 'reject']);
    }

    public function index(): View
    {
        $requests = Auth::user()->isAdmin
            ? BookRequest::with(['user', 'book', 'processedBy'])->latest()->paginate(15)
            : Auth::user()->requests()->with(['book', 'processedBy'])->latest()->paginate(15);

        return view('requests.index', compact('requests'));
    }

    public function store(Request $request, Book $book): RedirectResponse
    {
        // Check if user already has a pending request for this book
        $existingRequest = Auth::user()->requests()
            ->where('book_id', $book->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingRequest) {
            return back()->with('error', 'You already have a pending request for this book.');
        }

        // Check if user already has an active borrowing for this book
        $activeBorrowing = Auth::user()->borrowings()
            ->where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists();

        if ($activeBorrowing) {
            return back()->with('error', 'You are currently borrowing this book.');
        }

        Auth::user()->requests()->create([
            'book_id' => $book->id,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Request submitted successfully.');
    }

    public function approve(BookRequest $request): RedirectResponse
    {
        if ($request->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        // Check if book is available
        if ($request->book->copies_available <= 0) {
            return back()->with('error', 'No copies available for this book.');
        }

        DB::transaction(function () use ($request) {
            // Update request status
            $request->update([
                'status' => 'approved',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            // Create borrowing
            $request->user->borrowings()->create([
                'book_id' => $request->book_id,
                'borrowed_at' => now(),
                'due_at' => now()->addDays(config('bookborrower.borrow_duration')),
                'status' => 'borrowed'
            ]);

            // Decrement available copies
            $request->book->decrement('copies_available');
        });

        return back()->with('success', 'Request approved and book borrowed successfully.');
    }

    public function reject(Request $httpRequest, BookRequest $request): RedirectResponse
    {
        if ($request->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $request->update([
            'status' => 'rejected',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
            'note' => $httpRequest->input('note'),
        ]);

        return back()->with('success', 'Request rejected successfully.');
    }
}
