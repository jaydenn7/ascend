<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        return inertia('Admin/Index', [
            'overdue_users' => fn () => UserResource::collection(
                User::query()
                    ->with("borrows.bookCopy")
                    ->whereHas("overdue_borrows")
                    ->withCount(["current_borrows", "overdue_borrows"])
                    ->get()
            ),
        ]);
    }
}
