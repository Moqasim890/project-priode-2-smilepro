<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PatientModel extends Model
{
    public function SP_GetBerichtenByUserId($patientId)
    {
        DB::select('CALL SP_GetBerichtenByUserId(?)', [$patientId]);
    }
}