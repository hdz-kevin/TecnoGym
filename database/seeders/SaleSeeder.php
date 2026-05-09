<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Seed the sales and product_sales tables
     */
    public function run(): void
    {
        $salesToCreate = 25;
        $products = Product::all();

        for ($i = 0; $i < $salesToCreate; $i++) {
            /** @var Sale $sale */
            $sale = Sale::create([
                'total'   => 0,
                'sold_at' => Carbon::now()->subDays(rand(0, 10)),
            ]);

            $randomProducts = $products->random(rand(1, 3));

            foreach ($randomProducts as $product) {
                $quantity = rand(1, 3);

                ProductSale::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'unit_price' => $product->price,
                    'subtotal'   => $product->price * $quantity,
                ]);
            }

            $sale->update([
                'total' => $sale->productSales()->sum('subtotal'),
            ]);
        }

        for ($i = 0; $i < 10; $i++) {
            /** @var Sale $sale */
            $sale = Sale::create([
                'total'   => 0,
                'sold_at' => Carbon::now(),
            ]);

            $randomProducts = $products->random(rand(1, 3));

            foreach ($randomProducts as $product) {
                $quantity = rand(1, 3);

                ProductSale::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'unit_price' => $product->price,
                    'subtotal'   => $product->price * $quantity,
                ]);
            }

            $sale->update([
                'total' => $sale->productSales()->sum('subtotal'),
            ]);
        }
    }
}
