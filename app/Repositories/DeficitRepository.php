<?php

namespace App\Repositories;

use App\Models\Deficit;
use App\Models\Rider;
use Carbon\Carbon;

class DeficitRepository {
    public function getDeficitByRiderId($id)
    {
        $rider = Rider::findOrFail($id);

        if ($rider->salary_type === 'daily') {
            // Get deficits created today for riders with a daily salary type
            $today = Carbon::today();
            return Deficit::where('rider_id', $id)
                ->whereDate('created_at', $today);
        } else {
            // Get deficits created within a specific month for riders with a different salary type
            $currentMonth = Carbon::today()->month;
            $currentYear = Carbon::today()->year;
            return Deficit::where('rider_id', $id)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth);
        }
    }
}
