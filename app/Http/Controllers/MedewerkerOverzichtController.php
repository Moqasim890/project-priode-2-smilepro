<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class MedewerkerOverzichtController extends Controller
{
    /**
     * Toon een overzicht van alle medewerkers.
     *
     * Happy scenario:
     *  - /management/medewerkers
     *  - Haalt alle gebruikers op met een medewerker-rol
     *
     * Unhappy scenario:
     *  - /management/medewerkers?forceError=1
     *  - Simuleert een fout en toont de foutmelding + leeg overzicht
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);
        if ($perPage < 1) {
            $perPage = 10;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $search = trim((string) $request->query('q', ''));
        $forceError = $request->boolean('forceError', false);

        // Unhappy scenario: expres een fout simuleren (500-achtig gedrag)
        if ($forceError) {
            $emptyPaginator = new LengthAwarePaginator([], 0, $perPage, 1, [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]);

            return view('medewerker.index', [
                'medewerkers'  => $emptyPaginator,
                'search'       => $search,
                'errorMessage' => 'Er is een fout opgetreden bij het ophalen van de medewerkers. Probeer het later opnieuw.',
            ]);
        }

        try {
            // Rollen die als “medewerker” tellen
            $medewerkerRoles = ['Praktijkmanagement', 'Tandarts', 'Mondhygiënist', 'Assistent'];

            $query = User::query()
                ->with('roles')
                ->whereHas('roles', function ($q) use ($medewerkerRoles) {
                    $q->whereIn('name', $medewerkerRoles);
                });

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            }

            $medewerkers = $query
                ->orderBy('name')
                ->paginate($perPage)
                ->withQueryString();

            return view('medewerker.index', [
                'medewerkers' => $medewerkers,
                'search'      => $search,
            ]);
        } catch (\Throwable $e) {
            Log::error('Medewerkers overzicht ophalen mislukt', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            $emptyPaginator = new LengthAwarePaginator([], 0, $perPage, 1, [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]);

            return view('medewerker.index', [
                'medewerkers'  => $emptyPaginator,
                'search'       => $search,
                'errorMessage' => 'Er is een fout opgetreden bij het ophalen van de medewerkers. Probeer het later opnieuw.',
            ]);
        }
    }
}