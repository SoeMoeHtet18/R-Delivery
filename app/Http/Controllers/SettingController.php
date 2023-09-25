<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function browse()
    {
        return view('vue-pages.setting.browse');
    }
}
