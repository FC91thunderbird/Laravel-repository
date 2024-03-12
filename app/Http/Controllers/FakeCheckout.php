<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Healper\CartData;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Cookie;

class FakeCheckout extends Controller
{
    use ApiResponse;

    public function checkout(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        if ($customer) {
            [$cartItems] = CartData::getProductsAndCartItems();
            $orderItems = [];
            $lineItems = [];
            $totalPrice = 0;

            DB::beginTransaction();

            foreach ($cartItems as $product) {
                $quantity = $product['quantity'];
                $totalPrice += $product['price'] * $quantity;
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'inr',
                        'product_data' => [
                            'name' => $product['title'],
                            'images' => $product['image'] ? [$product['image']] : []
                        ],
                        'unit_amount' => $product['price'] * 100,
                    ],
                    'quantity' => $quantity,
                ];
                $orderItems[] = [
                    'product_id' => $product['id'],
                    'quantity' => $quantity,
                    'unit_price' => $product['price']
                ];

                if ($product['quantity'] !== null) {
                    $updateData = Product::find($product['id']);
                    $updateData->quantity -= $quantity;
                    $updateData->update(['quantity' => $updateData->quantity]);
                }
            }

            try {
                $orderData = [
                    'total_price' => $totalPrice,
                    'status' => OrderStatus::Unpaid,
                    'user_id' => $customer->id,
                ];
                $order = Order::create($orderData);

                // Create Order Items
                foreach ($orderItems as $orderItem) {
                    $orderItem['order_id'] = $order->id;
                    OrderItem::create($orderItem);
                }

                // Create Payment
                $paymentData = [
                    'order_id' => $order->id,
                    'amount' => $totalPrice,
                    'status' => PaymentStatus::Pending,
                    'type' => 'cc',
                    'created_by' => $customer->id,
                    'updated_by' => $customer->id,
                    // 'session_id' => $session->id
                ];
                Payment::create($paymentData);
            } catch (\Exception $e) {
                DB::rollBack();

                Log::critical(__METHOD__ . ' method does not work. ' . $e->getMessage());
                return response()->json('Error Shows', $e);
            }
            DB::commit();
            Cart::where(['user_id' => $customer->id])->delete();

            $cartItems = json_decode($request->cookie('cart_items', '[]'), true);

            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cookie::queue('cart_items', json_encode($cartItems), 60 * 24 * 30);

            return $this->success([], 'Order Created');
        } else {
            $user = new Customer();
            $user->first_name =  $request->first_name;
            $user->last_name = $request->last_name;
            $user->country = $request->country;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->zip = $request->zip;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->password = 'password';
            $user->role_id = 4;
            $user->save();
        }
    }
}
