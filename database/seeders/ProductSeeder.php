<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the products table with 10 realistic gym store products.
     */
    public function run(): void
    {
        $products = collect([
            [
                'name'        => 'Botella de agua 1L',
                'price'       => 10.50,
                'description' => null,
                'stock'       => 40,
            ],
            [
                'name'        => 'Proteína Optimum Gold Standard 500g',
                'price'       => 550,
                'description' => 'Proteína Whey Optimum Gold Standard de 500g sabor chocolate',
                'stock'       => 12,
            ],
            [
                'name'        => 'Proteína MuscleTech NitroTech 1kg',
                'price'       => 980,
                'description' => 'Proteína MuscleTech NitroTech con creatina de 1 kg sabor cookies & cream',
                'stock'       => 10,
            ],
            [
                'name'        => 'Pre entreno C4 Original 30 servicios',
                'price'       => 450,
                'description' => 'Pre-entreno Cellucor C4 Original, 30 servicios, sabor fruit punch.',
                'stock'       => 0,
            ],
            [
                'name'        => 'Scoop de proteína sabor chocolate',
                'price'       => 25,
                'description' => null,
                'stock'       => null,
            ],
            [
                'name'        => 'Scoop de proteína sabor vainilla',
                'price'       => 25,
                'description' => null,
                'stock'       => null,
            ],
            [
                'name'        => 'Creatina Monohidratada 300g',
                'price'       => 320,
                'description' => 'Creatina Monohidratada Micronizada 300g sabor neutro',
                'stock'       => 20,
            ],
            [
                'name'        => 'Bebida energética Monster 473ml',
                'price'       => 40,
                'description' => null,
                'stock'       => 0,
                'is_active'   => false,
            ],
            [
                'name'        => 'Barra proteica',
                'price'       => 50,
                'description' => 'Barra proteica Quest Bar 21 gramos',
                'stock'       => 50,
            ],
            [
                'name'        => 'BCAA Aminoácidos en polvo 200g',
                'price'       => 280,
                'description' => 'Aminoácidos de cadena ramificada (BCAA) en polvo, 200 gramos, sabor sandía',
                'stock'       => 2,
                'is_active'   => false,
            ],
        ]);

        $products->reverse()->each(fn ($product) => Product::create($product));
    }
}
