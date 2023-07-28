<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentNotificationListController extends Controller
{
    public function index()
    {

        return view('admin.payment_notification.index');
    }
}
