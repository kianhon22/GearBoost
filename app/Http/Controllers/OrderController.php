<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view orders.');
        }

        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to place an order.',
                'redirect' => route('login'),
            ], 401);
        }

        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 400);
        }

        // Get selected items or all items if none specified
        $selectedItemIds = $request->input('selected_items', []);
        
        if (empty($selectedItemIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select items to checkout.',
            ], 400);
        }

        // Filter cart items to only selected ones
        $itemsToOrder = $cart->items->whereIn('id', $selectedItemIds);

        if ($itemsToOrder->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No valid items selected.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Check stock availability
            foreach ($itemsToOrder as $item) {
                if ($item->product->stock < $item->quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Not enough stock for {$item->product->name}.",
                    ], 400);
                }
            }

            // Calculate total amount for selected items
            $totalAmount = $itemsToOrder->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Create order items and update stock
            foreach ($itemsToOrder as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $product = $item->product;
                $product->stock -= $item->quantity;
                $product->save();
                
                // Remove ordered item from cart
                $item->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'redirect' => route('orders.show', $order),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.',
            ], 500);
        }
    }

    public function update(Request $request, Order $order)
    {
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully.',
        ]);
    }
}

