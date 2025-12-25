<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PatientModel extends Model
{
    static public function SP_GetBerichtenById($patientId)
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
}