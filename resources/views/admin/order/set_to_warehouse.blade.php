@extends('admin.layouts.master')
@section('title','Dashboard')
@section('sub-title','Set To Warehouse')
@section('content')

<div>
    <h2 class="ps-1 card-header-title">
        <strong>Set To Warehouse</strong>
    </h2>
    <div class="row">
        <div class="filter-box">
            <div class="mb-3 p-3 col-4">
                
                <div class="col-10">
                    <input type="text" id="search" name="search" class="form-control" />
                </div>
            </div>
        </div>
    </div>
</div>

<div id="container"></div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        $('#search').keypress(function(e) {
            console.log('keypress');
            if (e.which === 13) { // Check if the pressed key is Enter (key code 13)
                e.preventDefault();
                var search = $('#search').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/change-order-status-to-warehouse',
                    type: "GET",
                    data: {
                        search : search
                    },
                    success: function(data) {
                        if (data['data']) {
                            $("#container").empty();
                            $("#container").append(data['data']);
                        } else {
                            $("#container").empty();
                            var options = {
                                text: data['message'],
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                backgroundColor: "red",
                                position: "center",
                            };

                            Toastify(options).showToast();
                        }
                    }
                    
                });
            }
        });
    });
    
</script>
@endsection