<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['total', 'sold_at'];

    /**
     * Get the individual product sales/details for the sale.
     */
    public function productSales()
    {
        return $this->hasMany(ProductSale::class);
    }

    /**
     * Get the products included in the sale.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'unit_price', 'subtotal');
    }

    /**
     * Get the formatted sold_at date attribute.
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        $formatted = Carbon::parse($this->sold_at)
                           ->locale('es')
                           ->translatedFormat('l j \d\e F Y');

        return ucfirst($formatted);
    }

    /**
     * Get the formatted sold_at time attribute.
     *
     * @return string
     */
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->sold_at)->format('h:i A');
    }
}
