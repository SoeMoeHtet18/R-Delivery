@include('admin.layouts.header')
@include('admin.layouts.topbar')
<div class="clearfix"></div>
<div class="page-container">

    @include('admin.layouts.sidebar')

    <div class="page-content-wrapper">
        <div class="page-content">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @yield('content')
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
@stack('javascript')
@yield('javascript')

</body>
</html>
