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
        $afspraken = Afspraken::getAllWithNames();
        return view('afspraken.index', compact('afspraken'));
    }

    public function show($id)
    {
        $afspraak = Afspraken::findOrFail($id);
        return view('afspraken.show', compact('afspraak'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patientid' => 'required',
            'medewerkerid' => 'required',
            'datum' => 'required|date',
            'tijd' => 'required',
        ]);

        Afspraken::create(array_merge($validated, ['status' => 'Bevestigd']));

        return redirect()->route('afspraken.index')
            ->with('success', 'Afspraak aangemaakt!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'datum' => 'required|date',
            'tijd' => 'required',
            'status' => 'required',
            'opmerking' => 'nullable',
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

