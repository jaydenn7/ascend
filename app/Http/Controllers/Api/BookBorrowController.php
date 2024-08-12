<?php

namespace App\Http\Controllers\Api;

use App\Models\BookCopy;
use App\Models\Borrow;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookBorrowController
{
    public function handle(Request $request)
    {
        $request->validate([
            "barcode" => ["required", "string", "exists:book_copies,barcode"],
            "membership_number" => ["required", "string", "exists:users,membership_number"],
            "due_date" => ["date"],
        ]);

        /** @var BookCopy $bookCopy */
        $bookCopy = BookCopy::query()
            ->where("barcode", $request->input("barcode"))
            ->sole();

        /** @var User $user */
        $user = User::query()
            ->where("membership_number", $request->input("membership_number"))
            ->sole();

        if (!$bookCopy->isAvailable()) {
            return response()->json(["message" => "This copy is unavailable"], 400);
        }

        try {
            DB::transaction(function () use ($bookCopy, $user, $request) {
                $borrow = new Borrow;
                $borrow->book_copy_id = $bookCopy->getKey();
                $borrow->user_id = $user->getKey();
                $borrow->borrowed_from = Carbon::now();
                $borrow->due_date = $request->date("due_date") ?? Carbon::now()->addDay();
                $borrow->save();

                $bookCopy->markAsBorrowed();
            });
        } catch (Exception $e) {
            report($e);
            return response()->json(["message" => "An error occurred. Please try again later."], 500);
        }

        return response()->json(["message" => "Success"]);
    }
}