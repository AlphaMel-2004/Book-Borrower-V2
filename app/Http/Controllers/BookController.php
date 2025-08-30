<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BookController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Book::class, 'book');
    }

    public function index(): View
    {
        $books = Book::withCount(['borrowings' => function ($query) {
            $query->whereNull('returned_at');
        }])
        ->latest()
        ->paginate(12);

        return view('books.index', compact('books'));
    }

    public function create(): View
    {
        return view('books.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn|max:13',
            'description' => 'nullable|string',
            'copies_total' => 'required|integer|min:1',
            'published_at' => 'nullable|date',
        ]);

        $validated['copies_available'] = $validated['copies_total'];
        
        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Book created successfully.');
    }

    public function show(Book $book): View
    {
        $book->load(['borrowings' => function ($query) {
            $query->whereNull('returned_at');
        }]);

        return view('books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id . '|max:13',
            'description' => 'nullable|string',
            'copies_total' => 'required|integer|min:' . ($book->copies_total - $book->copies_available),
            'published_at' => 'nullable|date',
        ]);

        // Calculate new copies_available based on the difference
        $difference = $validated['copies_total'] - $book->copies_total;
        $validated['copies_available'] = $book->copies_available + $difference;

        $book->update($validated);

        return redirect()->route('books.show', $book)
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->borrowings()->whereNull('returned_at')->exists()) {
            return back()->with('error', 'Cannot delete book with active borrowings.');
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully.');
    }
}
