<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FactuurModel as factuur;
use App\Models\PatientModel as patient;


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
    function index()
    {
        // Haal alle facturen op via stored procedure
        $facturen = factuur::GetAllFacturen();

        // Haal totaalbedragen per status op (Verzonden, Onbetaald, Niet-Verzonden, Betaald)
        // Gebruikt voor statistiek cards bovenaan de pagina
        $totalen = factuur::GetTotaalFactuurBedrag();

        // Stuur data naar view voor weergave
        return view('medewerker.facturen.index', compact('facturen', 'totalen'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patienten = patient::GetAllpatienten(); //geeft je naam,id en patientnummer

        return view('medewerker.facturen.create', compact('patienten'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'patientid' => 'required|integer'
            ,
            'behandelingid' => 'required|array|min:1'
            ,
            'nummer' => 'required|string'
            ,
            'datum' => 'required|date'
            ,
            'bedrag' => 'required|numeric'
            ,
            'status' => 'required|string'
        ]);

        $res = factuur::create($data);

        if ($res->affected < 0) {
            return back()->with('error', 'Er is iets misgegaan bij het aanmaken van de factuur');
        } else {
            return redirect()->route('medewerker.factuur.index')->with('success', 'Factuur succesvol aangemaakt!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }

    public function Getbehandelingen($patientId)
    {
        $behandelingen = patient::GetAllBehandelingen($patientId);
        // dd($behandelingen);
        return $behandelingen;
    }
}
