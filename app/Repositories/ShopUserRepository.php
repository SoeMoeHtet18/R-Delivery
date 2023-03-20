<?php

namespace App\Repositories;

use App\Models\ShopUser;

class ShopUserRepository
{
    public function getAllShopUsersByDESC()
    {
        $shop_users = ShopUser::all();
        return $shop_users;
    }

    public function getShopUserByID($id)
    {
        $shop_user = ShopUser::findOrFail($id);
        return $shop_user;
    }
}