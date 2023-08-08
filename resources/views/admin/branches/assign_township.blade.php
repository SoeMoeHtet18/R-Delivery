@extends('admin.layouts.master')
@section('title','Admin Tools')
@section('sub-title','Branch')
@section('content')
<div class="card card-container action-form-card">
    <div class="card-body">
        <h2 class="ps-1 card-header-title">
            <strong>Assign Townships</strong>
        </h2>
        <form action="{{url('/branches/'.$branch->id.'/assign-township')}}" method="POST" class="action-form" enctype="multipart/form-data">
            @csrf
            <div class="row m-0 mb-3">
                <label for="name" class="col-2">
                    <h4>Branch Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <input type="text" id="name" name="name" value="{{$branch->name}}" class="form-control" readonly/>
                    @if($errors->has('name'))
                    <span class="text-danger"><strong>{{ $errors->first('name') }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="row m-0 mb-3 selectsearch">
                <label for="township_id" class="col-2">
                    <h4>Township Name <b>:</b></h4>
                </label>
                <div class="col-10">
                    <select name="township_id[]" id="township_id" class="form-control" multiple="">
                        @foreach ($townships as $township)
                            <option value="{{$township->id}}" @if(in_array($township->id, $assignedTownshipID)) selected @endif>{{$township->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('township_id'))
                    <span class="text-danger"><strong>{{ $errors->first('township_id') }}</strong></span>
                    @endif
                </div>
            </div>
            
            <div class="footer-button float-end">
                <a href="{{route('branches.show', $branch->id)}}" class="btn btn-light">Cancel</a>
                <input type="submit" class="btn btn-success">
            </div>
        </form>
    </div>
</div>

@endsection
@section('javascript')
<script>
    $(document).ready(function() {
        $('#township_id').select2();
    });
</script>
@endsection
