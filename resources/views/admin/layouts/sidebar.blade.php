<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu"
            data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200">
            <li>
                <a href="{{route('users.index')}}">
                    <!-- <i class="fa fa-users"></i> -->
                    <span class="title">User</span>
                </a>
            </li>
            <li>
                <a href="{{route('shops.index')}}">
                    <!-- <i class="fa fa-users"></i> -->
                    <span class="title">Shop</span>
                </a>
            </li>
            <li>
                <a href="{{route('riders.index')}}">
                    <!-- <i class="fa fa-users"></i> -->
                    <span class="title">Rider</span>
                </a>
            </li>
            <li>
                <a href="{{route('shopusers.index')}}">
                    <!-- <i class="fa fa-users"></i> -->
                    <span class="title">Shop User</span>
                </a>
            </li>
            <li>
                <a href="{{route('townships.index')}}">
                    <!-- <i class="fa fa-users"></i> -->
                    <span class="title">Township</span>
            </li>
            <li>
                <a href="{{route('orders.index')}}">
                    <!-- <i class="fa fa-users"></i> -->
                    <span class="title">Oders</span>
                </a>
            </li>
            <li>
                <a href="{{route('cities.index')}}">
                    <!-- <i class="fa fa-users"></i> -->
                    <span class="title">Cities</span>
                </a>
            </li>
            <li>
            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            </li>
        </ul>
    </div>
</div>
