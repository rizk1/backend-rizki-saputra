<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'merchant_id' => 1,
            'name' => 'Sampo Pantine',
            'price' => 10000.00,
        ]);

        Product::create([
            'merchant_id' => 1,
            'name' => 'Sabun Detol',
            'price' => 4000.00,
        ]);

        Product::create([
            'merchant_id' => 2,
            'name' => 'Kursi Kayu',
            'price' => 40000.00,
        ]);
    }
}
