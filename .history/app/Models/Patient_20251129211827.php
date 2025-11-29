<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patient';
    
    public $timestamps = false;
    
    protected $fillable = [
        'persoonid',
        'nummer',
        'medischdossier',
        'isactief',
        'opmerking'
    ];

    protected $casts = [
        'isactief' => 'boolean'
    ];

    /**
     * Get the persoon that owns the patient
     */
    public function persoon()
    {
        return $this->belongsTo(Person::class, 'persoonid');
    }

    /**
     * Get the facturen for the patient
     */
    public function facturen()
    {
        return $this->hasMany(Factuur::class, 'patientid');
    }

    /**
     * Get the behandelingen for the patient
     */
    public function behandelingen()
    {
        return $this->hasMany(Behandeling::class, 'patientid');
    }
}
