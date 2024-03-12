<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['Admin', 'editer', 'user'];

        foreach($roles as $item){
            $role = new Role();
            $role->name = $item;
            $role->save();
        }

        $users = [
            ['name'=> 'admin', 'username'=> 'admin', 'email'=> 'admin@gmail.com', 'password'=>'password', 'role_id'=> 1],
            ['name'=> 'Editer', 'username'=> 'Editer', 'email'=> 'Editer@gmail.com', 'password'=>'password', 'role_id'=> 2],
            ['name'=> 'user', 'username'=> 'User', 'email'=> 'user@gmail.com', 'password'=>'password', 'role_id'=> 3],
        ];

        foreach($users as $user){
            User::create($user);
        }
    }
}
