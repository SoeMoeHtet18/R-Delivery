<?php

namespace App\Services;

use App\Models\ShopUser;

class ShopUserService
{
    public function saveShopUserData($data)
    {
        $shop_user = new ShopUser();
        $shop_user->name = $data['name'];
        $shop_user->phone_number = $data['phone_number'];
        $shop_user->email = $data['email'] ?? null;
        $shop_user->password = $data['password'];
        $shop_user->device_id = $data['device_id'] ?? null;
        $shop_user->shop_id = $data['shop_id'];
        $shop_user->save();
    }

    public function updateShopUserByID($data, $shop_user)
    {
        $shop_user->name = $data['name'];
        $shop_user->phone_number = $data['phone_number'];
        $shop_user->email = $data['email'] ?? $shop_user->email;
        if($data['password']) {
            $shop_user->password = bcrypt($data['password']);
        }
        $shop_user->device_id = $data['device_id'] ?? $shop_user->device_id;
        $shop_user->shop_id = $data['shop_id'];
        $shop_user->save();
    }

    public function deleteShopUserByID($id)
    {
        ShopUser::destroy($id);
    }
}