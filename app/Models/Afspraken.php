<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    const CREATED_AT = 'datumaangemaakt';
    const UPDATED_AT = 'datumgewijzigd';

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patientid');
    }

    public function medewerker()
    {
        return $this->belongsTo(Medewerker::class, 'medewerkerid');
    }

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

    public static function getAllWithNames()
    {
        return self::where('isactief', 1)
            ->orderBy('datum', 'asc')
            ->orderBy('tijd', 'asc')
            ->paginate(15);
    }

    public static function getStats()
    {
        return [
            'totaal' => self::where('isactief', 1)->count(),
            'vandaag' => self::actief()->vandaag()->count(),
            'dezeWeek' => self::actief()
                ->whereBetween('datum', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'geannuleerd' => self::where('status', 'Geannuleerd')
                ->where('isactief', 1)
                ->count(),
        ];
    }

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
