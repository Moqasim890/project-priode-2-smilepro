<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\models\FactuurModel AS factuur;

/**
 * FactuurController
 * 
 * Controller voor het beheren van facturen.
 * Toegankelijk voor medewerkers om factuuroverzichten te bekijken.
 */
class FactuurController extends Controller
{
    /**
     * Toon factuuroverzicht met statistieken.
     * 
     * Haalt alle facturen op en berekent totaalbedragen per status
     * voor weergave in statistiek cards en tabel.
     * 
     * @return \Illuminate\View\View Facturen index view met facturen lijst en totalen
     */
    function index(){
        // Haal alle facturen op via stored procedure
        $facturen = factuur::GetAllFacturen();
        
        // Haal totaalbedragen per status op (Verzonden, Onbetaald, Niet-Verzonden, Betaald)
        // Gebruikt voor statistiek cards bovenaan de pagina
        $totalen = factuur::GetTotaalFactuurBedrag();
        
        // Stuur data naar view voor weergave
        return view('medewerker.facturen.index', compact('facturen', 'totalen'));
    }
}