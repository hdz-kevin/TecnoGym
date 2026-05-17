<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSale extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'product_name',
        'product_price',
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

    /**
     * Calculate the subtotal for a given product price and quantity.
     *
     * @param float $productPrice
     * @param int $quantity
     * @return float
     */
    public static function subtotal(float $productPrice, int $quantity): float
    {
        return $productPrice * $quantity;
    }

    /**
     * Calculate the sale total from a collection of cart items.
     * Each item must have a 'subtotal' key.
     *
     * @param array<int, array{subtotal: float}> $items
     * @return float
     */
    public static function total(array $items): float
    {
        return collect($items)->sum('subtotal');
    }
}
