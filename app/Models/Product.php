<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'stock',
        'is_active',
    ];

    /**
     * Get the sales details associated with the product.
     */
    public function productSales()
    {
        return $this->hasMany(ProductSale::class);
    }
}
