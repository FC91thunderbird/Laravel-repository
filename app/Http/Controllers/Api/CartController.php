<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Cookie;
use App\Healper\CartData;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use ApiResponse;

    public function index()
    {
        [$cartItems] = CartData::getProductsAndCartItems();
        $total = 0;

     
        foreach ($cartItems as $product) {
            $total += $product['price'] * $product['quantity'];
        }
        

        // foreach ($products as $product) {
        //     $total += $product->price * $cartItems[$product->id]['quantity'];
        // }

        return $this->success(['cartItems' => $cartItems, 'total' => $total], "Cart Data Fetched");
    }


    public function store(Request $request, Product $product)
    {
        $user = Auth::guard('customer')->user();
        $quantity = 1;

        if ($user) {
            $cartItem = Cart::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->update();
                // $totalQuantity = $cartItem->quantity + $quantity;
            } else {
                $data = [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ];

                Cart::create($data);
                // $totalQuantity = $quantity;
            }
            return $this->success(['count' => CartData::getCartItemsCount()], "Item Added to cart First");
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            $productFound = false;
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] += $quantity;
                    // $totalQuantity = $item['quantity'] + $quantity;
                    $productFound = true;
                    break;
                }
            }
            if (!$productFound) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price
                ];
                // $totalQuantity = $quantity;
            }

            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);
            return $this->success(['count' => CartData::getCountFromItems($cartItems)], 'Item Added to cart second');
        }



        // if ($product->quantity !== null && $product->quantity < $totalQuantity) {
        //     return response([
        //         'message' => match ( $product->quantity ) {
        //             0 => 'The product is out of stock',
        //             1 => 'There is only one item left',
        //             default => 'There are only ' . $product->quantity . ' items left'
        //         }
        //     ], 422);
        // }
        
    }

    function update(Request $request, Product $cart)
    {
        $quantity = (int)$request->post('quantity');
        $user = Auth::guard('customer')->user();

        // if ($product->quantity !== null && $product->quantity < $quantity) {
        //     return response([
        //         'message' => match ( $product->quantity ) {
        //             0 => 'The product is out of stock',
        //             1 => 'There is only one item left',
        //             default => 'There are only ' . $product->quantity . ' items left'
        //         }
        //     ], 422);
        // }

        if ($user) {
            Cart::where(['user_id' => $user->id, 'product_id' => $cart->id])->update(['quantity' => $quantity]);

            return $this->success(['count' => CartData::getCartItemsCount()], 'Cart Update');
            // return response([ 'count' => CartData::getCartItemsCount(), ]);
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $cart->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);
            return $this->success(['count' => CartData::getCountFromItems($cartItems)], 'Cart Update');
            // return response(['count' => CartData::getCountFromItems($cartItems)]);
        }
    }


    public function destroy(Request $request, Product $product)
    {
        // dd($request->route()->parameters());
        $user = Auth::guard('customer')->user();
        if ($user) {
            $cartItem = Cart::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->delete();
            }

            return $this->success(['count' => CartData::getCartItemsCount()], 'Item Removed');
            // return response([ 'count' => Cart::getCartItemsCount(), ]);
        } else {
            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);

            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return $this->success(['count' => CartData::getCountFromItems($cartItems)], 'Item Removed Form Cart');
            // return response(['count' => Cart::getCountFromItems($cartItems)]);
        }
    }
}
