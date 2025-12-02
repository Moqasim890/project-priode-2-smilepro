<?php

namespace App\Http\Controllers;

use App\Models\AdminUserModel as User;

class AdminController extends Controller
{
    //

    function index()
    {
        return view('admin.dashboard');
    }

    function users()
    {
        $perPage = (int) request()->query('per_page', 10);
        $page = max(1, (int) request()->query('page', 1));
        $offset = ($page - 1) * $perPage;

        $users = //User::GetAllUsers($perPage, $offset);

        // Total count for pagination and display
        $total = 0 //User::SP_CountAllUsers();

        // Build simple pagination data
        $pagination = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => (int) ceil($total / $perPage),
        ];

        return view('admin.users.index', compact('users', 'pagination'));
    }
}
