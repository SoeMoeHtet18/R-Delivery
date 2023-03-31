<?php

namespace App\Repositories;

use App\Models\ShopUser;

class ShopUserRepository
{
    public function getAllShopUsersQuery()
    {
        $query = ShopUser::select('*');
        return $query;
    }

    public function getShopUserByID($id)
    {
        $shop_user = ShopUser::findOrFail($id);
        return $shop_user;
    }

    public function getAllShopUserCount()
    {
        $shopusercount = ShopUser::count();
        return $shopusercount;
    }
}