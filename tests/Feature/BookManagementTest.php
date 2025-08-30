<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_books_list(): void
    {
        $books = Book::factory(3)->create();

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        foreach ($books as $book) {
            $response->assertSee($book->title);
        }
    }

    public function test_public_can_view_book_details(): void
    {
        $book = Book::factory()->create();

        $response = $this->get(route('books.show', $book));

        $response->assertStatus(200)
            ->assertSee($book->title)
            ->assertSee($book->author);
    }

    public function test_admin_can_create_book(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->post(route('books.store'), [
                'title' => 'New Book',
                'author' => 'Test Author',
                'isbn' => '9780123456789',
                'description' => 'Test description',
                'copies_total' => 3,
                'published_at' => '2023-01-01'
            ]);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', [
            'title' => 'New Book',
            'author' => 'Test Author',
            'copies_available' => 3
        ]);
    }

    public function test_non_admin_cannot_create_book(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('books.store'), [
                'title' => 'New Book',
                'author' => 'Test Author'
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_book(): void
    {
        $admin = User::factory()->admin()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($admin)
            ->put(route('books.update', $book), [
                'title' => 'Updated Title',
                'author' => 'Updated Author',
                'copies_total' => $book->copies_total,
                'isbn' => $book->isbn
            ]);

        $response->assertRedirect(route('books.show', $book));
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Title',
            'author' => 'Updated Author'
        ]);
    }

    public function test_admin_cannot_reduce_copies_below_borrowed(): void
    {
        $admin = User::factory()->admin()->create();
        $book = Book::factory()->create([
            'copies_total' => 3,
            'copies_available' => 1 // meaning 2 copies are borrowed
        ]);

        $response = $this->actingAs($admin)
            ->put(route('books.update', $book), [
                'title' => $book->title,
                'author' => $book->author,
                'copies_total' => 1 // trying to set total below borrowed copies
            ]);

        $response->assertSessionHasErrors('copies_total');
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'copies_total' => 3
        ]);
    }

    public function test_admin_can_delete_book_with_no_active_borrowings(): void
    {
        $admin = User::factory()->admin()->create();
        $book = Book::factory()->create(['copies_available' => 1]);

        $response = $this->actingAs($admin)
            ->delete(route('books.destroy', $book));

        $response->assertRedirect(route('books.index'));
        $this->assertSoftDeleted($book);
    }
}
