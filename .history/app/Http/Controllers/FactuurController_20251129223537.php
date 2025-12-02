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
        
        // Haal totalen op met stored procedure
        $totalen = factuur::GetTotaalFactuurBedrag(
        
        $totalBedrag = $totalen->totaal_bedrag ?? 0;
        $betaaldBedrag = $betaald->totaal_bedrag ?? 0;
        $openstaandBedrag = $onbetaald->totaal_bedrag ?? 0;
        
        return view('medewerker.facturen.index', compact('facturen', 'totalBedrag', 'betaaldBedrag', 'openstaandBedrag'));
    }
}