<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $fines = Auth::user()->isAdmin
            ? Fine::with(['borrowing.user', 'borrowing.book'])->latest()->paginate(15)
            : Auth::user()->fines()->with(['borrowing.book'])->latest()->paginate(15);

        return view('fines.index', compact('fines'));
    }

    public function pay(Fine $fine): RedirectResponse
    {
        if (!Auth::user()->isAdmin && $fine->borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        if ($fine->paid) {
            return back()->with('error', 'This fine has already been paid.');
        }

        $fine->update([
            'paid' => true,
            'paid_at' => now()
        ]);

        return back()->with('success', 'Fine marked as paid successfully.');
    }
}
