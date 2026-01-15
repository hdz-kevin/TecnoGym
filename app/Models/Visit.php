<?php

namespace App\Models;

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

    /**
     * Get the visit type associated with the visit.
     */
    public function visitType(): BelongsTo
    {
        return $this->belongsTo(VisitType::class);
    }
}
