<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAdd;
use App\Http\Requests\UserUpdate;
use App\Http\Resources\UsersResources;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function index()
    {
        $users = Cache::remember('users', 60, function () {
            return User::with('roles')->orderBy('id', 'desc')->paginate(10);
        });
        Cache::put('users', $users, 3600);
        return response()->json(UsersResources::collection($users));
    }

    function fetchRoles(){
        $roles = Role::orderBy('name', 'asc')->get();
        return response()->json($roles);
    }


    public function store(UserAdd $request)
    {
        try {
            $input = $request->validated();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $ext = $image->extension();
                $file = time().'.'.$ext;
                $image->storeAs('public/users', $file);
                $input['image'] = $file;
            }

            $store = User::create($input);
            if(!$store){
                return response()->json(['success' => false, 'message' => 'User Not Created..']);
            }
            Cache::forget('users');
            return response()->json(['success' => true, 'message' => 'User added successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error adding user']);
        }
    }

    
    public function show(User $user)
    {
        return response()->json($user);
    }
    
    public function update(UserUpdate $request, User $user)
    {
        try {
            $input = $request->validated();

           if ($request->hasFile('image')) {
                $image = $request->file('image');
                $ext = $image->extension();
                $file = time().'.'.$ext;
                $image->storeAs('public/users', $file);
                $input['image'] = $file;
            }
          
            $update = $user->update($input);
            if(!$update){
                return response()->json(['success' => false, 'message' => 'User Not Updated..']);
            }
            Cache::forget('users');
            return response()->json(['success' => true, 'message' => 'User Update successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error Update User..']);
        }
    }

    function roleUpdate(User $user){
       $updateRole = $user->update(['roleId'=> request('role')]);
        if(!$updateRole){
            return response()->json(['success' => false, 'message' => 'Role Not Updated..'], 500);
        }
        Cache::forget('users');
        return response()->json(['success' => true, 'message' => 'Role Update successfully'], 200);
    }


    public function destroy(User $user)
    {
        $user->delete();
        Cache::forget('users');
        return response()->json(['success' => true, 'message' => 'User Deleted successfully'],200);
    }

    public function uploadImage(Request $request){
        if ($request->hasFile('profile_picture')) {
            $previousPath = $request->user()->getRawOriginal('image');
            $image = $request->file('profile_picture');
            $ext = $image->extension();
            $file = time().'.'.$ext;
            $image->storeAs('public/users', $file);

            $request->user()->update(['image' => $file]);

            Storage::delete('/public/users/'.$previousPath);

            return response()->json(['message' => 'Profile picture uploaded successfully!']);
        }
    }
}
