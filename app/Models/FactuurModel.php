<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * FactuurModel
 * 
 * Model voor het beheren van facturen via stored procedures.
 * Gebruikt voor het ophalen van factuuroverzichten en totaalbedragen per status.
 */
class FactuurModel extends Model
{
    /**
     * Haal alle facturen op via stored procedure met paginering.
     * 
     * Roept SP_GetAllFacturen aan die facturen ophaalt met patiënt gegevens
     * en gekoppelde behandelingen (via GROUP_CONCAT).
     * 
     * @param int $limit Aantal facturen per pagina (default: 100)
     * @param int $offset Startpositie voor paginering (default: 0)
     * @return array Array van factuur objecten met patiënt en behandelingen, of lege array bij fout
     */
    public static function GetAllFacturen(int $limit = 100, int $offset = 0){
        try{
            // Log de aanroep met parameters
            Log::info('GetAllFacturen uitgevoerd', ['limit' => $limit, 'offset' => $offset]);
            
            // Voer stored procedure uit
            $result = DB::select('CALL SP_GetAllFacturen(?,?)', [$limit, $offset]);
            
            // Log succesvol resultaat
            Log::info('GetAllFacturen succesvol', ['aantal_facturen' => count($result)]);
            
            return $result;
        } catch (\Throwable $e) {
            // Log de fout met volledige details
            Log::error('GetAllFacturen mislukt', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'limit' => $limit,
                'offset' => $offset
            ]);
            
            // Return lege array bij fout zodat view blijft werken
            return [];
        }
    }
    
    /**
     * Haal totaalbedragen op gegroepeerd per factuurstatus.
     * 
     * Roept SP_GetAllTotaalbedragFacturen aan die SUM van bedragen per status berekent.
     * Gebruikt voor statistiek cards op facturen overzicht pagina.
     * 
     * @return array Associatieve array met status als key en totaalbedrag als value, of lege array bij fout
     */
    public static function GetTotaalFactuurBedrag(){
        try{
            // Log de aanroep
            Log::info('GetTotaalFactuurBedrag uitgevoerd');
            
            // Voer stored procedure uit
            $results = DB::select('CALL SP_GetAllTotaalbedragFacturen()');
            
            // Converteer array van objecten naar associatieve array voor makkelijke toegang
            // Key = status (Verzonden, Onbetaald, etc.), Value = totaalbedrag
            $totalen = [];
            foreach ($results as $row) {
                $totalen[$row->status] = $row->totaalbedrag;
            }
            
            // Log succesvol resultaat met welke statuses gevonden zijn
            Log::info('GetTotaalFactuurBedrag succesvol', ['statuses' => array_keys($totalen)]);
            
            return $totalen;
        } catch (\Throwable $e) {
            // Log de fout met volledige details
            Log::error('GetTotaalFactuurBedrag mislukt', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return lege array bij fout zodat view "Geen data beschikbaar" kan tonen
            return [];
        }
    }
}