<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medewerker extends Model
{
    protected $table = 'medewerker';
    
    public $timestamps = false;
    
    protected $fillable = [
        'persoonid',
        'nummer',
        'medewerkertype',
        'specialisatie',
        'beschikbaarheid',
        'isactief',
        'opmerking'
    ];

    protected $casts = [
        'isactief' => 'boolean'
    ];

    /**
     * Get the persoon that owns the medewerker
     */
    public function persoon()
    {
        return $this->belongsTo(Person::class, 'persoonid');
    }

    /**
     * Get the behandelingen for the medewerker
     */
    public function behandelingen()
    {
        return $this->hasMany(Behandeling::class, 'medewerkerid');
    }
}
