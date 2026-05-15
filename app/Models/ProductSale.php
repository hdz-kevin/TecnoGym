<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'product_price',
        'product_name',
        'quantity',
        'subtotal',
    ];

    /**
     * Get the sale that owns the product sale detail.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the product associated with the sale detail.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
