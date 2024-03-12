<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\Payment\PaypalController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SubcategoryController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FakeCheckout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
//     return $request->user()->only(['name', 'email', 'roleId', 'image']);
//     // return $request->user();
//     // return $request->user()->only(['name', 'email', 'role', 'avatar']);
// });


Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'userDetails']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Auth::routes();

// User
Route::group(['prefix'=>'user'], function(){
    Route::get('/category',[CategoryController::class,'index']);
    Route::get('/products', [ProductController::class,'index']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/{product}', [CartController::class, 'store']);
    Route::put('/cart/{cart}', [CartController::class, 'update']);
    Route::delete('/cart/{product}', [CartController::class, 'destroy']);

    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/{order}', [CheckoutController::class, 'checkoutOrder'])->name('cart.checkout-order');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
    Route::get('/webhook', [CheckoutController::class, 'webhook'])->name('webhook');


    // Paypal
    Route::get('paypal/payment', [PaypalController::class, 'payment'])->name('paypal.payment');
    Route::get('paypal/payment/success', [PaypalController::class, 'paymentSuccess'])->name('paypal.payment.success');
    Route::get('paypal/payment/cancel', [PaypalController::class, 'paymentCancel'])->name('paypal.payment/cancel');

    Route::post('/login', [CustomerController::class, 'customerLogin']);
    Route::post('/fakeCheckout', [FakeCheckout::class,'checkout']);
});


Route::group(['prefix' => 'customer'], function () {
    Route::post('/logout', [CustomerController::class, 'logout']);
    Route::get('/profile', [CustomerController::class, 'customerProfile']);
});

// Route::group(['prefix'=>'customer', 'middleware'=> 'auth:sanctum'], function(){
//     Route::post('/logout', [CustomerController::class, 'logout']);
//     Route::get('/profile', [CustomerController::class, 'customerProfile']);
// });

Route::group(['prefix'=>'admin', 'middleware'=> 'auth:sanctum'],function(){
    Route::resource('/users', UsersController::class);
    Route::post('/users/{user}', [UsersController::class, 'update']);
    Route::patch('/users/roleUpdate/{id}', [UsersController::class,'roleUpdate']);
    Route::get('/roles', [UsersController::class, 'fetchRoles']);

    Route::resource('/category', CategoryController::class);
    Route::post('/category/{category}', [CategoryController::class, 'update']);
    Route::get('/statistics', [CategoryController::class,'dashboardCount']);

    Route::get('/activeCategory', [CategoryController::class, 'activeCategory']);
    Route::get('/getUserCount', [CategoryController::class,'getUserCount']);

    Route::post('/upload-profile-image', [UsersController::class,'uploadImage']);

    Route::resource('/subcategory', SubcategoryController::class);
    Route::post('/subcategory/{subcategory}', [SubcategoryController::class, 'update']);


    Route::resource('/products', ProductController::class);
    Route::post('/products/{product}', [ProductController::class, 'update']);

    Route::get('/categoryWiseSubcategory/{id}', [SubcategoryController::class, 'categoryIdWiseSubcategory']);

    Route::resource('/appointments', AppointmentController::class);
    Route::post('/appointments/{appointment}', [AppointmentController::class, 'update']);
    Route::get('/usersFetch', [AppointmentController::class,'usersFetch']);

    Route::get('/appointment-status', [AppointmentController::class, 'getStatusWithCount']);
});
