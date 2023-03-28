@extends('admin.layouts.master')

@section('content')
        <div class="card card-container action-form-card">
            <div class="card-body">
                <h2 class="ps-1 card-header-title">
                    <strong>Assign Township For Rider</strong>
                </h2>
                <form action="{{url('/riders/'.$rider->id.'/assign-township')}}" method="POST" class="action-form">
                    @csrf
                    @method('PUT')
                    <div class="row m-0 mb-3">
                        <label for="name" class="col-2">
                            <h4>Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <input type="text" value="{{$rider->name}}" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <label for="township_id" class="col-2">
                            <h4>Township Name <b>:</b></h4>
                        </label>
                        <div class="col-10">
                            <select name="township_id[]" id="township_id" class="form-control" multiple>
                                @foreach($townships as $township)
                                    <option value="{{$township->id}}"
                                        @foreach($rider->townships as $rider_township)
                                        @if($township->id == $rider_township->id) {{'selected'}} @endif
                                        @endforeach>{{$township->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="id" name="id" value="{{$rider->id}}" class="form-control"/>
                    <div class="footer-button float-end">
                        <a href="{{url()->previous() }}" class="btn btn-light">Cancel</a>
                        <input type="submit" class="btn btn-success ">
                    </div>
                    
                </form>
            </div>
        </div>
        
@endsection
@section('javascript')
<script type="text/javascript">
  $(function () {
      $('#township_id').select2({
        placeholder: 'Select Townships',
        allowClear: true
    });   
  });
</script>
@endsection