<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * AdminUserModel
 * 
 * Model voor het beheren van gebruikers via stored procedures.
 * Gebruikt voor de admin interface om gebruikerslijsten op te halen en te tellen.
 */
class AdminUserModel extends Model
{
    /**
     * Haal alle gebruikers op via stored procedure met paginering.
     * 
     * Roept SP_GetAllUsers aan die gebruikers ophaalt met hun rollen.
     * Gebruikt LIMIT en OFFSET voor paginering.
     * 
     * @param int $limit Aantal gebruikers per pagina (default: 100)
     * @param int $offset Startpositie voor paginering (default: 0)
     * @return array Array van gebruiker objecten, of lege array bij fout
     */
    static public function GetAllUsers(int $limit = 100, int $offset = 0){
        try {
            // Log de aanroep met parameters voor debugging
            Log::info('GetAllUsers uitgevoerd', ['limit' => $limit, 'offset' => $offset]);
            
            // Voer stored procedure uit
            $result = DB::select('CALL SP_GetAllUsers(?, ?)', [$limit, $offset]);
            
            // Log succesvol resultaat
            Log::info('GetAllUsers succesvol', ['aantal_users' => count($result)]);
            
            return $result;
        } catch (\Throwable $e) {
            // Log de fout met volledige details
            Log::error('GetAllUsers mislukt', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'limit' => $limit,
                'offset' => $offset
            ]);
            
            // Return lege array bij fout zodat de applicatie blijft werken
            return [];
        }
    }
    
    /**
     * Tel het totaal aantal gebruikers in het systeem.
     * 
     * Roept SP_CountAllUsers aan die het totaal aantal gebruikers retourneert.
     * Gebruikt voor paginering berekeningen.
     * 
     * @return int Totaal aantal gebruikers, of 0 bij fout
     */
    static public function SP_CountAllUsers(){
        try {
            // Log de aanroep
            Log::info('SP_CountAllUsers uitgevoerd');
            
            // Voer stored procedure uit en haal eerste rij op
            $result = DB::selectOne("CALL SP_CountAllUsers()");
            
            // Extraheer count waarde, of 0 als result null is
            $count = $result ? $result->total_users : 0;
            
            // Log succesvol resultaat
            Log::info('SP_CountAllUsers succesvol', ['total_users' => $count]);
            
            return $count;
        } catch (\Throwable $e) {
            // Log de fout met volledige details
            Log::error('SP_CountAllUsers mislukt', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // Return 0 bij fout zodat paginering niet breekt
            return 0;
        }
    }
    
    /**
     * Haal alle patienten
     */
    static public function SP_GetAllPatienten(){
        try {
            // Log de aanroep
            Log::info('SP_GetAllPatienten uitgevoerd');
            
            // Voer stored procedure uit
            $result = DB::select("CALL SP_GetAllPatienten()");
            
            // Log succesvol resultaat
            Log::info('SP_GetAllPatienten succesvol');
            
            return $result;
        } catch (\Throwable $e) {
            // Log dat er een fout was
            Log::error('SP_GetAllPatienten mislukt');
            
            // Return lege array bij fout zodat de applicatie blijft werken
            return [];
        }
    } 

    /**
     * Haal alle Berichten
     */
    static public function SP_GetAllBerichten(){
        try {
            // Log de aanroep
            Log::info('SP_GetAllBerichten uitgevoerd');
            
            // Voer stored procedure uit
            $result = DB::select("CALL SP_GetAllBerichten()");
            
            // Log succesvol resultaat
            Log::info('SP_GetAllBerichten succesvol');
            
            return $result;
        } catch (\Throwable $e) {
            // Log dat er een fout was
            Log::error('SP_GetAllBerichten mislukt');
            
            // Return lege array bij fout zodat de applicatie blijft werken
            return [];
        }
    } 
}