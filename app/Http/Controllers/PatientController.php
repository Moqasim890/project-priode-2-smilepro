<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientModel as Patient;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Deze functie haalt de berichten op voor een patient op
     * door id te gebruiken in de stored procedure
     */
    public function getBerichtenById()
    {
        $userid    = Auth::Id();
        $patientid = Patient::SP_GetPatientIdByUserId($userid);
        $naam      = Auth::user()->name; //<- op een mooie dag zou dit de naam van de patient zeggen
        $berichten = Patient::SP_GetBerichtenById($patientid);
        // dd($patientid);

        return view('Patient.berichten.index', [
            'berichten' => $berichten,
            'naam'      => $naam
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
