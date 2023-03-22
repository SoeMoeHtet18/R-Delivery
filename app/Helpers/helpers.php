<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

if(!function_exists('nomenclature')) {
    function nomenclature($array = array())
    {
        $column_name = $array['column_name'];
        $carbon = Carbon::now()->format('ymd');
        $lastId = DB::table($array['table_name'])->orderByDesc('id')->first();
        $lastId = $lastId->$column_name;
        $lastId = substr($lastId, -2);
        $lastId = (int)$lastId;
        return $array['prefix'] . $carbon . '-' . str_pad($lastId + 1, 2, '0', STR_PAD_LEFT);
    }
}