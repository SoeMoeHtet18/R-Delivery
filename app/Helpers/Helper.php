<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class Helper
{
    public static function nomenclature($tableName, $prefix, $columnName, $shop_id)
    {
        $formattedShopId = str_pad($shop_id, 3, '0', STR_PAD_LEFT);

        $carbon = Carbon::now()->format('ymd');

        $maxId = DB::table($tableName)->max($columnName);

        if ($maxId === null) {
            $nextId = 1;
        } else {
            $nextId = (int)$maxId + 1;
        }

        $nomenclatureCode = $prefix . '-' . $formattedShopId . $carbon . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        return $nomenclatureCode;
    }
}
