<?php
namespace App\Services;

use App\Models\User;

class UserService
{
    public function __construct()
    {
    }

    // register
    public function register($data)
    {
        // 密碼加密
        $data['password'] = bcrypt($data['password']);
        return User::create($data);
    }

    public function getUserList()
    {
        return User::all();
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function createUser($data)
    {
        return User::create($data);
    }

    public function updateUser($id, $data)
    {
        $user = User::find($id);
        $user->update($data);
        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return $user;
    }
}
