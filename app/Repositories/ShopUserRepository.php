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
        $user = auth()->user();
        return ShopUser::where('branch_id', $user->branch_id)->count();
    }

    public function getShopUsersByShopID($shop_id)
    {
        return ShopUser::where('shop_id', $shop_id)->with('shop')->get();
    }
}