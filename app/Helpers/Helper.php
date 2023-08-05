<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class Helper
{
    public static function nomenclature($tableName, $prefix, $columnName, $appendix_id, $appendix_type, $change_type = null)
    {
        $formattedAppendixId= str_pad($appendix_id, 3, '0', STR_PAD_LEFT);
        $upperAppendixType = strtoupper($appendix_type);

        $carbon = Carbon::now()->format('ymd');

        $maxId = DB::table($tableName)->max($columnName);

        if ($maxId === null) {
            $nextId = 1;
        } else {
            if($change_type == null) {
                $nextId = (int)$maxId + 1;
            } else {
                $nextId = (int)$maxId;
            }
        }

        $nomenclatureCode = $prefix . '-' . $upperAppendixType . $formattedAppendixId . $carbon . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        return $nomenclatureCode;
    }
}
