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
     * Get the formatted date attribute.
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        $formatted = Carbon::parse($this->visit_at)
                           ->locale('es')
                           ->translatedFormat('l j \d\e F Y');

        return ucfirst($formatted);
    }

    /**
     * Get the formatted time attribute.
     *
     * @return string
     */
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->visit_at)->format('h:i A');
    }

}
