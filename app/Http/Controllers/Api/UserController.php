<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserAdd;
use App\Http\Requests\User\UserUpdate;
use App\Http\Resources\User\UserListResource;
use App\Http\Resources\User\UserResource;
use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    // protected $userRepository;

    // public function __construct()
    // {
    //     $this->userRepository = new UserRepository();
    // }
    
    public function index(Request $request)
    {
        $users = $this->userRepository->getList($request);

        if (empty($users)) {
            return $this->response(false, [], 'users not found', 200);
        }
        
        $data = [
            'users' => new UserListResource($users),
        ];
        return $this->response(true, $data, 'users', 200);
    }
    

    public function store(UserAdd $request)
    {
        $user = $this->userRepository->store($request->validated());
        if (empty($user)) {
            return $this->response(false, [], 'Failed to create user', 200);
        }
        $data = [
            'user' => $user,
        ];
        return $this->response(true, $data, 'user', 200);
    }
   
    public function show(string $id)
    {
        $user = $this->userRepository->getById($id);
        if (empty($user)) {
            return $this->response(false, [], 'user not found', 200);
        }
        $data = [
            'user' => new UserResource($user),
        ];
        return $this->response(true, $data, 'user', 200);
    }


    public function update(UserUpdate $request, string $id)
    {
        $user = $this->userRepository->update($id, $request->validated());
        if (empty($user)) {
            return $this->response(false, [], 'Faied to update user', 200);
        }
        $data = [
            'user' => $user,
        ];
        return $this->response(true, $data, 'user', 200);
    }

    
    public function destroy(string $id)
    {
        $user = $this->userRepository->delete($id);
        if (empty($user)) {
            return $this->response(false, [], 'Failed to delete user', 200);
        }
        $data = [
            'user' => $user,
        ];
        return $this->response(true, $data, 'user', 200);
    }

    function getRoles(){
        $roles =  Role::select('id', 'name')->get();
        return $this->response(true, $roles, 'Roles fetch', 200);
    }
}
