<?php

use App\Http\Controllers\Api\BookBorrowController;
use App\Http\Controllers\Api\BookReturnController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post("/borrow", [BookBorrowController::class, "handle"]);
Route::post("/return", [BookReturnController::class, "handle"]);