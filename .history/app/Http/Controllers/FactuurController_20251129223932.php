<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\models\FactuurModel AS factuur;

class FactuurController extends Controller
{
    function index(){
        $facturen = factuur::GetAllFacturen();
        
        // Haal totalen op voor elke status
        $statuses = ['Verzonden', 'Onbetaald', 'Niet-Verzonden', 'Betaald'];
        $totalen = [];
        
        foreach ($statuses as $status) {
            $result = factuur::GetTotaalFactuurBedrag($status);
            $totalen[$status] = $result;
        }
        
        return view('medewerker.facturen.index', compact('facturen', 'totalen'));
    }
}