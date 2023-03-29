<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu"
            data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200">
            <li class="@if(request()->is('shops*')) {{'active'}} @endif">
                <a href="{{route('shops.index')}}">
                    <!-- <i class="fa fa-users"></i> -->
                    <span class="title">Shop</span>
                </a>
            </li>
            <li class="@if(request()->is('riders*')) {{'active'}} @endif">
                <a href="{{route('riders.index')}}">
                    <!-- <i class="fa fa-users"></i> -->
                    <span class="title">Rider</span>
                </a>
            </li>
            <li class="@if(request()->is('shopusers*')) {{'active'}} @endif">
                <a href="{{route('shopusers.index')}}">
                    <!-- <i class="fa fa-users"></i> -->
                    <span class="title">Shop User</span>
                </a>
            </li>
           
            <li class="@if(request()->is('orders*')) {{'active'}} @endif">
                <a href="{{route('orders.index')}}">
                    <!-- <i class="fa fa-users"></i> -->
                    <span class="title">Order</span>
                </a>
            </li>
            <li>
                <a id="paymentBoxCollapse" data-bs-toggle="collapse" href="#paymentBox" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <span class="title">Payments<span class="arrow"></span></span>
                </a>
                
            </li>
            <ul class="collapse customize-collapse" id="paymentBox">
                    <li class="@if(request()->is('shoppayments*')) {{'active'}} @endif">
                        <a href="{{route('shoppayments.index')}}">
                            <!-- <i class="fa fa-users"></i> -->
                            <span class="title">Shop Payment</span>
                        </a>
                    </li>
                    <li class="@if(request()->is('customer-payments*')) {{'active'}} @endif">
                        <a href="{{route('customer-payments.index')}}">
                            <!-- <i class="fa fa-users"></i> -->
                            <span class="title">Customer Payment</span>
                        </a>
                    </li>           
                    <li class="@if(request()->is('transactions-for-shop*')) {{'active'}} @endif">
                        <a href="{{route('transactions-for-shop.index')}}">
                            <!-- <i class="fa fa-users"></i> -->
                            <span class="title">Transactions For Shop</span>
                        </a>
                    </li>
            </ul>
            <li>
                <a id="adminToolsCollapse" data-bs-toggle="collapse" href="#adminTools" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <span class="title">Admin Tools<span class="arrow"></span></span>
                </a>
            </li>
                <ul class="collapse customize-collapse" id="adminTools">
                    <li class="@if(request()->is('users*')) {{'active'}} @endif">
                        <a href="{{route('users.index')}}">
                            <!-- <i class="fa fa-users"></i> -->
                            <span class="title">User</span>
                        </a>
                    </li>
                    <li class="@if(request()->is('cities*')) {{'active'}} @endif">
                        <a href="{{route('cities.index')}}">
                            <!-- <i class="fa fa-users"></i> -->
                            <span class="title">City</span>
                        </a>
                    </li>
                    <li class="@if(request()->is('townships*')) {{'active'}} @endif">
                        <a href="{{route('townships.index')}}">
                            <!-- <i class="fa fa-users"></i> -->
                            <span class="title">Township</span>
                        </a>
                    </li>
                    <li class="@if(request()->is('itemtypes*')) {{'active'}} @endif">
                        <a href="{{route('itemtypes.index')}}">
                            <!-- <i class="fa fa-users"></i> -->
                            <span class="title">Item Type</span>
                        </a>
                    </li>
                </ul>
            <li class="mt-5">
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    <span class="title">{{ __('Logout') }}</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
