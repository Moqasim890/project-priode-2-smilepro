<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FactuurModel extends Model
{
    public static function GetAllFacturen(int $limit = 100, int $offset = 0){
        try
        return DB::select('CALL SP_GetAllFacturen(?,?)', []);
    }
}