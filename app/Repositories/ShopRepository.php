<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Models\ShopUser;
use Yajra\DataTables\Facades\DataTables;

class ShopRepository
{
    public function getAllShops()
    {
        $shops = Shop::all();
        return $shops;
    }
    
    public function getAllShopsByDESC()
    {
        $shops = Shop::select('*')->orderBy('id','DESC');
        return $shops;
    }

    public function getShopByID($id)
    {
        $shop = Shop::findOrFail($id);
        return $shop;
    }

    public function getShopUsersByShopID($id)
    { 
        $data = ShopUser::where('shop_id', $id)->orderBy('id','DESC')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($data){
                $actionBtn = '
                        <a href="'. route("shopusers.show", $data->id) .'" class="edit btn btn-info btn-sm">View</a> 
                        <a href="'. route("shopusers.edit", $data->id) .'" class="edit btn btn-light btn-sm">Edit</a>
                    ';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}