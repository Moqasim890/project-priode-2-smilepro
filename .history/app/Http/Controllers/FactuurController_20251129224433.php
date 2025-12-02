<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\models\FactuurModel AS factuur;

class FactuurController extends Controller
{
    function index(){
        $facturen = '';//factuur::GetAllFacturen();
        
        // Haal totalen op met stored procedure
        $totalen = factuur::GetTotaalFactuurBedrag();
        
        return view('medewerker.facturen.index', compact('facturen', 'totalen'));
    }
}