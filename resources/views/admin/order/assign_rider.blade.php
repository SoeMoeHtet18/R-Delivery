@extends('admin.layouts.master')

@section('content')
        <div class="card card-container action-form-card">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Assign Rider to Order</strong>
                </h2>
                <form action="{{url('/orders/'.$order->id.'/assign-rider')}}" method="post" class="action-form">
                    @csrf
                    @method('post')
                    <div class="row m-0 mb-3">
                        <label for="township_id" class="col-2">
                            <h4>Township Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input value="{{$township->name}}" readonly>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="rider_id" class="col-2">
                            <h4>Rider Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="rider_id" id="rider_id" class="form-control">
                                <option value="" selected disabled>Select Rider For This Order</option>
                                @foreach($riders as $rider)
                                <option value="{{$rider->id}}">{{$rider->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('rider_id'))
                            <span class="text-danger"><strong>{{ $errors->first('rider_id') }}</strong></span>
                            @endif
                        </div>
                    </div>
                    <input type="submit" value="Assign" class="btn btn-success float-end">
                </form>
            </div>
        </div>
        
@endsection