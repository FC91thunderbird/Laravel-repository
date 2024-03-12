<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Cookie;

class CustomerController extends Controller
{
    use ApiResponse;

    function customerLogin(Request $request){
        $input = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    
        if (!Auth::guard()->attempt($input)) {
            return $this->error('Wrong Credentials', 401);
        }
    
        $customer = Auth::guard()->user();
        $customer->tokens()->delete();
    
        // Create a new token
        $token = $customer->createToken('API Token')->plainTextToken;
        $cookie = cookie('token', $token, 60 * 24); // 1 day
    
        return $this->success([ 'customer' => $customer, 'access_token' => $token], 'Successfully Login')->withCookie($cookie);
    }

    function customerProfile(Request $request){
        $customer = Auth::guard('customer')->user();
        return $this->success(['customer' => $customer], 'Customer Profile Retrieved Successfully');
    
        // $customer = $request->user()->only(['first_name', 'last_name', 'email', 'role_id','country','address','address2','city', 'zip', 'phone', 'image']);
    }


    function logout(){
        $customer = Auth::guard('customer')->user();
        $customer->tokens()->delete();
        $cookie = Cookie::forget('token');

        return $this->success([], 'Successfully Logged Out')->withCookie($cookie);

        // return $this->success([], 'Successful Logout');
    }
}
