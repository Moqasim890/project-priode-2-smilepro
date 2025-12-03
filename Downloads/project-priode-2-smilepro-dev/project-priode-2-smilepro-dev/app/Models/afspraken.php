<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Afspraken extends Model
{
    use HasFactory;

    protected $table = 'afspraken';

    protected $fillable = [
        'patientid',
        'medewerkerid',
        'datum',
        'tijd',
        'status',
        'isactief',
        'opmerking',
    ];

    // Gebruik custom timestamps
    const CREATED_AT = 'datumaangemaakt';
    const UPDATED_AT = 'datumgewijzigd';

    // Relaties definiëren
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patientid');
    }

    public function medewerker()
    {
        return $this->belongsTo(Medewerker::class, 'medewerkerid');
    }

    // Scopes voor veel gebruikte queries
    public function scopeActief($query)
    {
        return $query->where('isactief', 1);
    }

    public function scopeBevestigd($query)
    {
        return $query->where('status', 'Bevestigd');
    }

    public function scopeVandaag($query)
    {
        return $query->whereDate('datum', today());
    }

    // Static method voor overzicht met joins (DB QUERY IN MODEL)
    public static function getAllWithNames()
    {
        // Sorteer van oud naar nieuw (ASC in plaats van DESC)
        return self::where('isactief', 1)
            ->orderBy('datum', 'asc')
            ->orderBy('tijd', 'asc')
            ->paginate(15);
    }

    // Statistieken ophalen (DB QUERY IN MODEL)
    public static function getStats()
    {
        return [
            'totaal' => self::where('isactief', 1)->count(),
            'vandaag' => self::whereDate('datum', today())->where('isactief', 1)->count(),
            'dezeWeek' => self::whereBetween('datum', [now()->startOfWeek(), now()->endOfWeek()])
                ->where('isactief', 1)
                ->count(),
            'geannuleerd' => self::where('status', 'Geannuleerd')->where('isactief', 1)->count(),
        ];
    }

    // Afspraken per dag laatste 7 dagen (DB QUERY IN MODEL)
    public static function getLastSevenDays()
    {
        return DB::table('afspraken')
            ->select(
                DB::raw('DATE(datum) as dag'),
                DB::raw('COUNT(*) as totaal'),
                DB::raw('SUM(CASE WHEN status = "Bevestigd" THEN 1 ELSE 0 END) as bevestigd'),
                DB::raw('SUM(CASE WHEN status = "Geannuleerd" THEN 1 ELSE 0 END) as geannuleerd')
            )
            ->where('datum', '>=', now()->subDays(7))
            ->where('isactief', 1)
            ->groupBy('dag')
            ->orderBy('dag', 'desc')
            ->get();
    }
}
