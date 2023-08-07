<?php

namespace App\Repositories;

use App\Models\ShopUser;

class ShopUserRepository
{
    public function getAllShopUsersQuery()
    {
        $branch_id = auth()->user()->branch_id;
        $query = ShopUser::select('*')->where('branch_id', $branch_id);
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