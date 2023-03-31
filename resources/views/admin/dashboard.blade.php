@extends('admin.layouts.master')
@section('title','Main Dashboard')
@section('sub-title','Overview')
@section('content')

    <style>
        .link
        {
            color: #64c5b1;
            text-decoration: none;
        }
        .link:hover
        {
            color: #64c5b1;
            text-decoration: none;
        }
        .bg-info-box
        {
            background-color: #64c5b1;
            color: white;
            border: 1px solid #64c5b1;
            border-radius: 5px !important;
        }
        .bg-info-box:hover
        {
            background-color: white;
            color: #64c5b1;
            border: 1px solid #64c5b1;
            border-radius: 5px !important;
        }
        .info-box-title
        {
            color: #64c5b1;
        }
    </style>

    <h4 class="info-box-title"><strong>Overview</strong></h4>
    <section class="content">

        <div class="row">
            
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{route('users.index')}}" class="link">
                    <div class="info-box">
                        <span class="info-box-icon bg-info-box">U</span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Users</span>
                            <span class="info-box-number">{{$usercount}}</span>
                        </div>
                    </div>
                </a> 
            </div>
                 
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{route('shops.index')}}" class="link">
                    <div class="info-box">
                        <span class="info-box-icon bg-info-box">S</span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Shops</span>
                            <span class="info-box-number">{{$shopcount}}</span>
                        </div>
                    </div>
                </a> 
            </div>
 
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{route('shopusers.index')}}" class="link">
                    <div class="info-box">
                        <span class="info-box-icon bg-info-box">SU</span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Shop Users</span>
                            <span class="info-box-number">{{$shopusercount}}</span>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{route('cities.index')}}" class="link">
                    <div class="info-box">
                        <span class="info-box-icon bg-info-box">C</span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Cities</span>
                            <span class="info-box-number">{{$citycount}}</span>
                        </div>
                    </div>
                </a>
            </div>     

            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{route('itemtypes.index')}}" class="link">
                    <div class="info-box">
                        <span class="info-box-icon bg-info-box">I</span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Item Types</span>
                            <span class="info-box-number">{{$itemTypeCount}}</span>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{route('townships.index')}}" class="link">
                    <div class="info-box">
                        <span class="info-box-icon bg-info-box">T</span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Townships</span>
                            <span class="info-box-number">{{$townshipcount}}</span>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{route('shoppayments.index')}}" class="link">
                    <div class="info-box">
                        <span class="info-box-icon bg-info-box">SP</span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Shop Payment</span>
                            <span class="info-box-number">{{$shoppaymentcount}}</span>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{route('customer-payments.index')}}" class="link">
                    <div class="info-box">
                        <span class="info-box-icon bg-info-box">CP</span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Customer Payment</span>
                            <span class="info-box-number">{{$customerpaymentcount}}</span>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{route('transactions-for-shop.index')}}" class="link">
                    <div class="info-box">
                        <span class="info-box-icon bg-info-box">TS</span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Transaction For Shop</span>
                            <span class="info-box-number">{{$transactionforshopcount}}</span>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{route('riders.index')}}" class="link">
                    <div class="info-box">
                        <span class="info-box-icon bg-info-box">R</span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Riders</span>
                            <span class="info-box-number">{{$ridercount}}</span>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{route('orders.index')}}" class="link">
                    <div class="info-box">
                        <span class="info-box-icon bg-info-box">O</span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Orders</span>
                            <span class="info-box-number">{{$ordercount}}</span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </section>
    <hr style="border: 1px dashed #000;">

    <!-- <p><strong><h4>Shop</h4></strong></p>
    <section class="content">

        <div class="row">
            <a href="#" class="link">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua">S</span>

                        <div class="info-box-content">
                            <span class="info-box-text">All Shops</span>
                            <span class="info-box-number">{{$shopcount}}</span>
                        </div>
                    </div>
                </div>
            </a>   
        </div>
    </section>
    <hr style="border: 1px dashed #000;"> -->

@endsection
@section('javascript')

@endsection