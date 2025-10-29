<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function getOrCreateCart()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate([
                'user_id' => Auth::id(),
            ]);
        } else {
            $sessionId = Session::getId();
            $cart = Cart::firstOrCreate([
                'session_id' => $sessionId,
            ]);
        }

        return $cart;
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product');

        return view('cart.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.',
            ], 400);
        }

        $cart = $this->getOrCreateCart();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($product->stock < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available.',
                ], 400);
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        $cart->load('items.product');

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cartItem = CartItem::findOrFail($id);
        $cart = $cartItem->cart;
        
        // If quantity is 0, return a special response asking for confirmation
        if ($request->quantity == 0) {
            $cartItem->delete();
            $cart->load('items.product');
            
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
            ]);
        }

        $product = $cartItem->product;

        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.',
            ], 400);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $cart->load('items.product');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully.',
        ]);
    }

    public function destroy(string $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        $cart = $cartItem->cart;
        $cart->load('items.product');

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart.',
        ]);
    }

    public function count()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items');
        return response()->json([
            'count' => $cart->getTotalItems(),
        ]);
    }

    public function clearCart()
    {
        $cart = $this->getOrCreateCart();
        
        $cart->items()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
        ]);
    }
}

