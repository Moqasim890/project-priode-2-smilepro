<?php

namespace App\Http\Controllers;

use App\Models\Afspraken;
use Illuminate\Http\Request;

class AfsprakenController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // Roep model method aan - DB query zit in model
        $afspraken = Afspraken::getAllWithNames();// Haal alle afspraken op met patient- en medewerker namen
        return view('afspraken.index', compact('afspraken'));// Stuur data naar view
    }

    public function show($id)
    {
        $afspraak = Afspraken::findOrFail($id);// Zoek afspraak op ID of geef 404
        return view('afspraken.show', compact('afspraak'));// Stuur data naar view
    }

    public function store(Request $request) // Sla nieuwe afspraak op
    {
        $validated = $request->validate([// Validate input data
            'patientid' => 'required',// 'patientid' is required
            'medewerkerid' => 'required',// 'medewerkerid' is required
            'datum' => 'required|date',// 'datum' is required and must be a date
            'tijd' => 'required',// 'tijd' is required
        ]);

        Afspraken::create(array_merge($validated, ['status' => 'Bevestigd']));

        return redirect()->route('afspraken.index')
            ->with('success', 'Afspraak aangemaakt!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([// Validate input data
            'datum' => 'required|date',// 'datum' is required and must be a date
            'tijd' => 'required',// 'tijd' is required
            'status' => 'required',// 'status' is required
            'opmerking' => 'nullable',// 'nullable' allows null values
        ]);

        $afspraak = Afspraken::findOrFail($id);
        $afspraak->update($validated);

        return redirect()->route('afspraken.index')
            ->with('success', 'Afspraak bijgewerkt!');
    }

    public function destroy($id)
    {
        $afspraak = Afspraken::findOrFail($id);
        $afspraak->update(['isactief' => 0]);

        return redirect()->route('afspraken.index')
            ->with('success', 'Afspraak verwijderd!');
    }
}

