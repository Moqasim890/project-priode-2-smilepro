<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persoon extends Model
{
    use HasFactory;

    protected $table = 'persoon';

    protected $fillable = [
        'gebruikerid',
        'voornaam',
        'tussenvoegsel',
        'achternaam',
        'geboortedatum',
        'isactief',
        'opmerking',
    ];

    const CREATED_AT = 'datumaangemaakt';
    const UPDATED_AT = 'datumgewijzigd';

    public function user()
    {
        return $this->belongsTo(User::class, 'gebruikerid');
    }
}