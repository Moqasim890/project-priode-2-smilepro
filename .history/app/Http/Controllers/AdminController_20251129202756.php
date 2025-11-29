<?php

namespace App\Http\Controllers;


class AdminController extends Controller
{
    function users()
    {
        return view('admin.users.index');
    }
}
