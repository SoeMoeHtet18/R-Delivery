<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentDetailRequest;
use App\Http\Requests\ShopUserUpdateApiRequest;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;

class UpdateShopOrchestratorController extends Controller
{
    public function updateShopUserDataAndPaymentInfo(
        Request $request,
        ShopUserApiController $shopUserApiController,
        PaymentDetailApiController $paymentDetailApiController
    ) {
        $shop_user = auth()->guard('shop-user-api')->user();

        $data = $request->all();
        $requiredShopUserData = [
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email']
        ];

        // Create a new instance of ShopUserUpdateApiRequest with only the required data
        $shopUserUpdateRequest = new ShopUserUpdateApiRequest();
        $shopUserUpdateRequest->merge($requiredShopUserData);
        $shopUserResponse = $shopUserApiController->update($shopUserUpdateRequest);

        $requiredPaymentDetailData = [
            'shop_id' => $shop_user->shop_id,
            'payment_type_id' => $data['payment_type_id'],
            'account_owner_name' => $data['account_owner_name'],
            'account_number' => $data['account_number']
        ];

        // Create a new instance of ShopUserUpdateApiRequest with only the required data
        $paymentDetailRequest = new PaymentDetailRequest();
        $paymentDetailRequest->merge($requiredPaymentDetailData);

        $paymentDetail = PaymentDetail::where('shop_id', $shop_user->shop_id)->first();

        if(!$paymentDetail) {
            $paymentDetailResponse = $paymentDetailApiController->create($paymentDetailRequest);
        } else {
            $paymentDetailResponse = $paymentDetailApiController->update($paymentDetailRequest, $paymentDetail);
        }

        return response()->json([
            'user_response' => $shopUserResponse,
            'payment_response' => $paymentDetailResponse,
            'message' => 'Successfully Updated User Data And Payment Detail', 'status' => 'success'], 200);
    }
}
