<?php

namespace App\Http\Controllers;

use App\Models\Factuur;
use App\Models\Patient;
use App\Models\Behandeling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FactuurController extends Controller
{
    /**
     * Display a listing of facturen
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 25);
        $status = $request->get('status');
        
        $query = DB::table('factuur')
            ->join('patient', 'factuur.patientid', '=', 'patient.id')
            ->join('persoon', 'patient.persoonid', '=', 'persoon.id')
            ->join('behandeling', 'factuur.behandelingid', '=', 'behandeling.id')
            ->select(
                'factuur.id',
                'factuur.nummer',
                'factuur.datum',
                'factuur.bedrag',
                'factuur.status',
                DB::raw("CONCAT(persoon.voornaam, ' ', COALESCE(persoon.tussenvoegsel, ''), ' ', persoon.achternaam) as patient_naam"),
                'patient.nummer as patient_nummer',
                'behandeling.behandelingtype'
            )
            ->where('factuur.isactief', 1);

        if ($status) {
            $query->where('factuur.status', $status);
        }

        $facturen = $query->orderBy('factuur.datum', 'desc')
            ->paginate($perPage);

        $totalBedrag = DB::table('factuur')
            ->where('isactief', 1)
            ->sum('bedrag');

        $betaaldBedrag = DB::table('factuur')
            ->where('isactief', 1)
            ->where('status', 'Betaald')
            ->sum('bedrag');

        $openstaandBedrag = DB::table('factuur')
            ->where('isactief', 1)
            ->where('status', 'Onbetaald')
            ->sum('bedrag');

        return view('admin.facturen.index', compact('facturen', 'totalBedrag', 'betaaldBedrag', 'openstaandBedrag'));
    }

    /**
     * Show the form for creating a new factuur
     */
    public function create()
    {
        $patienten = DB::table('patient')
            ->join('persoon', 'patient.persoonid', '=', 'persoon.id')
            ->where('patient.isactief', 1)
            ->select(
                'patient.id',
                'patient.nummer',
                DB::raw("CONCAT(persoon.voornaam, ' ', COALESCE(persoon.tussenvoegsel, ''), ' ', persoon.achternaam) as naam")
            )
            ->orderBy('persoon.achternaam')
            ->get();

        return view('admin.facturen.create', compact('patienten'));
    }

    /**
     * Get behandelingen for a patient (AJAX)
     */
    public function getBehandelingen($patientId)
    {
        $behandelingen = DB::table('behandeling')
            ->leftJoin('factuur', 'behandeling.id', '=', 'factuur.behandelingid')
            ->where('behandeling.patientid', $patientId)
            ->where('behandeling.isactief', 1)
            ->whereNull('factuur.id') // Only behandelingen without factuur
            ->select(
                'behandeling.id',
                'behandeling.datum',
                'behandeling.behandelingtype',
                'behandeling.kosten',
                'behandeling.omschrijving'
            )
            ->orderBy('behandeling.datum', 'desc')
            ->get();

        return response()->json($behandelingen);
    }

    /**
     * Store a newly created factuur in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patientid' => 'required|exists:patient,id',
            'behandelingid' => 'required|exists:behandeling,id',
            'nummer' => 'required|unique:factuur,nummer|max:50',
            'datum' => 'required|date',
            'bedrag' => 'required|numeric|min:0',
            'status' => 'required|in:Verzonden,Niet-Verzonden,Betaald,Onbetaald',
            'opmerking' => 'nullable|string'
        ]);

        Factuur::create($validated);

        return redirect()->route('admin.facturen.index')
            ->with('success', 'Factuur succesvol aangemaakt');
    }

    /**
     * Show the form for editing the specified factuur
     */
    public function edit($id)
    {
        $factuur = Factuur::with(['patient.persoon', 'behandeling'])->findOrFail($id);
        
        return view('admin.facturen.edit', compact('factuur'));
    }

    /**
     * Update the specified factuur in database
     */
    public function update(Request $request, $id)
    {
        $factuur = Factuur::findOrFail($id);

        $validated = $request->validate([
            'nummer' => 'required|max:50|unique:factuur,nummer,' . $id,
            'datum' => 'required|date',
            'bedrag' => 'required|numeric|min:0',
            'status' => 'required|in:Verzonden,Niet-Verzonden,Betaald,Onbetaald',
            'opmerking' => 'nullable|string'
        ]);

        $factuur->update($validated);

        return redirect()->route('admin.facturen.index')
            ->with('success', 'Factuur succesvol bijgewerkt');
    }

    /**
     * Remove the specified factuur from database (soft delete)
     */
    public function destroy($id)
    {
        $factuur = Factuur::findOrFail($id);
        $factuur->update(['isactief' => 0]);

        return redirect()->route('admin.facturen.index')
            ->with('success', 'Factuur succesvol verwijderd');
    }
}
