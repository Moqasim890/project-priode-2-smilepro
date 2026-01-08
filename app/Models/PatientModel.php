<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class PatientModel extends Model
{

    static function GetAllpatienten(){
        try{
            log::info('GetAllpatienten uitgevoerd');

            $result = DB::select('CALL SP_GetAllPatienten()');

            log::info('GetAllPatienten succesvol uitgevoerd');

            return $result;
        }
        catch(\Throwable $e) {
            // loged de fout met alle details in de error log
            Log::error('GetAllFacturen mislukt', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
                // 'limit' => $limit,
                // 'offset' => $offset
            ]);
        }
    }


        static function GetAllBehandelingen($pid){
        try{
            log::info('GetAllBehandelingen uitgevoerd');

            $result = DB::select('CALL SP_GetAllBehandelingen(?)', [$pid]);
            // dd($result);

            log::info('GetAllBehandelingen succesvol uitgevoerd');

            return $result;
        }
        catch(\Throwable $e) {
            // loged de fout met alle details in de error log
            Log::error('GetAllBehandelingen mislukt', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
                // 'limit' => $limit,
                // 'offset' => $offset
            ]);
        }
    }
}
