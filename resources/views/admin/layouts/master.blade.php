@include('admin.layouts.header')
@include('admin.layouts.topbar')
<div class="clearfix"></div>
<div class="page-container">

    @include('admin.layouts.sidebar')

    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-title-box">
                            <h3 class="f_s_30 f_w_700 text-white">@yield('title')</h3>
                            <ol class="page-sub-title-box">
                                <li class="page-sub-title">R-Delivery</li>
                                <li class="page-sub-title">@yield('title')</li>
                                <li class="page-sub-title">@yield('sub-title')</li>
                                @yield('more-sub-title')
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="content-card" class="card content-card">
                            <div id="content-body" class="card-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="scroll-to-top"
     style="display: none;">
    <i class="fa fa-arrow-up"></i>
</div>
@include('admin.layouts.loading')
@include('admin.layouts.script')
@if (session('error'))
    <script>
         Toastify({
            text: "{!! session('error') !!}",
            gravity: "top",
            position: "center",
            backgroundColor: "red",
            duration: 3000,
        }).showToast();
    </script>
@endif
@yield('javascript')

</body>
</html>
