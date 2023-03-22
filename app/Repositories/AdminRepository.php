<?php

namespace App\Repositories;

use App\Models\User;

class AdminRepository
{   
    public function getAllUsers()
    {
        $users = User::select('*');
        return $users;
    }
    public function getUserById($id)
    {
        $user = User::findOrFail($id); 
        return $user;
    }
}