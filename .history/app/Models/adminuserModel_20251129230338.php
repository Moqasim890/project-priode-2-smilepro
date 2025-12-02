<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminUserModel extends Model
{
    /**
     * Fetch all users via stored procedure with limit & offset.
     */
    static public function GetAllUsers(int $limit = 100, int $offset = 0){
        try {
            return DB::select('CALL SP_GetAllUsers(?, ?)', [$limit, $offset]);
        } catch (\Throwable $e) {
           return [];
        }
    }
    static public function SP_CountAllUsers(){
        return 
    }
}