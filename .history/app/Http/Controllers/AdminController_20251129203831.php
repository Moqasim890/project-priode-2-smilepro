<?php

namespace App\Http\Controllers;

USE

class AdminController extends Controller
{
    //

    function index()
    {
        return view('admin.dashboard');
    }

    function users()
    {

        return view('admin.users.index');
    }
}
