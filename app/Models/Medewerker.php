<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medewerker extends Model
{
    use HasFactory;

    protected $table = 'medewerker';

    protected $fillable = [
        'persoonid',
        'nummer',
        'medewerkertype',
        'specialisatie',
        'beschikbaarheid',
        'isactief',
        'opmerking',
    ];

    const CREATED_AT = 'datumaangemaakt';
    const UPDATED_AT = 'datumgewijzigd';

    public function persoon()
    {
        return $this->belongsTo(Persoon::class, 'persoonid');
    }

    public function afspraken()
    {
        return $this->hasMany(Afspraken::class, 'medewerkerid');
    }
}