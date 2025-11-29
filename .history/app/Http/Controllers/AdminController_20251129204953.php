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
        // Default pagination-like parameters; adjust if needed
        $users = User::GetAllUsers(100, 0);
        return view('admin.users.index', compact('users'));
    }
}
