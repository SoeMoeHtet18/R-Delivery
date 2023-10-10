<?php

namespace App\Repositories;

use App\Models\User;

class AdminRepository
{   
    public function getAllUsersQuery()
    {
        $branch_id = auth()->user()->branch_id;
        if(auth()->user()->id == 1) {
            $query = User::select('*');
        } else {
            $query = User::select('*')->where('users.branch_id', $branch_id);
        }
        
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
        $user = auth()->user();
        return User::where('branch_id', $user->branch_id)->count();
    }

    public function getAllAdminData($search) {
        $branch_id = auth()->user()->branch_id;
        if(auth()->user()->id == 1) {
            $query = User::select('*');
        } else {
            $query = User::select('*')->where('users.branch_id', $branch_id);
        }
        if(isset($search)) {
            $query = $query->where('name','like', '%' . $search . '%')
                        ->orWhere('email','like', '%' . $search . '%')
                        ->orWhere('phone_number','like', '%' . $search . '%');
        }
        return $query->orderBy('id','desc')->get();
    }
}