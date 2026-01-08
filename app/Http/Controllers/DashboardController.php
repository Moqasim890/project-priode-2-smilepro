<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\DashboardModel as Dashboard;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard - Management overzicht.
     * - Dashboard beheren
     * - Aantal afspraken bekijken
     * - Omzet bekijken
     */
    public function adminDashboard()
    {
        try {
            $data = [
                // Afspraken statistieken
                'afsprakenStats' => Dashboard::GetAfsprakenStats(),
                'recenteAfspraken' => Dashboard::GetRecenteAfspraken(5),
                
                // Omzet statistieken
                'factuurStats' => Dashboard::GetFactuurStats(),
                'omzetTotaal' => Dashboard::GetOmzetTotaal(),
                'openstaandeFacturen' => Dashboard::GetOpenstaandeFacturen(),
                
                // Algemene statistieken
                'totaalPatienten' => Dashboard::GetTotaalPatienten(),
                'totaalMedewerkers' => Dashboard::GetTotaalMedewerkers()
            ];
            
            return view('admin.dashboard', $data);

        } catch (\Exception $e) {
            Log::error('DashboardController@adminDashboard: ' . $e->getMessage());
            return view('admin.dashboard', $this->getEmptyAdminData())
                ->with('error', 'Fout bij laden dashboard.');
        }
    }

    /**
     * Medewerker Dashboard - beperkt overzicht.
     */
    public function medewerkerDashboard()
    {
        try {
            $data = [
                'afsprakenStats' => Dashboard::GetAfsprakenStats(),
                'recenteAfspraken' => Dashboard::GetRecenteAfspraken(10),
                'totaalPatienten' => Dashboard::GetTotaalPatienten()
            ];
            
            return view('medewerker.dashboard', $data);

        } catch (\Exception $e) {
            Log::error('DashboardController@medewerkerDashboard: ' . $e->getMessage());
            return view('medewerker.dashboard', [
                'afsprakenStats' => null,
                'recenteAfspraken' => [],
                'totaalPatienten' => 0
            ])->with('error', 'Fout bij laden dashboard.');
        }
    }

    /**
     * Afspraken overzicht pagina voor management.
     */
    public function afsprakenOverzicht()
    {
        try {
            $data = [
                'afsprakenStats' => Dashboard::GetAfsprakenStats(),
                'afsprakenPerMaand' => Dashboard::GetAfsprakenPerMaand(),
                'afsprakenPerMedewerker' => Dashboard::GetAfsprakenPerMedewerker()
            ];
            
            return view('admin.afspraken-overzicht', $data);

        } catch (\Exception $e) {
            Log::error('DashboardController@afsprakenOverzicht: ' . $e->getMessage());
            return back()->with('error', 'Fout bij laden afspraken overzicht.');
        }
    }

    /**
     * Omzet overzicht pagina voor management.
     */
    public function omzetOverzicht()
    {
        try {
            $data = [
                'factuurStats' => Dashboard::GetFactuurStats(),
                'omzetTotaal' => Dashboard::GetOmzetTotaal(),
                'omzetPerMaand' => Dashboard::GetOmzetPerMaand(),
                'openstaandeFacturen' => Dashboard::GetOpenstaandeFacturen()
            ];
            
            return view('admin.omzet-overzicht', $data);

        } catch (\Exception $e) {
            Log::error('DashboardController@omzetOverzicht: ' . $e->getMessage());
            return back()->with('error', 'Fout bij laden omzet overzicht.');
        }
    }

    /**
     * =========================================
     * Omzet bekijken per periode voor praktijkmanager
     * =========================================
     * 
     * Happy Scenario:
     * - Praktijkmanager gaat naar management dashboard
     * - Selecteert "Omzet bekijken" optie
     * - Ziet omzetgegevens van de praktijk
     * - Totale omzet wordt weergegeven
     * - Overzicht per geselecteerde periode wordt getoond
     * 
     * Unhappy Scenario:
     * - Praktijkmanager selecteert periode zonder gegevens
     * - Melding wordt getoond dat er geen omzetgegevens beschikbaar zijn
     * - Geen omzetoverzicht wordt weergegeven
     * =========================================
     */
    public function omzetBekijken(Request $request)
    {
        try {
            // Haal periode parameters op (default: huidige maand)
            $startDatum = $request->input('start_datum', date('Y-m-01'));
            $eindDatum = $request->input('eind_datum', date('Y-m-t'));

            // Haal omzetgegevens op voor de geselecteerde periode
            $omzetGegevens = Dashboard::GetOmzetPerPeriode($startDatum, $eindDatum);
            $totaleOmzet = Dashboard::GetTotaleOmzetPerPeriode($startDatum, $eindDatum);

            // =========================================
            // UNHAPPY SCENARIO: Geen omzet beschikbaar
            // Wanneer er geen omzetgegevens zijn voor de 
            // geselecteerde periode, toon een melding
            // =========================================
            if (empty($omzetGegevens) || $totaleOmzet == 0) {
                return view('admin.omzet-bekijken', [
                    'omzetGegevens' => [],
                    'totaleOmzet' => 0,
                    'startDatum' => $startDatum,
                    'eindDatum' => $eindDatum,
                    'geenGegevens' => true  // Flag voor unhappy scenario
                ])->with('warning', 'Geen omzetgegevens beschikbaar voor de geselecteerde periode.');
            }

            // =========================================
            // HAPPY SCENARIO: Omzetgegevens beschikbaar
            // Toon de omzetgegevens en totale omzet
            // =========================================
            $data = [
                'omzetGegevens' => $omzetGegevens,
                'totaleOmzet' => $totaleOmzet,
                'startDatum' => $startDatum,
                'eindDatum' => $eindDatum,
                'geenGegevens' => false
            ];

            return view('admin.omzet-bekijken', $data);

        } catch (\Exception $e) {
            Log::error('DashboardController@omzetBekijken: ' . $e->getMessage());
            return back()->with('error', 'Fout bij ophalen omzetgegevens.');
        }
    }

    /**
     * Lege data voor admin dashboard bij errors.
     */
    private function getEmptyAdminData(): array
    {
        return [
            'afsprakenStats' => null,
            'recenteAfspraken' => [],
            'factuurStats' => [],
            'omzetTotaal' => 0,
            'openstaandeFacturen' => [],
            'totaalPatienten' => 0,
            'totaalMedewerkers' => 0
        ];
    }
}
