<?php

namespace App\Services;

use App\Models\ShopUser;
use Illuminate\Support\Facades\Hash;

class ShopUserService
{
    public function saveShopUserData($data)
    {
        $user = auth()->user();

        $shop_user = new ShopUser();
        $shop_user->name = $data['name'];
        $shop_user->phone_number = $data['phone_number'];
        $shop_user->email = $data['email'] ?? null;
        $shop_user->password = bcrypt($data['password']);
        $shop_user->device_id = $data['device_id'] ?? null;
        $shop_user->shop_id = $data['shop_id'] ?? null;
        $shop_user->branch_id = $user->branch_id;
        $shop_user->save();
        return $shop_user;
    }

    public function updateShopUserByID($data, $shop_user)
    {
        $shop_user->name = $data['name'];
        $shop_user->phone_number = $data['phone_number'];
        $shop_user->email = $data['email'] ?? $shop_user->email;
        if(isset($data['password'])) {
            $shop_user->password =  bcrypt($data['password']);
        }
        $shop_user->device_id = $data['device_id'] ?? $shop_user->device_id;
        $shop_user->shop_id = $data['shop_id'] ?? $shop_user->shop_id;
        $shop_user->save();
        return $shop_user;
    }

    public function deleteShopUserByID($id)
    {
        ShopUser::destroy($id);
    }

    public function changePassword($shopUser, $old_password, $new_password) 
    {
        $password = $shopUser->password;
        if (Hash::check($old_password, $password)) {
            $shopUser->password = bcrypt($new_password);
            $shopUser->save();
            return true;
        } else {
           return false;
        }
    }

    public function checkPassword($password, $id)
    {
        $shop_user = ShopUser::findOrFail($id);
        return Hash::check($shop_user->password, $password);
    }

    public function updateShopUserData($data, $id)
    {
        $shop_user = ShopUser::findOrFail($id);
        $shop_user->name = $data['name'];
        $shop_user->phone_number = $data['phone_number'];
        $shop_user->email = $data['email'] ?? $shop_user->email;
        if(isset($data['password'])) {
            $shop_user->password =  bcrypt($data['password']);
        }
        $shop_user->device_id = $data['device_id'] ?? $shop_user->device_id;
        $shop_user->shop_id = $data['shop_id'] ?? $shop_user->shop_id;
        $shop_user->save();
        return $shop_user;
    }
}