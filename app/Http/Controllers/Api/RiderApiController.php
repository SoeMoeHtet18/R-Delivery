<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use App\Repositories\RiderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RiderApiController extends Controller
{
    protected $riderRepository;

    public function __construct(RiderRepository $riderRepository)
    {
        $this->riderRepository = $riderRepository;
    }
    public function riderLoginApi(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => $validator->messages()->first(), 'status' => 'fail'], 401);
        }

        
        if(Auth::guard('rider')->attempt(['phone_number' => request('phone_number'), 'password' => request('password')])){ 
            $rider = Rider::where('id', Auth::guard('rider')->user()->id)->first();
            $rider->token =  $rider->createToken('rider')->accessToken;
            Rider::where('phone_number',request('phone_number'))->update(['token' => $rider->token]);       
            return response()->json( ['data' => $rider, 'message' => 'Successfully Logged In', 'status' => 'success'], 200); 
        }else{
            return response()->json(['data' => [], 'message' => 'Invalid credentials.', 'status' => 'fail'], 401); 
        }
    }

    public function show($id)
    {
        $rider = $this->riderRepository->getRiderByID($id);
        return response()->json(['data'=> $rider, 'message'=> 'Successfully Get Rider Detail', 'status' => 'success', 200]);
    }
}
