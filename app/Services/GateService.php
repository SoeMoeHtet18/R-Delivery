<?php

namespace App\Services;

use App\Models\Gate;
use App\Models\Township;

class GateService
{
    public function saveGateData($data) {
        $gate = new Gate();
        $gate->name = $data['name'];
        $gate->city_id = $data['city_id'];
        $gate->address = $data['address'];
        $gate->save();
        $township_id = $data['township_id'];
        $townships = Township::whereIn('id',$township_id)->get();
        foreach($townships as $township) {
            $township->associable()->associate($gate);
            $township->save();
        }
        
        return $gate;
    }
    
    public function updateGateByID($data, $gate) {
        $gate->name = $data['name'];
        $gate->city_id = $data['city_id'];
        $gate->address = $data['address'];
        $gate->save();
        $township_id = $data['township_id'];
        $gate->townships()->wherePivot('gate_id','=',$gate->id)->detach();
        $gate->townships()->sync($township_id);
        return $gate;
    }

    public function deleteGateByID($id)
    {
        Gate::destroy($id);
    }
}
