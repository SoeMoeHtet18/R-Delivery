@extends('vue-pages.layout.master')
@section('content')
<dashboard
    :user_count="{{$userCount}}"
    :shop_user_count="{{$shopUserCount}}"
    :rider_count="{{$riderCount}}"
    :pick_up_count="{{$collectionCount}}"
    :exchange_count="{{$customerCollectionCount}}"
    :pick_up_group_count="{{$collectionGroupCount}}"
    :shop_payment_from_company_count="{{$transactionForShopCount}}"
    :shop_payment_from_shop_count="{{$shopPaymentCount}}"
    :rider_payment_count="{{$riderPaymentCount}}"
    :city_count="{{$cityCount}}"
    :township_count="{{$townshipCount}}"
    :branch_count="{{$branchCount}}"
    :gate_count="{{$gateCount}}"
    :third_party_vendor_count="{{$thirdPartyVendorCount}}"
    :item_type_count="{{$itemTypeCount}}"
    :delivery_type_count="{{$deliveryTypeCount}}"
    :order_count="{{$orderCount}}"
    :shop_count="{{$shopCount}}"
>
</dashboard>

@endsection
