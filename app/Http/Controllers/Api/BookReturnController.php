<?php

namespace App\Http\Controllers\Api;

use App\Models\BookCopy;
use App\Models\Borrow;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\MultipleRecordsFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookReturnController
{
    public function handle(Request $request)
    {
        $request->validate([
            "barcode" => ["required", "string", "exists:book_copies,barcode"],
        ]);

        /** @var BookCopy $bookCopy */
        $bookCopy = BookCopy::query()
            ->where("barcode", $request->input("barcode"))
            ->sole();

        try {
            /** @var Borrow $borrow */
            $borrow = Borrow::query()
                ->whereBelongsTo($bookCopy)
                ->whereNull("returned_at")
                ->sole();
        } catch (ModelNotFoundException $e) {
            report($e);
            return response()->json(["message" => "No active borrow record found for this book copy"], 404);
        } catch (MultipleRecordsFoundException $e) {
            report($e);
            return response()->json(["message" => "Multiple active borrow records found for this book copy"], 400);
        }

        try {
            DB::transaction(function () use ($borrow, $bookCopy) {
                $borrow->returned_at = Carbon::now();
                $borrow->save();

                $bookCopy->markAsAvailable();
            });
        } catch (Exception $e) {
            report($e);
            return response()->json(["message" => "An error occurred. Please try again later"], 500);
        }

        return response()->json(["message" => "Success"]);
    }
}