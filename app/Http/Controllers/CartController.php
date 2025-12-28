<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;



class CartController extends Controller
{
      public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $productId = $request->product_id;
        $quantity  = $request->quantity;

        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id'    => Auth::id(),
                'product_id' => $productId,
                'quantity'   => $quantity
            ]);
        }
        return response()->json(['message' => 'تمت إضافة المنتج للسلة بنجاح']);
    }




     public function showCart()
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

        return response()->json([
            'cart' => $cartItems->map(function ($item) {
                return [
                    'id'       => $item->id,
                    'product'  => $item->product->name,
                    'price'    => $item->product->price,
                    'quantity' => $item->quantity,
                    'total'    => $item->product->price * $item->quantity
                ];
            })
        ]);
    }

}
