<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::create([
            'customer_id' => 1,
            'product_id' => 1,
            'quantity' => 2,
            'buy_price' => 10000.00,
            'total_price' => 20000.00,
            'discount' => 0,
            'final_price' => 20000.00,
            'free_shipping' => false,
        ]);

        Order::create([
            'customer_id' => 2,
            'product_id' => 2,
            'quantity' => 14,
            'buy_price' => 4000.00,
            'total_price' => 56000.00,
            'discount' => 5600.00,
            'final_price' => 50400.00,
            'free_shipping' => true,
        ]);

        Order::create([
            'customer_id' => 1,
            'product_id' => 3,
            'quantity' => 1,
            'buy_price' => 40000.00,
            'total_price' => 40000.00,
            'discount' => 0,
            'final_price' => 40000.00,
            'free_shipping' => false,
        ]);
    }
}
