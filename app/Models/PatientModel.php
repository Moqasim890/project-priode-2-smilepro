<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PatientModel extends Model
{
    /** Alle patienten ophalen */
    public static function GetAllpatienten()
    {
        try {
            Log::info('GetAllpatienten uitgevoerd');
            $result = DB::select('CALL SP_GetAllPatienten()');
            Log::info('GetAllPatienten succesvol uitgevoerd');
            return $result;
        } catch (\Throwable $e) {
            Log::error('GetAllPatienten mislukt', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return [];
        }
    }

    /** Alle behandelingen voor patient */
    public static function GetAllBehandelingen($pid)
    {
        try {
            Log::info('GetAllBehandelingen uitgevoerd');
            $result = DB::select('CALL SP_GetAllBehandelingen(?)', [$pid]);
            Log::info('GetAllBehandelingen succesvol uitgevoerd');
            return $result;
        } catch (\Throwable $e) {
            Log::error('GetAllBehandelingen mislukt', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return [];
        }
    }

    /** Haal patientId via userId */
    public static function SP_GetPatientIdByUserId($userid)
    {
        try {
            Log::info('SP_GetPatientIdByUserId uitgevoerd');
            $result = DB::selectOne('CALL SP_GetPatientIdByUserId(?)', [$userid]);
            Log::info('SP_GetPatientIdByUserId succesvol');
            return $result?->patientid;
        } catch (\Throwable $e) {
            Log::info('SP_GetPatientIdByUserId niet succesvol uitgevoerd');
            return null;
        }
    }

    /** Berichten ophalen voor patient */
    public static function SP_GetBerichtenById($patientId)
    {
        try {
            Log::info('SP_GetBerichtenById uitgevoerd');
            $result = DB::select('CALL SP_GetBerichtenById(?)', [$patientId]);
            Log::info('SP_GetBerichtenById succesvol');
            return $result ?? [];
        } catch (\Throwable $e) {
            Log::info('SP_GetBerichtenById niet succesvol uitgevoerd');
            return [];
        }
    }

    /** Aantal berichten tellen */
    public static function SP_CountBerichtenById($patientId)
    {
        try {
            Log::info('SP_CountBerichtenById uitgevoerd');
            $result = DB::select('CALL SP_CountBerichtenById(?)', [$patientId]);
            Log::info('SP_CountBerichtenById succesvol');
            return $result ?? [];
        } catch (\Throwable $e) {
            Log::info('SP_CountBerichtenById niet succesvol uitgevoerd');
            return [];
        }
    }
}
