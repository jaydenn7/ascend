<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookCopyResource;
use App\Models\BookCopy;
use Illuminate\Http\Request;

class BookCopiesController extends Controller
{
    public function index(Request $request)
    {
        return inertia('BookCopies/Index', [
            'book_copies' => fn () => BookCopyResource::collection(
                BookCopy::query()
                    ->with("book.authors")
                    ->whereAvailable()
                    ->applySearchFiltersFrom($request)
                    ->get()
            )
        ]);
    }
}
