<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\AfspraakModel as Afspraak;

class AfspraakController extends Controller
{
    /** Determine route name prefix based on current scope (admin vs medewerker). */
    private function routePrefix(): string
    {
        return request()->routeIs('admin.*') ? 'admin.afspraken' : 'medewerker.afspraken';
    }

    /**
     * Toon afspraken overzicht.
     */
    public function index()
    {
        try {
            $afspraken = Afspraak::GetAllAfspraken();
            $statistieken = Afspraak::GetStatistieken();
            
            return view('medewerker.afspraken.index', compact('afspraken', 'statistieken'));

        } catch (\Exception $e) {
            Log::error('AfspraakController@index: ' . $e->getMessage());
            return view('medewerker.afspraken.index', [
                'afspraken' => [],
                'statistieken' => null
            ])->with('error', 'Fout bij ophalen afspraken.');
        }
    }

    /**
     * Toon formulier voor nieuwe afspraak.
     */
    public function create()
    {
        try {
            $patienten = Afspraak::GetAllPatienten();
            $medewerkers = Afspraak::GetBeschikbareMedewerkers();
            
            return view('medewerker.afspraken.create', compact('patienten', 'medewerkers'));

        } catch (\Exception $e) {
            Log::error('AfspraakController@create: ' . $e->getMessage());
            return redirect()->route($this->routePrefix() . '.index')
                ->with('error', 'Fout bij laden formulier.');
        }
    }

    /**
     * =========================================
     * Sla nieuwe afspraak op.
     * =========================================
     * 
     * Happy Scenario:
     * - Geldige afspraakgegevens worden ingevoerd
     * - Tijdslot is beschikbaar
     * - Afspraak wordt toegevoegd aan de agenda
     * - Gebruiker ziet bevestiging dat afspraak succesvol is aangemaakt
     * 
     * Unhappy Scenario:
     * - Tijdslot is al bezet door een andere afspraak
     * - Foutmelding wordt getoond dat tijdslot niet beschikbaar is
     * - Afspraak wordt NIET toegevoegd aan de agenda
     * =========================================
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'patientid' => 'required|integer|min:1',
                'medewerkerid' => 'required|integer|min:1',
                'datum' => 'required|date|after_or_equal:today',
                'tijd' => 'required|date_format:H:i',
                'status' => 'required|in:Bevestigd,Geannuleerd',
                'opmerking' => 'nullable|string|max:500'
            ]);

            // =========================================
            // UNHAPPY SCENARIO: Controleer of tijdslot bezet is
            // Als de medewerker al een afspraak heeft op dit tijdstip,
            // toon een foutmelding en voeg de afspraak NIET toe
            // =========================================
            if (Afspraak::IsTijdslotBezet(
                $validated['medewerkerid'], 
                $validated['datum'], 
                $validated['tijd']
            )) {
                return back()->withInput()
                    ->with('error', 'Dit tijdslot is niet beschikbaar. De geselecteerde medewerker heeft al een afspraak op dit tijdstip. Kies een ander tijdstip of een andere medewerker.');
            }

            // =========================================
            // HAPPY SCENARIO: Tijdslot is vrij, maak afspraak aan
            // =========================================
            $afspraakId = Afspraak::CreateAfspraak($validated);

            if ($afspraakId) {
                return redirect()->route($this->routePrefix() . '.index')
                    ->with('success', 'Afspraak succesvol aangemaakt!');
            }

            return back()->withInput()
                ->with('error', 'Afspraak aanmaken mislukt.');

        } catch (\Exception $e) {
            Log::error('AfspraakController@store: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Er is een fout opgetreden.');
        }
    }



}
