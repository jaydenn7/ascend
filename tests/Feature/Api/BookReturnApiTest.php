<?php

namespace Tests\Feature\Api;

use App\Enums\Status;
use App\Models\BookCopy;
use App\Models\Borrow;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class BookReturnApiTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function successful_book_return()
    {
        $bookCopy = BookCopy::factory()->create(["status" => Status::BORROWED]);
        $user = User::factory()->create();
        $borrow = Borrow::factory()->create([
            "book_copy_id" => $bookCopy->id,
            "user_id" => $user->id,
            "returned_at" => null,
        ]);

        $response = $this->postJson("/api/return", [
            "barcode" => $bookCopy->barcode,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing("borrows", [
            "id" => $borrow->id,
            "returned_at" => null,
        ]);

        $this->assertTrue($bookCopy->fresh()->status === Status::AVAILABLE->value);
    }

    /** @test */
    public function no_active_borrow_record_returns_error()
    {
        $bookCopy = BookCopy::factory()->create(["status" => Status::BORROWED]);

        $response = $this->postJson("/api/return", [
            "barcode" => $bookCopy->barcode,
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson(["message" => "No active borrow record found for this book copy"]);
    }

    /** @test */
    public function multiple_active_borrow_records_returns_error()
    {
        $bookCopy = BookCopy::factory()->create(["status" => Status::BORROWED]);

        Borrow::factory()->count(2)->create([
            "book_copy_id" => $bookCopy->id,
            "returned_at" => null,
        ]);

        $response = $this->postJson("/api/return", [
            "barcode" => $bookCopy->barcode,
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(["message" => "Multiple active borrow records found for this book copy"]);
    }
}
