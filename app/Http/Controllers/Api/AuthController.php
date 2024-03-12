<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsersResources;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(),400, []);
            // dd( $validator->errors()->messages());
            // return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }


        $credentials = filter_var($input['email'], FILTER_VALIDATE_EMAIL)
            ? ['email' => $input['email'], 'password' => $input['password']]
            : ['username' => $input['email'], 'password' => $input['password']];

        if (!Auth::attempt($credentials)) {
            return $this->error('Wrong Credentials', 401, []);
            // return response()->json(['success' => false, 'message' => 'Wrong Credentials'], 401);
        }


        // if (filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        //     Auth::attempt(['email' => $input['email'], 'password' => $input['password']]);
        // } else {
        //     Auth::attempt(['username' => $input['email'], 'password' => $input['password']]);
        // }

        // if (!Auth::check()) {
        //     return response()->json(['success' => false, 'message' => 'Wrong Credientials'],422);
        // }

        $user = User::where('email', Auth::user()->email)->first();
        // $token = $user->createToken('access_token')->plainTextToken;
        $token = auth()->user()->createToken('API Token')->plainTextToken;
        $cookie = cookie('token', $token, 60 * 24); // 1 day

        return $this->success(['user'=> $user, 'access_token'=> $token], 'Successfully Login')->withCookie($cookie);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Successful Login',
        //     'user' => $user,
        //     'access_token' => $token,
        // ], 200)->withCookie($cookie);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }


    public function userDetails(Request $request)
    {
        // $users = new UsersResources(Auth::user());
        // $settings = Setting::first();
        $users = $request->user()->only(['name', 'email', 'roleId', 'image']);
        $settings = Setting::select('sitename', 'logo')->first();
        return response()->json(['users' => $users, 'settings' => $settings]);
    }


    // public function logout(Request $request)
    // {
    //     $accessToken = auth()->user()->token();

    //     $refreshToken = DB::table('oauth_refresh_tokens')
    //     ->where('access_token_id', $accessToken->id)
    //     ->update([
    //         'revoked' => true
    //     ]);

    //     $accessToken->revoke();

    //     $cookie = cookie()->forget('token');
    //     // Auth::user()->tokens()->delete();
    //     // // $request->user()->tokens()->delete();
    //     // $cookie = Cookie::forget('token');

    //     return response()->json(['message' => 'Successfully logged out'])->withCookie($cookie);
    // }


    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->success([], 'Successful Logout');
        // try {
        //     $user = Auth::user();

        //     $user->tokens->each(function (PersonalAccessToken $token) {
        //         $token->delete();
        //     });

        //     // Clear the cookie
        //     $cookie = cookie()->forget('token');

        //     return response()->json(['message' => 'Successfully logged out'])->withCookie($cookie);
        // } catch (\Exception $e) {
        //     return response()->json(['message' => 'Error during logout', 'error' => $e->getMessage()], 500);
        // }
    }

    // public function logout(): JsonResponse
    // {
    //     Auth::user()->tokens()->delete();

    //     $cookie = cookie()->forget('token');

    //     return response()->json(['message' => 'Successfully logged out'])->withCookie($cookie);
    // }
}
