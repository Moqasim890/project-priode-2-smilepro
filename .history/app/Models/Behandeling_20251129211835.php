<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Behandeling extends Model
{
    protected $table = 'behandeling';
    
    public $timestamps = false;
    
    protected $fillable = [
        'medewerkerid',
        'patientid',
        'datum',
        'tijd',
        'behandelingtype',
        'omschrijving',
        'kosten',
        'status',
        'isactief',
        'opmerking'
    ];

    protected $casts = [
        'datum' => 'date',
        'kosten' => 'decimal:2',
        'isactief' => 'boolean'
    ];

    /**
     * Get the patient that owns the behandeling
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patientid');
    }

    /**
     * Get the medewerker that owns the behandeling
     */
    public function medewerker()
    {
        return $this->belongsTo(Medewerker::class, 'medewerkerid');
    }

    /**
     * Get the factuur for the behandeling
     */
    public function factuur()
    {
        return $this->hasOne(Factuur::class, 'behandelingid');
    }
}
