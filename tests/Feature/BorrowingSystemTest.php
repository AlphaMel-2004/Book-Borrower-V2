<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_borrow_available_book(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'copies_total' => 2,
            'copies_available' => 2
        ]);

        $response = $this->actingAs($user)
            ->post(route('books.borrow', $book));

        $response->assertRedirect();
        $this->assertDatabaseHas('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'borrowed'
        ]);
        $this->assertEquals(1, $book->fresh()->copies_available);
    }

    public function test_user_cannot_borrow_unavailable_book(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'copies_total' => 1,
            'copies_available' => 0
        ]);

        $response = $this->actingAs($user)
            ->post(route('books.borrow', $book));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id
        ]);
    }

    public function test_user_can_return_borrowed_book(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'copies_total' => 1,
            'copies_available' => 0
        ]);
        $borrowing = Borrowing::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'borrowed',
            'returned_at' => null
        ]);

        $response = $this->actingAs($user)
            ->post(route('borrowings.return', $borrowing));

        $response->assertRedirect();
        $this->assertNotNull($borrowing->fresh()->returned_at);
        $this->assertEquals(1, $book->fresh()->copies_available);
    }

    public function test_overdue_return_creates_fine(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['copies_available' => 0]);
        $borrowing = Borrowing::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'borrowed',
            'due_at' => Carbon::now()->subDays(5),
            'returned_at' => null
        ]);

        $response = $this->actingAs($user)
            ->post(route('borrowings.return', $borrowing));

        $response->assertRedirect();
        $this->assertDatabaseHas('fines', [
            'borrowing_id' => $borrowing->id,
            'paid' => false
        ]);
    }

    public function test_user_can_renew_non_overdue_borrowing(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['copies_available' => 0]);
        $borrowing = Borrowing::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'borrowed',
            'due_at' => Carbon::now()->addDays(2),
            'returned_at' => null
        ]);

        $originalDueDate = $borrowing->due_at;

        $response = $this->actingAs($user)
            ->post(route('borrowings.renew', $borrowing));

        $response->assertRedirect();
        $this->assertTrue($borrowing->fresh()->due_at->gt($originalDueDate));
    }

    public function test_user_cannot_renew_overdue_borrowing(): void
    {
        $user = User::factory()->create();
        $borrowing = Borrowing::factory()->create([
            'user_id' => $user->id,
            'status' => 'borrowed',
            'due_at' => Carbon::now()->subDays(1),
            'returned_at' => null
        ]);

        $response = $this->actingAs($user)
            ->post(route('borrowings.renew', $borrowing));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(
            $borrowing->due_at->toDateTimeString(),
            $borrowing->fresh()->due_at->toDateTimeString()
        );
    }

    public function test_user_cannot_exceed_maximum_concurrent_borrows(): void
    {
        $user = User::factory()->create();
        
        // Create max allowed active borrowings
        Borrowing::factory()->count(config('bookborrower.max_concurrent_borrows'))
            ->create([
                'user_id' => $user->id,
                'status' => 'borrowed',
                'returned_at' => null
            ]);

        $newBook = Book::factory()->create(['copies_available' => 1]);

        $response = $this->actingAs($user)
            ->post(route('books.borrow', $newBook));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(1, $newBook->fresh()->copies_available);
    }
}
