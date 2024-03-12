<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Healper\CartData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enums\PaymentStatus;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        // $user = new Customer();
        // $user->first_name =  $request->first_name;
        // $user->last_name = $request->last_name;
        // $user->country = $request->country;
        // $user->address = $request->address;
        // $user->city = $request->city;
        // $user->zip = $request->zip;
        // $user->phone = $request->phone;
        // $user->email = $request->email;
        // $user->password = 'password';
        // $user->role_id = 4;
        // $user->save();

        // $user = $request->user();

        // $customer = $user->customer;
        // if (!$customer->billingAddress || !$customer->shippingAddress) {
        //     return redirect()->route('profile')->with('error', 'Please provide your address details first.');
        // }

        $user = $request->user();

        // dd($user);
        // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        // $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        [$cartItems] = CartData::getProductsAndCartItems();
        $orderItems = [];
        $lineItems = [];
        $totalPrice = 0;

        DB::beginTransaction();

        // foreach ($products as $product) {
        //     $quantity = $cartItems[$product->id]['quantity'];
        //     if ($product->quantity !== null && $product->quantity < $quantity) {
        //         $message = match ($product->quantity) {
        //             0 => 'The product "' . $product->title . '" is out of stock',
        //             1 => 'There is only one item left for product "' . $product->title,
        //             default => 'There are only ' . $product->quantity . ' items left for product "' . $product->title,
        //         };
        //         return redirect()->back()->with('error', $message);
        //     }
        // }


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
                // 'description' => $product['description'], // Add a description if needed
                // 'shipping' => [
                //     'name' => $request->first_name. " " .$request->last_name,
                //     'address' => [
                //         'line1' => $request->address,
                //     ],
                // ],

            ];

            // $customer = \Stripe\Customer::create(array(
            //     'name' => $request->first_name. " ". $request->last_name,
            //     'description' => 'test description',
            //     'email' => $request->email,
            //     "address" => ["city" => $request->city, "country" => $request->country, "line1" => $request->address, "line2" => "", "postal_code" => $request->zip, "state" => $request->state ? $request->state : '']
            // ));

            $orderItems[] = [
                'product_id' => $product['id'],
                'quantity' => $quantity,
                'unit_price' => $product['price']
            ];

            if ($quantity !== null) {
                $this->decreaseQuantities($product['id'], $quantity);
                // $product['quantity'] -= $quantity;
                // $product->update(['quantity'=>$product['quantity'] ]);
            }
        }


        $session = $stripe->checkout->sessions->create([
            'line_items' =>  $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        // $session = \Stripe\Checkout\Session::create([
        //     // 'payment_method_types' => ['card', 'upi'],
        //     'line_items' => $lineItems,
        //     'customer' => $customer->id, 
        //     'mode' => 'payment',
        //     // 'customer_creation' => 'always',
        //     'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
        //     'cancel_url' => route('checkout.failure', [], true),
        // ]);

        try {
            // Create Order
            $orderData = [
                'total_price' => $totalPrice,
                'status' => OrderStatus::Unpaid,
                'user_id' => $user->id, //$user->id
                // 'product_id' => '1', //$product->id
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
                'created_by' => $user->id, // $user->id
                'updated_by' => $user->id,  // $user->id
                'session_id' => $session->id
            ];
            Payment::create($paymentData);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::critical(__METHOD__ . ' method does not work. ' . $e->getMessage());
            throw $e;
        }

        DB::commit();
        // Cart::where(['user_id' => $user->id])->delete();

        return response()->json([
            'stripeUrl' => $session->url,
            'session_id' => $session->id
        ]);
        // return redirect($session->url);
    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionId = $request->get('session_id');

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if (!$session) {
                throw new NotFoundHttpException;
            }

            // $session = \Stripe\Checkout\Session::retrieve($sessionId);
            // if (!$session) {
            //     throw new NotFoundHttpException();
            //     // return response()->json(['success'=> false, 'message'=> 'Session not found']);
            //     // return view('checkout.failure', ['message' => 'Invalid Session ID']);
            // }

            $payment = Payment::query()
                ->where(['session_id' => $sessionId])
                // ->whereIn('status', 'pending')
                ->first();
            if (!$payment) {
                throw new NotFoundHttpException();
            }
            if ($payment->status === PaymentStatus::Pending->value) {
                $this->updateOrderAndSession($payment);
            }
            // $customer = \Stripe\Customer::retrieve($session->customer);

            return response()->json(['success' => true, 'message' => 'Payment Success']);

            // return view('checkout.success', compact('customer'));
        } catch (NotFoundHttpException $e) {
            return response()->json(['success' => false, 'message' => 'Throw error', 'error' => $e]);
            // throw $e;
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Payment exception Failed', 'error' => $e]);
            // return view('checkout.failure', ['message' => $e->getMessage()]);
        }
    }

    public function failure(Request $request)
    {
        return response()->json(['success' => false, 'message' => 'Payment Failed', 'error' => $request]);
        // return view('checkout.failure', ['message' => ""]);
    }

    public function checkoutOrder(Order $order, Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => $item->product->title,
                        // 'images' => [$product->image]
                    ],
                    'unit_amount' => $item->unit_price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure', [], true),
        ]);

        $order->payment->session_id = $session->id;
        $order->payment->save();

        return response()->json([
            'url' => $session->url
        ]);

        // return redirect($session->url);
    }

    public function webhook()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $endpoint_secret = env('WEBHOOK_SECRET_KEY');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 401);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 402);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $paymentIntent = $event->data->object;
                $sessionId = $paymentIntent['id'];

                $payment = Payment::query()
                    ->where(['session_id' => $sessionId, 'status' => PaymentStatus::Pending])
                    ->first();
                if ($payment) {
                    $this->updateOrderAndSession($payment);
                }
                // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('', 200);
    }

    private function updateOrderAndSession(Payment $payment)
    {
        DB::beginTransaction();
        try {
            $payment->status = 'paid'; // PaymentStatus::Paid->value;
            $payment->update();

            $order = $payment->order;

            $order->status = 'paid'; // OrderStatus::Paid->value;
            $order->update();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical(__METHOD__ . ' method does not work. ' . $e->getMessage());
            throw $e;
        }

        DB::commit();

        // try {
        //     $adminUsers = User::where('is_admin', 1)->get();

        //     foreach ([...$adminUsers, $order->user] as $user) {
        //         Mail::to($user)->send(new NewOrderEmail($order, (bool)$user->is_admin));
        //     }
        // } catch (\Exception $e) {
        //     Log::critical('Email sending does not work. ' . $e->getMessage());
        // }
    }

    protected function decreaseQuantities($productId, $quantity)
    {
        $product = Product::find($productId);
        $product->update(['quantity' => $product->quantity - $quantity]);
    }
}
