<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardModel
{
    /**
     * Haal afspraken statistieken op.
     */
    public static function GetAfsprakenStats(): ?object
    {
        try {
            $result = DB::select('CALL SP_GetAfsprakenStatistieken()');
            return $result[0] ?? null;
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetAfsprakenStats: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Haal factuur statistieken op per status.
     */
    public static function GetFactuurStats(): array
    {
        try {
            return DB::select('CALL SP_GetAllTotaalbedragFacturen()');
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetFactuurStats: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Haal totale omzet op.
     */
    public static function GetOmzetTotaal(): float
    {
        try {
            $result = DB::select('
                SELECT COALESCE(SUM(bedrag), 0) as totaal 
                FROM factuur 
                WHERE isactief = 1 AND status = "Betaald"
            ');
            return (float) ($result[0]->totaal ?? 0);
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetOmzetTotaal: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Haal omzet per maand op (laatste 6 maanden).
     */
    public static function GetOmzetPerMaand(): array
    {
        try {
            return DB::select('
                SELECT 
                    DATE_FORMAT(datum, "%Y-%m") as maand,
                    DATE_FORMAT(datum, "%M %Y") as maand_naam,
                    COUNT(*) as aantal_facturen,
                    COALESCE(SUM(bedrag), 0) as totaal
                FROM factuur
                WHERE isactief = 1 
                AND datum >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(datum, "%Y-%m"), DATE_FORMAT(datum, "%M %Y")
                ORDER BY maand DESC
            ');
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetOmzetPerMaand: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Haal totaal aantal patiënten op.
     */
    public static function GetTotaalPatienten(): int
    {
        try {
            $result = DB::select('SELECT COUNT(*) as totaal FROM patient WHERE isactief = 1');
            return $result[0]->totaal ?? 0;
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetTotaalPatienten: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Haal totaal aantal medewerkers op.
     */
    public static function GetTotaalMedewerkers(): int
    {
        try {
            $result = DB::select('SELECT COUNT(*) as totaal FROM medewerker WHERE isactief = 1');
            return $result[0]->totaal ?? 0;
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetTotaalMedewerkers: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Haal recente afspraken op.
     */
    public static function GetRecenteAfspraken(int $limit = 5): array
    {
        try {
            return DB::select('
                SELECT 
                    a.id,
                    a.datum,
                    a.tijd,
                    a.status,
                    CONCAT_WS(" ", p.voornaam, p.tussenvoegsel, p.achternaam) AS patientnaam,
                    CONCAT_WS(" ", pm.voornaam, pm.tussenvoegsel, pm.achternaam) AS medewerkernaam
                FROM afspraken a
                INNER JOIN patient pt ON pt.id = a.patientid
                INNER JOIN persoon p ON p.id = pt.persoonid
                LEFT JOIN medewerker m ON m.id = a.medewerkerid
                LEFT JOIN persoon pm ON pm.id = m.persoonid
                WHERE a.isactief = 1 
                AND a.datum >= CURDATE()
                ORDER BY a.datum ASC, a.tijd ASC
                LIMIT ?
            ', [$limit]);
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetRecenteAfspraken: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Haal afspraken per maand op (laatste 6 maanden).
     */
    public static function GetAfsprakenPerMaand(): array
    {
        try {
            return DB::select('
                SELECT 
                    DATE_FORMAT(datum, "%Y-%m") as maand,
                    DATE_FORMAT(datum, "%M %Y") as maand_naam,
                    COUNT(*) as totaal,
                    SUM(CASE WHEN status = "Bevestigd" THEN 1 ELSE 0 END) as bevestigd,
                    SUM(CASE WHEN status = "Geannuleerd" THEN 1 ELSE 0 END) as geannuleerd
                FROM afspraken
                WHERE isactief = 1 
                AND datum >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(datum, "%Y-%m"), DATE_FORMAT(datum, "%M %Y")
                ORDER BY maand DESC
            ');
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetAfsprakenPerMaand: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Haal afspraken per medewerker op.
     */
    public static function GetAfsprakenPerMedewerker(): array
    {
        try {
            return DB::select('
                SELECT 
                    m.id,
                    CONCAT_WS(" ", p.voornaam, p.tussenvoegsel, p.achternaam) AS naam,
                    m.medewerkertype,
                    COUNT(a.id) as aantal_afspraken
                FROM medewerker m
                INNER JOIN persoon p ON p.id = m.persoonid
                LEFT JOIN afspraken a ON a.medewerkerid = m.id AND a.isactief = 1
                WHERE m.isactief = 1
                GROUP BY m.id, p.voornaam, p.tussenvoegsel, p.achternaam, m.medewerkertype
                ORDER BY aantal_afspraken DESC
            ');
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetAfsprakenPerMedewerker: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Haal openstaande facturen op.
     */
    public static function GetOpenstaandeFacturen(): array
    {
        try {
            return DB::select('
                SELECT 
                    f.id,
                    f.nummer,
                    f.datum,
                    f.bedrag,
                    f.status,
                    CONCAT_WS(" ", p.voornaam, p.tussenvoegsel, p.achternaam) AS patientnaam
                FROM factuur f
                INNER JOIN patient pt ON pt.id = f.patientid
                INNER JOIN persoon p ON p.id = pt.persoonid
                WHERE f.isactief = 1 
                AND f.status IN ("Verzonden", "Onbetaald")
                ORDER BY f.datum DESC
                LIMIT 10
            ');
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetOpenstaandeFacturen: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * =========================================
     * Haal omzetgegevens op per periode
     * =========================================
     * 
     * Happy Scenario:
     * - Retourneert array met omzetgegevens per dag/week binnen periode
     * - Bevat datum, aantal facturen en totaalbedrag
     * 
     * Unhappy Scenario:
     * - Retourneert lege array als er geen gegevens zijn
     * - Controller toont melding aan gebruiker
     * =========================================
     * 
     * @param string $startDatum Begin datum van de periode (Y-m-d)
     * @param string $eindDatum Eind datum van de periode (Y-m-d)
     * @return array Omzetgegevens per dag binnen de periode
     */
    public static function GetOmzetPerPeriode(string $startDatum, string $eindDatum): array
    {
        try {
            return DB::select('
                SELECT 
                    DATE(datum) as datum,
                    COUNT(*) as aantal_facturen,
                    COALESCE(SUM(bedrag), 0) as totaal_omzet
                FROM factuur
                WHERE isactief = 1 
                AND status = "Betaald"
                AND datum BETWEEN ? AND ?
                GROUP BY DATE(datum)
                ORDER BY datum ASC
            ', [$startDatum, $eindDatum]);
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetOmzetPerPeriode: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * =========================================
     * Haal totale omzet op voor een specifieke periode
     * =========================================
     * 
     * Happy Scenario:
     * - Retourneert het totaalbedrag van alle betaalde facturen
     * - Wordt weergegeven als "Totale omzet" in de view
     * 
     * Unhappy Scenario:
     * - Retourneert 0 als er geen omzet is in de periode
     * - Controller controleert op 0 en toont melding
     * =========================================
     * 
     * @param string $startDatum Begin datum van de periode (Y-m-d)
     * @param string $eindDatum Eind datum van de periode (Y-m-d)
     * @return float Totale omzet voor de periode
     */
    public static function GetTotaleOmzetPerPeriode(string $startDatum, string $eindDatum): float
    {
        try {
            $result = DB::select('
                SELECT COALESCE(SUM(bedrag), 0) as totaal 
                FROM factuur 
                WHERE isactief = 1 
                AND status = "Betaald"
                AND datum BETWEEN ? AND ?
            ', [$startDatum, $eindDatum]);
            
            return (float) ($result[0]->totaal ?? 0);
        } catch (\Exception $e) {
            Log::error('DashboardModel@GetTotaleOmzetPerPeriode: ' . $e->getMessage());
            return 0;
        }
    }
}
