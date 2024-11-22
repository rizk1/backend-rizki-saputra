<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function list()
    {
        $orders = Order::with('product')
            ->where('customer_id', auth()->id())
            ->orWhereHas('product', function ($query) {
                $query->where('merchant_id', auth()->id());
            })
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully',
            'data' => $orders
        ]);
    }

    public function checkout(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $totalPrice = $product->price * $request->quantity;

        $discount = 0;
        $freeShipping = false;

        if ($totalPrice >= 50000) {
            $discount = $totalPrice * 0.10;
        }

        if ($totalPrice > 15000) {
            $freeShipping = true;
        }

        $finalPrice = $totalPrice - $discount;

        $order = Order::create([
            'customer_id' => auth()->id(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'buy_price' => $product->price,
            'total_price' => $totalPrice,
            'discount' => $discount,
            'final_price' => $finalPrice,
            'free_shipping' => $freeShipping,
        ]);

        return response()->json($order, 201);
    }
} 