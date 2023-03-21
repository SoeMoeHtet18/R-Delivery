<?php

namespace App\Repositories;

use App\Models\Order;

class OrdersRepository
{
    public function getAllOrder()
    {
        $order = Order::get();
        return $order;
    }
}