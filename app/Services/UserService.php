<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getUsers()
    {
        $users = User::get();
    
        return $users;
    }

    public function getUserById($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return ['messages' => 'User not found'];
        }

        return $user;
    }

    public function createNewUser($request)
    {
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'email' => $request->email,
            'role' => $request->role
        ];

        try {
            $createUser = User::create($data);
        } catch (\Exception $e) {
            \Log::error('Error create user' . $e);
            return ['message' => 'Error creating user. Please try again later.'];
        }
        
        return $createUser;
    }

    public function updateUser($request, $id)
    {
        $user = User::find($id);

        $data = [
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'role' => $request->role ? $request->role : $user->role
        ];

        if (!$user) {
            return 'User not found';
        }
        $updateUser = $user->update($data);
        
        return 'Successfully update user ';
    }

    public function deleteUser($user, $id)
    {
        $deleteUser = User::find($id);
        if (!$deleteUser) {
            return 'User not found';
        }

        if ($user->id == $deleteUser->id) {
            return 'Users are not allow to delete himself/herself.';
        }
        $deleteUser->delete($id);

        return 'Successfully deleted user.';
    }
}