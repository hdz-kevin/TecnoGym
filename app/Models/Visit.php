<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'visit_at',
        'price',
    ];

    protected $casts = [
        'visit_at' => 'datetime',
    ];

    /**
     * Get the formatted visit at attribute.
     *
     * @return string
     */
    public function getFormattedVisitAtAttribute()
    {
        $formatted = Carbon::parse($this->visit_at)
                           ->locale('es')
                           ->translatedFormat('l j F, h:i a');

        return ucfirst($formatted);
    }
}
