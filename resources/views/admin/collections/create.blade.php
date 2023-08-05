@extends('admin.layouts.master')
@section('title','Collections')
@section('sub-title','Pick Up Create')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Pick Up</strong>
        </h2>
        <form action="{{route('collections.store')}}" method="POST" class="action-form">
            @csrf
            <div class="row m-0 mb-3">
                <label for="shop_id" class="col-2">
                    <h4>Shop <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="shop_id" id="shop_id" class="form-control">
                        <option value="" selected disabled>Select the Shop To Be Picked Up</option>
                        @foreach ( $shops as $shop)
                        <option value="{{$shop->id}}" @if($shop->id == old('shop_id')) selected
                            @endif
                            >{{$shop->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('shop_id'))
                    <span class="text-danger"><strong>{{ $errors->first('shop_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="collection_code" class="col-2">
                    <h4>Pick Up Code <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="collection_code" name="collection_code" class="form-control" readonly />
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_quantity" class="col-2">
                    <h4>Total Quantity of Pick Up <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_quantity" name="total_quantity" class="form-control" />
                    @if ($errors->has('total_quantity'))
                    <span class="text-danger"><strong>{{ $errors->first('total_quantity') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount of Pick Up <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount" class="form-control" />
                    @if ($errors->has('total_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="total_quantity" class="col-2">
                    <h4>Paid Amount By Rider<b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="paid_amount" name="paid_amount" class="form-control" />
                    @if ($errors->has('paid_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('paid_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="note" class="col-2">
                    <h4>Note <b>:</b></h4>
                </label>
                <div class="col-10">
                    <textarea id="note" style="height: 100px" name="note" class="form-control"></textarea>
                    @if ($errors->has('note'))
                    <span class="text-danger"><strong>{{ $errors->first('note') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="footer-button float-end">
                <a href="{{url()->previous() }}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success ">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $('#shop_id').select2();

    $('#shop_id').on('change', function() {
        var shop_id = $('#shop_id').val();
        $.ajax({
            url: '/get-collection-code',
            type: "GET",
            data: {
                shop_id: shop_id
            },
            success: function(res) {
                if (res) {
                    $("#collection_code").val(res.data);
                }
            }
        });
    })
</script>
@endsection