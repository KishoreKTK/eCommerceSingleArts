
@extends('layouts.login_layout')
@section('form_title', 'Seller Register')

@section('form_content')
<h6><center>Request New Seller Membership?</center></h6>
<br size="3" class="my-2">
{{-- <h6 class="font-weight-light">Signing Up is Easy, Just Send us fill form correctly and Send Login Requests</h6> --}}
<form class="pt-3" action="{{ route('seller.create') }}" method="post" autocomplete="off" enctype="multipart/form-data">
    @if (Session::get('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif
    @if (Session::get('fail'))
    <div class="alert alert-danger">
        {{ Session::get('fail') }}
    </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @csrf
    <div class="form-group">
        <label for="exampleFormControlInput2">Full Name</label>
        <input type="text" class="form-control" id="exampleFormControlInput2" name="sellername" value="{{ old('sellername') }}" placeholder="Seller Name">
    </div>

    <div class="form-group">
        <label for="exampleFormControlInput1">Email address</label>
        <input type="email" class="form-control" id="exampleFormControlInput1" name="selleremail" value="{{ old('selleremail') }}" placeholder="Seller Email">
    </div>

    <div class="form-group">
        <label>Mobile Number</label><br>
        <input type="text" class="form-control" name="mobile" placeholder="Contact Number" required>
    </div>


    <div class="form-group">
        <label for="exampleFormControlInput3">Password</label>
        <input type="password" class="form-control" id="exampleFormControlInput3" name="password" value="{{ old('password') }}" placeholder="Password">
    </div>

    <div class="form-group">
        <label for="exampleFormControlInput4">Confirm Password</label>
        <input type="password" class="form-control" id="exampleFormControlInput4" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Confirm Password">
    </div>

    <div class="form-group">
        <p> Business Type</p>
        <label class="radio-inline mx-2">
            <input type="radio" class="seller_buss_type_class" name="seller_buss_type" value="Individual"> Individual</label>
        <label class="radio-inline mx-2">
            <input type="radio" class="seller_buss_type_class" name="seller_buss_type" value="Business"> Business</label>
    </div>

    {{-- <div class="form-group">
        <label for="exampleFormControlSelect1">Business Types</label>
        <select class="form-control" name="seller_buss_type" id="exampleFormControlSelect1">
            <option value="">Please Select</option>
            <option>Public Limited Company</option>
            <option>Private Limited Company</option>
            <option>Joint-Venture Company</option>
            <option>Partnership Firm</option>
            <option>One Person Company</option>
            <option>Sole Proprietorship</option>
            <option>Branch Office</option>
            <option>Non-Government Organization (NGO)</option>
        </select>
    </div> --}}

    {{-- <div class="form-group">
        <label for="exampleFormControlSelect1">Business Categories</label>
        <select class="form-control" name="seller_buss_cat" id="exampleFormControlSelect1">
            <option value="">Please Select</option>
            <option>Sole proprietorship</option>
            <option>Partnership</option>
            <option>Corporation</option>
            <option>Limited Liability Company</option>
        </select>
    </div> --}}

    <div class="form-group business_type_company">
        <label for="exampleFormControlInput2">Business Name</label>
        <input type="name" class="form-control" id="exampleFormControlInput2" name="seller_full_name_buss" value="{{ old('seller_full_name_buss') }}" placeholder="Business Name">
    </div>

    {{-- <div class= " form-group mb-3">
        <label for="formFile" class="form-label">Upload Trade License</label><br>
        <input class="form-control" type="file" id="formFile" name="seller_trade_license" accept="application/pdf,application/vnd.ms-excel" required>
        {{-- type="file" class="form-control"
    </div> --}}

    <div class="mb-2 business_type_company">
        <label for="formFile" class="form-label">Upload Trade License</label><br>
        <input type="file" class="form-control" id="formFile" name="seller_trade_license" accept="application/pdf,application/vnd.ms-excel">
    </div>

    <div class="form-group mt-2 business_type_company">
        <label>Trade Expiry Date</label>
        <input type="date" class="form-control" value="{{ old('seller_trade_exp_dt') }}" name="seller_trade_exp_dt">
    </div>

    <div class="mt-2 mb-2 initial_hidden_fields">
        <label id="profile_image_label" for="sellerprofile" class="form-label" ></label><br>
        <input type="file" class="form-control" id="sellerprofile" name="SellerProfile" required  accept="image/png, image/jpeg">
    </div>


    <div class="form-group mt-2 initial_hidden_fields">
        <label for="exampleFormControlTextarea1" id="abt_seller_label">About You</label>
        <textarea class="form-control" name="sellerabout" required id="exampleFormControlTextarea1" rows="3" value="{{ old('sellerabout')}}"></textarea>
    </div>


  <div class="mb-4">
    <div class="form-check">
      <label class="form-check-label text-muted">
        <input type="checkbox" class="form-check-input"> I agree to all Terms & Conditions </label>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">SIGN UP</button>
  </div>
  <div class="text-center mt-4 font-weight-light"> Already have an account? <a href="{{ route('seller.login') }}" class="text-primary">Login</a>
  </div>
</form>
@endsection

@section('pagescript')
<script src="{{ asset('assets/js/seller.js') }}"></script>
@endsection
