<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner  w-100">
        <div class="page-header-inner">

            <a href="javascript:;"
               class="menu-toggler responsive-toggler"
               data-toggle="collapse"
               data-target=".navbar-collapse">
            </a>

            <div class="top-menu">
                <div class="nav navbar-nav pull-right hidden-xs">
                    <span class="current-user">
                        <!-- <i class="fa fa-user-o"></i>  -->
                        {{ Auth::user()->name }}!</span>
                </div>

            </div>
            <div class="navbar-header">
                <a href="{{ URL::to('/admin') }}" class="navbar-brand">

                    R-Delivery - Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
