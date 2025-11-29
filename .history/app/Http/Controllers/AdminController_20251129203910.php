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
        $users = User::GetAllUsers();
        return view('admin.users.index');
    }
}
