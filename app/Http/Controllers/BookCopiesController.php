<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCopyResource;
use App\Models\BookCopy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookCopiesController extends Controller
{
    public function index(Request $request)
    {
        return inertia('BookCopies/Index', [
            'book_copies' => fn () => BookCopyResource::collection(
                BookCopy::query()
                    ->with("book.authors")
                    ->whereAvailable()
                    ->whereAccessibleTo(Auth::user())
                    ->applySearchFiltersFrom($request)
                    ->get()
            ),
            "requested_copies" => fn () => BookCopyResource::collection(
                BookCopy::query()
                    ->with("book.authors")
                    ->whereHas("borrowRequests", fn (Builder $query) => $query->whereBelongsTo(Auth::user()))
                    ->get()
            ),
            "borrowed_copies" => fn () => BookCopyResource::collection(
                BookCopy::query()
                    ->with("book.authors")
                    ->whereHas("borrows", fn (Builder $query) => $query->whereBelongsTo(Auth::user()))
                    ->get()
            ),
        ]);
    }
}
