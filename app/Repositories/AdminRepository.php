<?php

namespace App\Repositories;

use App\Models\User;

class AdminRepository
{   
    public function getAllUsersQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = User::select('*')->where('users.branch_id', $branch_id);
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

    public function getAllUsersCount()
    {
        $usercount = User::count();
        return $usercount;
    }
}