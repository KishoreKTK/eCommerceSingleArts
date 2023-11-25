@extends('layouts.login_layout')
@section('form_title', 'Create New Password')
@section('form_content')
@if($result['status'] == true)
    <form class="pt-3" action="{{ url('UpdatePassword') }}" method="POST">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ $message }}</strong>
                {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ $message }}</strong>
                {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
        @endif
        {{-- @if (Session::get('fail'))
            <div class="alert alert-danger">
                {{ Session::get('fail') }}
            </div>
        @endif --}}
        <input type="hidden" name="mail" value="{{$result['mail']}}">
        <input type="hidden" name="usertype" value="{{$result['usertype']}}">
        @csrf
        <div class="form-group">
            <input type="password" name="password" value="{{ old('password') }}" class="form-control form-control-lg" required id="exampleInputPassword1" placeholder="New Password">
            <span class="text-danger">@error('password'){{ $message }}@enderror</span>
        </div>
        <div class="form-group">
            <input type="password" name="ConfirmPassword" value="{{ old('password') }}" class="form-control form-control-lg" required id="exampleInputPassword12" placeholder="Confirm New Password">
            <span class="text-danger">@error('password'){{ $message }}@enderror</span>
        </div>

        <div class="mt-3 mb-2">
            <button class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" >Submit</button>
        </div>
    </form>
@else
<div class="alert alert-danger">
    {{ $result['message'] }}
</div>
<div class="mt-3 mb-2">
    <a href="" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" >View Web Page</a>
</div>
@endif
@endsection
