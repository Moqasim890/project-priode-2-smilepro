<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminUserModel extends Model
{
    static public function GetAllUsers(){
        return DB::select()
    } 
}