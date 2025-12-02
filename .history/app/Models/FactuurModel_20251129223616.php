<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FactuurModel extends Model
{
    public static function GetAllFacturen(int $limit = 100, int $offset = 0){
        try{
            return DB::select('CALL SP_GetAllFacturen(?,?)', [$limit, $offset]);
        } catch (\Throwable $e) {
           return 'error';
        }
    }
    
    public static function GetTotaalFactuurBedrag(?string $status = null){
        try{
            $results = DB::select('CALL SP_GetTotaalFactuurBedrag(?)'
        } catch (\Throwable $e) {
           return 'error';
        }
    }
}