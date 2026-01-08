<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AfspraakModel extends Model
{
    protected $table = 'afspraken';

    /**
     * Haal alle afspraken op.
     */
    public static function GetAllAfspraken(int $limit = 100, int $offset = 0): array
    {
        try {
            return DB::select('CALL SP_GetAllAfspraken(?, ?)', [$limit, $offset]);
        } catch (\Exception $e) {
            Log::error('GetAllAfspraken fout: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Haal één afspraak op via ID.
     */
    public static function GetAfspraakById(int $id): ?object
    {
        try {
            $result = DB::select('CALL SP_GetAfspraakById(?)', [$id]);
            return $result[0] ?? null;
        } catch (\Exception $e) {
            Log::error('GetAfspraakById fout: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Haal statistieken op voor dashboard.
     */
    public static function GetStatistieken(): ?object
    {
        try {
            $result = DB::select('CALL SP_GetAfsprakenStatistieken()');
            return $result[0] ?? null;
        } catch (\Exception $e) {
            Log::error('GetStatistieken fout: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Haal alle medewerkers op voor dropdown.
     */
    public static function GetBeschikbareMedewerkers(): array
    {
        try {
            return DB::select('CALL SP_GetBeschikbareMedewerkers()');
        } catch (\Exception $e) {
            Log::error('GetBeschikbareMedewerkers fout: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Haal alle patiënten op voor dropdown.
     */
    public static function GetAllPatienten(): array
    {
        try {
            return DB::select('CALL SP_GetAllPatientenDropdown()');
        } catch (\Exception $e) {
            Log::error('GetAllPatienten fout: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Maak een nieuwe afspraak aan.
     */
    public static function CreateAfspraak(array $data): ?int
    {
        try {
            $result = DB::select('CALL SP_CreateAfspraak(?, ?, ?, ?, ?, ?)', [
                $data['patientid'],
                $data['medewerkerid'],
                $data['datum'],
                $data['tijd'],
                $data['status'] ?? 'Bevestigd',
                $data['opmerking'] ?? null
            ]);

            if (empty($result) || !isset($result[0]->id)) {
                Log::error('CreateAfspraak: Geen ID geretourneerd van stored procedure');
                return null;
            }

            $afspraakId = (int) $result[0]->id;
            
            // Log succesvolle creatie
            Log::info('CreateAfspraak SUCCESS', [
                'afspraak_id' => $afspraakId,
                'patient_id' => $data['patientid'],
                'medewerker_id' => $data['medewerkerid'],
                'datum' => $data['datum'],
                'tijd' => $data['tijd'],
                'status' => $data['status'] ?? 'Bevestigd'
            ]);

            return $afspraakId;

        } catch (\Exception $e) {
            Log::error('CreateAfspraak FAILED: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * =========================================
     * Controleer of een tijdslot bezet is
     * =========================================
     * 
     * Happy Scenario:
     * - Tijdslot is vrij, retourneert false
     * - Afspraak kan worden aangemaakt
     * 
     * Unhappy Scenario:
     * - Tijdslot is al bezet door een andere afspraak
     * - Retourneert true, afspraak wordt NIET aangemaakt
     * - Gebruiker krijgt foutmelding te zien
     * =========================================
     * 
     * @param int $medewerkerid ID van de medewerker
     * @param string $datum Datum van de afspraak (Y-m-d)
     * @param string $tijd Tijd van de afspraak (H:i)
     * @param int|null $excludeId Afspraak ID om uit te sluiten (voor updates)
     * @return bool True als tijdslot bezet is, false als vrij
     */
    public static function IsTijdslotBezet(int $medewerkerid, string $datum, string $tijd, ?int $excludeId = null): bool
    {
        try {
            // Controleer of er al een afspraak bestaat voor deze medewerker op dit tijdstip
            // Een tijdslot is bezet als er een afspraak is op exact dezelfde tijd
            $query = '
                SELECT COUNT(*) as aantal 
                FROM afspraken 
                WHERE medewerkerid = ? 
                AND datum = ? 
                AND TIME_FORMAT(tijd, "%H:%i") = ?
                AND isactief = 1
            ';
            
            $params = [$medewerkerid, $datum, $tijd];
            
            // Bij update: sluit de huidige afspraak uit van de controle
            if ($excludeId !== null) {
                $query .= ' AND id != ?';
                $params[] = $excludeId;
            }
            
            Log::info('IsTijdslotBezet check', [
                'medewerkerid' => $medewerkerid,
                'datum' => $datum,
                'tijd' => $tijd,
                'params' => $params
            ]);
            
            $result = DB::select($query, $params);
            
            Log::info('IsTijdslotBezet result', ['aantal' => $result[0]->aantal ?? 0]);
            
            return ($result[0]->aantal ?? 0) > 0;
            
        } catch (\Exception $e) {
            Log::error('IsTijdslotBezet fout: ' . $e->getMessage());
            // Bij fout: veiligheidshalve aangeven dat slot bezet is
            return true;
        }
    }

 
}
