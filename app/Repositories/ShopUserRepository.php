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

    public function getAllShopUsers($search)
    {
        return ShopUser::when(isset($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email','like', '%' . $search . '%')
                ->orWhere('phone_number','like', '%' . $search . '%')
                ->orWhereHas('shop', function ($shopQuery) use ($search) {
                    $shopQuery->where('name', 'like', '%' . $search . '%');
                });
        })->with('shop')->orderBy('id', 'desc')->get();
    }
}