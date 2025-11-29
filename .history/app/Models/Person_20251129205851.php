<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Person extends Model
{
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gebruikerid');
    }
}
