<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factuur extends Model
{
    protected $table = 'factuur';
    
    public $timestamps = false;
    
    protected $fillable = [
        'patientid',
        'behandelingid',
        'nummer',
        'datum',
        'bedrag',
        'status',
        'isactief',
        'opmerking'
    ];

    protected $casts = [
        'datum' => 'date',
        'bedrag' => 'decimal:2',
        'isactief' => 'boolean'
    ];

    /**
     * Get the patient that owns the invoice
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patientid');
    }

    /**
     * Get the behandeling that owns the invoice
     */
    public function behandeling()
    {
        return $this->belongsTo(Behandeling::class, 'behandelingid');
    }
}
