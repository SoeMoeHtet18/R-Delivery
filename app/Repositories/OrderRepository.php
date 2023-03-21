<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function getOrderByID($id)
    {
        $order = Order::findOrFail($id);
        return $order;
    }
    public function getAllOrder()
    {
        $order = Order::get();
        return $order;
    }
}