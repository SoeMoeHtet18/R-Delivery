<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function browse()
    {
        return view('vue-pages.setting.browse');
    }

    public function updateSetting(Request $request)
    {
        $data = $request->data;
        $collection_method = $data['collection_method'];
        $schedule_date = $data['schedule_date'];

        $cached_collection_method = Cache::get('collection_method');
        $cached_schedule_date = Cache::get('schedule_date');

        if($cached_collection_method != $collection_method) {
            Cache::put('collection_method', $collection_method);
        } elseif($cached_schedule_date != $schedule_date) {
            Cache::put('schedule_date', $schedule_date);
        }
        return response()->json([
            'data'   => null,
            'message'=> 'Successfully update setting',
            'status' => 'success'
        ], 200);
    }
}
