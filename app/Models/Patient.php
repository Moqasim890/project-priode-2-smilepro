<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patient';

    protected $fillable = [
        'persoonid',
        'nummer',
        'medischdossier',
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
        return $this->hasMany(Afspraken::class, 'patientid');
    }
}