<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShopUser;
use App\Repositories\ShopUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShopUserApiController extends Controller
{
    protected $shopuserRepository;

    public function __construct(ShopUserRepository $shopUserRepository)
    {
        $this->shopuserRepository = $shopUserRepository;
    }

    public function shopUsersLoginApi(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['data' => [], 'message' => $validator->messages()->first(), 'status' => 'fail'], 401);
        }
        // dd($request->all());

        if(Auth::guard('shopuser')->attempt(['phone_number' => request('phone_number'), 'password' => request('password')])){ 
            $shopuser = ShopUser::where('id', Auth::guard('shopuser')->user()->id)->first();
            $shopuser->token =  $shopuser->createToken('ShopUser')->accessToken;
            ShopUser::where('phone_number',request('phone_number'))->update(['token' => $shopuser->token]);       
            return response()->json( ['data' => $shopuser, 'message' => 'Successfully Logged In', 'status' => 'success'], 200); 
        }else{
            return response()->json(['data' => [], 'message' => 'Invalid credentials.', 'status' => 'fail'], 401); 
        }
    }

    public function show($id) 
    {
        $shopuser = $this->shopuserRepository->getShopUserByID($id);
        return response()->json( ['data' => $shopuser, 'message' => 'Successfully Get Shop User Detail', 'status' => 'success'], 200); 
    }
}
