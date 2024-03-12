<?php

namespace App\Healper;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CartData{

    public static function getCartItemsCount():int {
        $request = \request();
        $user = $request->user();
        if ($user) {
            return Cart::where('user_id', $user->id)->sum('quantity');
        } else {
            $cartItems = self::getCookieCartItems();

            return array_reduce(
                $cartItems,
                fn($carry, $item) => $carry + $item['quantity'],
                0
            );
        }
    }

    public static function getCookieCartItems()
    {
        $request = \request();
        return json_decode($request->cookie('cart_items', '[]'), true);
    }

    public static function getCountFromItems($cartItems)
    {
        return array_reduce(
            $cartItems,
            fn($carry, $item) => $carry + $item['quantity'],
            0
        );
    }

    // public static function getProductsAndCartItems(): array
    // {
    //     $cartItems = self::getCartItems();
    //     $ids = Arr::pluck($cartItems, 'product_id');
    //     $products = Product::query()->whereIn('id', $ids)->get();

    //     if(Auth::guard('customer')->user()){
    //         $cartItems = Arr::keyBy($cartItems, 'product_id');
    //     }else{
    //         $cartItems = array_map(function ($item) use ($products) {
    //             $productInfo = collect($products)->firstWhere('id', $item['product_id']);
    //             return [
    //                 'id' => $productInfo['id'],
    //                 'user_id' => $productInfo['user_id'],
    //                 'title' => $productInfo['title'],
    //                 'category' => $productInfo['category'],
    //                 'subcategory' => $productInfo['subcategory'],
    //                 'slug' => $productInfo['slug'],
    //                 'description' => $productInfo['description'],
    //                 'price' => $productInfo['price'],
    //                 'image' => $productInfo['image'],
    //                 'quantity' => $item['quantity'],
    //             ];
    //         }, $cartItems);
    //         $cartItems = collect($cartItems)->keyBy('id')->toArray();
    //     }

    //     return [$products, $cartItems];
    // }


    public static function getProductsAndCartItems():array {
        $cartItems = self::getCartItems();
        $ids = Arr::pluck($cartItems, 'product_id');
        $products = Product::query()->whereIn('id', $ids)->get();
        $cartItems = Arr::keyBy($cartItems, 'product_id');

        $mergedArray = $products->map(function ($product) use ($cartItems) {
            return array_merge($product->toArray(), $cartItems[$product->id]);
        });

        return [$mergedArray];
    }

    // public static function getProductsAndCartItems(): array
    // {
    //     $cartItems = self::getCartItems();
    //     $ids = Arr::pluck($cartItems, 'product_id');
    //     $products = Product::query()->whereIn('id', $ids)->get();


    //     $cartItems = array_map(function ($item) use ($products) {
    //         $productInfo = $products->firstWhere('id', $item['product_id']);
    //         return [
    //             'product_id' => $item['product_id'],
    //             'user_id' => $item['user_id'],
    //             'title' => $item['title'],
    //             'category' => $item['category'],
    //             'subcategory' => $item['subcategory'],
    //             'slug' => $item['slug'],
    //             'description' => $item['description'],
    //             'price' => $item['price'],
    //             'image' => $item['image'],
    //             'quantity' => $item['quantity'],
    //             'product_info' => $productInfo,
    //         ];
    //     }, $cartItems);

    //     return [$products, $cartItems];
    // }




    public static function getCartItems()
    {
        $request = \request();
        $user = Auth::guard('customer')->user();
        if ($user) {
            return Cart::where('user_id', $user->id)->get()->map(
                fn($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]
            );
        } else {
            return self::getCookieCartItems();
        }
    }

}