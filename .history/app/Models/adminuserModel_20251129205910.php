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
            // Fallback: JOIN users with persoon to provide similar dataset
            return DB::select(
                'SELECT u.id, u.name, u.email, u.created_at, p.voornaam, p.tussenvoegsel, p.achternaam, p.geboortedatum
                 FROM users u
                 LEFT JOIN persoon p ON p.gebruikerid = u.id
                 ORDER BY u.id DESC
                 LIMIT ? OFFSET ?',
                [$limit, $offset]
            );
        }
    } 
}