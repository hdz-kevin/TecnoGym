<?php

namespace App\Models;

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
}
