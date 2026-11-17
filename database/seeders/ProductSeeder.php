<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['store_id' => 'mauteh_kanal', 'name' => 'Es Teh Manis', 'price' => 5000],
            ['store_id' => 'mauteh_kanal', 'name' => 'Es Kampul', 'price' => 7000],
            ['store_id' => 'mauteh_kanal', 'name' => 'Choco Taro', 'price' => 12000],
            // Tambahkan sisa menu Anda di sini...
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}