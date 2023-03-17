@extends('admin.layouts.master')

@section('content')
<style>
    .card-toolbar{
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
    }
    .create-button { 
        width: 70px;
        height: 30px;
        margin-bottom: 10px;
    }
</style>
        <div class="card">
            <div class="card-body">
                    <h2 class="ps-1">
                        <strong>Shop Detail</strong>
                    </h2>
                    <div class="card-toolbar">
                        <div class="create-button">
                            <a href="{{route('shops.edit' , $shop->id)}}" class="btn btn-light">Edit</a>
                        </div>
                        <form action="{{route('shops.destroy', $shop->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="Delete" class="btn btn-danger float-end">
                        </form>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Name <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $shop->name }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                            <h4>Email <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $shop->address }}
                        </div>
                    </div>
                    <div class="row m-0 mb-3">
                        <div class="col-2">
                        <h4>Phone Number <b>:</b></h4>
                        </div>
                        <div class="col-10 p-3">
                            {{ $shop->phone_number }}
                        </div>
                </div>
                <div class="card">
                    <div class="card-body px-4">
                        <h4>
                            <strong>Shop Users</strong>
                        </h4>
                        <div class="card-text">
                            <ol>
                                @foreach($shop->shop_users as $user)
                                    <li>
                                        <a href="{{route('shopusers.show', $user->id)}}"
                                            class="text-dark">{{$user->name}}</a>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection