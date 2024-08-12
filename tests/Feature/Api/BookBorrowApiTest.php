<?php

namespace Tests\Feature\Api;

use App\Enums\Status;
use App\Models\BookCopy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class BookBorrowApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function successful_borrowing_of_a_book_copy()
    {
        $bookCopy = BookCopy::factory()->create(['status' => Status::AVAILABLE]);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/borrow', [
            'barcode' => $bookCopy->barcode,
            'membership_number' => $user->membership_number,
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('borrows', [
            'book_copy_id' => $bookCopy->id,
            'user_id' => $user->id,
            'returned_at' => null,
        ]);

        $this->assertFalse($bookCopy->fresh()->status === Status::AVAILABLE->value);
    }

    /** @test */
    public function invalid_barcode_returns_error()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/borrow', [
            'barcode' => 'invalid-barcode',
            'membership_number' => $user->membership_number,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'The selected barcode is invalid.']);
    }

    /** @test */
    public function invalid_membership_number_returns_error()
    {
        $bookCopy = BookCopy::factory()->create(['status' => Status::AVAILABLE]);

        $response = $this->actingAs(User::factory()->create())->postJson('/api/borrow', [
            'barcode' => $bookCopy->barcode,
            'membership_number' => 'invalid-membership-number',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('membership_number');
    }

    /** @test */
    public function book_copy_unavailable_returns_error()
    {
        $bookCopy = BookCopy::factory()->create(["status" => Status::RESERVED]);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/borrow', [
            'barcode' => $bookCopy->barcode,
            'membership_number' => $user->membership_number,
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'This copy is unavailable']);
    }

    /** @test */
    public function successful_borrowing_with_due_date()
    {
        $bookCopy = BookCopy::factory()->create(['status' => Status::AVAILABLE]);
        $user = User::factory()->create();
        $dueDate = now()->addDays(7)->format('Y-m-d');

        $response = $this->actingAs($user)->postJson('/api/borrow', [
            'barcode' => $bookCopy->barcode,
            'membership_number' => $user->membership_number,
            'due_date' => $dueDate,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('borrows', [
            'book_copy_id' => $bookCopy->id,
            'user_id' => $user->id,
            'due_date' => $dueDate,
        ]);

        $this->assertTrue($bookCopy->fresh()->status === Status::BORROWED->value);
    }
}
