<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $authenticated = auth()->attempt($credentials);
        if (!$authenticated) {
            return $this->response(false, [], 'Invalid credentials', 401); // 'Invalid credentials
        }
        $user = auth()->user();
        $token = $user->createToken('auth_token')->plainTextToken;
        $data = [
            'token' => $token,
            'user'=> $user
        ];
        return $this->response(true, $data, 'Successful login.');
    }

    public function getUserDetails(Request $request)
    {
        try {
            $user = $request->user();
            $data = array(
                'user' => $user,
            );
            return  $this->response(true, $data, __('messages.login_successfully'));
        } catch (\Throwable $e) {
            return  $this->response(false, [], $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return $this->response(true, [], __('messages.logout_success'));
    }
}
