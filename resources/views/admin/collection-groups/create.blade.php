@extends('admin.layouts.master')
@section('title','Collection Group Create')
@section('content')
<style>
    /* Assuming .plus-btn is the direct child of .generat_sku */
    .generat_sku > .plus-btn {
    background-color: #000000;
    padding: 10px;
    color: white;
    border-radius: 20px;
    font-size: 23px;
    float: right;
    }

</style>
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Add New Collection Group</strong>
        </h2>
        <form action="{{route('collection-groups.store')}}" method="POST" class="action-form">
            @csrf
            <div class="row m-0 mb-3">
                <label for="total_amount" class="col-2">
                    <h4>Total Amount <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="total_amount" name="total_amount" class="form-control" />
                    @if ($errors->has('total_amount'))
                    <span class="text-danger"><strong>{{ $errors->first('total_amount') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="rider_id" class="col-2">
                    <h4>Rider <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="rider_id" id="rider_id" class="form-control">
                        <option value="" selected disabled>Select the Rider for This Collection</option>
                        @foreach ( $riders as $rider)
                        <option value="{{$rider->id}}" 
                            @if($rider->id == old('rider_id')) selected
                            @endif
                            >{{$rider->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('rider_id'))
                    <span class="text-danger"><strong>{{ $errors->first('rider_id') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3">
                <label for="assigned_date" class="col-2">
                    <h4>Assigned Date <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="date" id="assigned_date" name="assigned_date" class="form-control" />
                    @if ($errors->has('assigned_date'))
                    <span class="text-danger"><strong>{{ $errors->first('assigned_date') }}</strong></span>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card card-container action-form-card">
    <div class="card-body">
        <div class="row">
            <div class="generat_sku margin10"  style="cursor: pointer;"><i id="addMoreCategory" class="fal fa-plus plus-btn"
                style="cursor: pointer;"></i></div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                <label for="shop">
                    <strong>Shop</strong>
                </label>
                <div class="col-10">
                    <select name="shop" id="shop" class="form-control shop-dropdown">
                        <option value="" selected disabled>Select</option>
                        @foreach($shops as $shop)
                        <option value="{{$shop->id}}">{{$shop->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
            <label><strong>Amount</strong></label>
            <input type="text" id="total_amount" name="total_amount" class="form-control" />
            </div>
        </div>
        <div id="extraHtml">
        </div>
    </div>
</div>

<div class="card card-container action-form-card">
    <div class="card-body">
        <div id="collectionList">
        </div>
    </div>
</div>

<div class="footer-button float-end">
    <a href="{{route('collection-groups.index')}}" class="btn btn-light">Cancel</a>
    <input type="submit" class="btn btn-success ">
</div>

@endsection
@section('javascript')
<script type="text/javascript">
    $(function() {
        $('#rider_id').select2();
        $('#collection_id').select2({
            placeholder: 'Select Collections',
            allowClear: true
        });
    });

    var appendCategory = () => {return '<div class="row" ><div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><label for="shop"><strong>Shop</strong></label><div class="col-10"><select name="shop" id="shop" class="form-control shop-dropdown"><option value="" selected disabled>Select</option>@foreach($shops as $shop)<option value="{{$shop->id}}">{{$shop->name}}</option>@endforeach</select></div></div><div class="col-lg-4 col-md-4col-sm-12 col-xs-6"><label><strong>Amount</strong></label><input type="text" id="total_amount" name="total_amount" class="form-control" /></div></div>'
    };

    var addMoreCategory = () => {
      document.getElementById('addMoreCategory').addEventListener('click', (e) => {
        console.log('addMoreCategory')
        $('#extraHtml').append(appendCategory());
      });
    }
    addMoreCategory();

    $(document).ready(function() {
        var shopDropdownValues = [];
        $('body').on('change', '.shop-dropdown', function(e) {
            var selectedValue = $(this).val();
            var dropdownIndex = $('.shop-dropdown').index(this);
            shopDropdownValues[dropdownIndex] = selectedValue;
            console.log(shopDropdownValues);
            data = {
                shop_ids: shopDropdownValues,
            };
            console.log(data);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/get-collections-by-shop',
                    type: "GET",
                    data: data,
                    success: function(datas) {
                        if (datas) {
                            $("#collectionList").html(datas);
                        } else {
                            $("#collectionList").html("");
                        }
                    }
                });
        });
    });
</script>
@endsection