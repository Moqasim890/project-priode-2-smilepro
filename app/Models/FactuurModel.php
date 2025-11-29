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
    
    public static function GetTotaalFactuurBedrag(){
        try{
            $results = DB::select('CALL SP_GetAllTotaalbedragFacturen()');
            
            // Converteer array naar associatieve array met status als key
            $totalen = [];
            foreach ($results as $row) {
                $totalen[$row->status] = $row->totaalbedrag;
            }
            
            return $totalen;
        } catch (\Throwable $e) {
           return [];
        }
    }
}