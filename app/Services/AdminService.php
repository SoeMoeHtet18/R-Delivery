<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AdminRepository;

class AdminService
{
    public function saveAdminData($data)
    {
        $user = new User();
        $user->name = $data['name'];
        $user->phone_number = $data['phone_number'];
        $user->email = $data['email'] ?? null;
        $user->password = $data['password'] ? bcrypt($data['password']): null;
        $user->device_id = $data['device_id'] ?? null;
        $branch_id = auth()->user()->branch_id;
        if(auth()->user()->id == 1){
            $user->branch_id = $data['branch_id'] ?? null;
        } else {
            $user->branch_id = $branch_id;
        }
        $user->save();
    }

    public function updateAdminData($data, $user)
    {
        $user->name = $data['name'];
        $user->phone_number = $data['phone_number'];
        $user->email = $data['email'] ?? null;
        if($data['password']) {
            $user->password = bcrypt($data['password']);
        }
        $user->device_id = $data['device_id'] ?? $user->device_id;
        $branch_id = auth()->user()->branch_id;
        if(auth()->user()->id == 1){
            $user->branch_id = $data['branch_id'] ?? null;
        } else {
            $user->branch_id = $branch_id;
        }
        $user->save();
    }

    public function deleteUserByID($id)
    {
        User::destroy($id);
    }
}