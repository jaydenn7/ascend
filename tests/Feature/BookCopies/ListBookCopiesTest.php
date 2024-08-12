<?php

namespace Tests\Feature\BookCopies;

use App\Models\BookCopy;
use App\Models\Library;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ListBookCopiesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_list_book_copies_associated_with_users_library()
    {
        $library = Library::factory()->create();
        BookCopy::factory()->count(2)->create(['library_id' => $library->id]);
        $user = User::factory()->hasAttached($library)->create();

        $response = $this->actingAs($user)->get(route('book-copies.index'));

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('BookCopies/Index')
            ->count('book_copies.data', 2));
    }

    /** @test */
    public function it_eager_loads_books_and_authors_for_book_copies()
    {
        $library = Library::factory()->create();
        BookCopy::factory()->count(2)->create(['library_id' => $library->id]);
        $user = User::factory()->hasAttached($library)->create();

        DB::enableQueryLog();
        $this->actingAs($user)->get(route('book-copies.index'));
        DB::disableQueryLog();

        static::assertNotEquals(
            count(DB::getQueryLog()),
            BookCopy::query()->whereAvailable()->count()
        );
    }
}
