<?php

namespace App\Repositories;

use App\Models\User;

class AdminRepository
{   
    public function getAllUsersQuery()
    {
        $query = User::select('*');
        return $query;
    }

    public function getAllUsers()
    {
        $users = User::all();
        return $users;
    }
    
    public function getUserById($id)
    {
        $user = User::findOrFail($id); 
        return $user;
    }
}