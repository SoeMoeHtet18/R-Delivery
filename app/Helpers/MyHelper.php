<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class MyHelper {
    public static function nomenclature($array = array())
    {
        $column_name = $array['column_name'];
        $carbon = Carbon::now()->format('ymd');
        $lastId = DB::table($array['table_name'])->orderByDesc('id')->first();
        if($lastId) {
             $lastId = $lastId->$column_name;
            $lastId = substr($lastId, -2);
            $lastId = (int)$lastId;
            return $array['prefix'] . $carbon . '-' . str_pad($lastId + 1, 2, '0', STR_PAD_LEFT);
        } else {
            return $array['prefix'] . $carbon . '-' . str_pad(1, 2, '0', STR_PAD_LEFT);
        }
       
    }
}   