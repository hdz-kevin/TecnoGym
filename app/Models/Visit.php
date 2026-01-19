<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_type_id',
        'visit_at',
        'price_paid',
    ];

    protected $casts = [
        'visit_at' => 'datetime',
    ];

    /**
     * Get the visit type associated with the visit.
     */
    public function visitType(): BelongsTo
    {
        return $this->belongsTo(VisitType::class);
    }

    /**
     * Get the formatted visit at attribute.
     *
     * @return string
     */
    public function getFormattedVisitAtAttribute()
    {
        $formatted = Carbon::parse($this->visit_at)
                           ->locale('es')
                           ->translatedFormat('D d F Y - h:i a');

        return ucfirst($formatted);
    }
}
