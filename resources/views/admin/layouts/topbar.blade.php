<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner  w-100">
        <div class="page-header-inner">

            <a href="javascript:;"
               class="menu-toggler responsive-toggler"
               data-toggle="collapse"
               data-target=".navbar-collapse">
            </a>

            

            <div class="top-menu" style="display: flex;">
                <a id="notificationCollapse" class="btn btn-secondary" data-bs-toggle="collapse" href="#notification" role="button" aria-expanded="false" aria-controls="collapseExample">
                <i class="fa fa-bell"></i>
                </a>
                <div class="dropdown-menu collapse customize-collapse" aria-labelledby="notificationDropdown" id="notification">
            <div class="dropdown-header">Notifications</div>
            <div class="dropdown-divider"></div>
            <div class="dropdown-body" id="notificationList">
                <a class="dropdown-item" href="#">No notifications</a>
            </div>
        </div>
                
                <div class="nav navbar-nav pull-right hidden-xs">
                    <span class="current-user">
                        <!-- <i class="fa fa-user-o"></i>  -->
                        {{ Auth::user()->name }}!</span>
                </div>

            </div>
            <div class="navbar-header">
                <a href="{{ URL::to('/dashboard') }}" class="navbar-brand topbar-brand">
                    R-Delivery
                </a>
            </div>
        </div>
    </div>
</div>
