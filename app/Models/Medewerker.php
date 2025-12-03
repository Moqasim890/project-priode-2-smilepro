<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medewerker extends Model
{
    use HasFactory;

    protected $table = 'medewerker';

=======
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medewerker extends Model
{
    protected $table = 'medewerker';

    // Dit model gebruikt custom timestamp kolommen in SQL, dus we schakelen de standaard timestamps uit
    public $timestamps = false;

>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
    protected $fillable = [
        'persoonid',
        'nummer',
        'medewerkertype',
        'specialisatie',
        'beschikbaarheid',
        'isactief',
        'opmerking',
    ];

<<<<<<< HEAD
    const CREATED_AT = 'datumaangemaakt';
    const UPDATED_AT = 'datumgewijzigd';

    public function persoon()
    {
        return $this->belongsTo(Persoon::class, 'persoonid');
    }

    public function afspraken()
    {
        return $this->hasMany(Afspraken::class, 'medewerkerid');
=======
    /**
     * Relatie naar persoon (naam / basisgegevens)
     */
    public function persoon(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'persoonid');
    }

    /**
     * Placeholder relatie naar behandelingen
     * (wordt momenteel nog niet gebruikt, maar sluit aan bij de database structuur)
     */
    public function behandelingen(): HasMany
    {
        return $this->hasMany(Behandeling::class, 'medewerkerid');
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
    }
}