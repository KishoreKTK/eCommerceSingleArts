@extends('layouts.dashboard_layout')
@section('pagecss')
<style>
.modal-lg {
    max-width: 50% !important;
}
</style>

@endsection
@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>
            </span> Seller Management
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ Route('admin.home') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ Route('admin.SellerList') }}">Seller Lists</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create New Seller</li>
            </ol>
        </nav>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ $message }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create New Seller </h4>
                    <form class="pt-3" action="{{ route('admin.SellerCreate') }}" method="post" autocomplete="off" enctype="multipart/form-data">
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
                            <input type="text" class="form-control" name="mobile" placeholder="Enter Phone Number" required>
                        </div>

                        <div class="form-group">
                            <p> Business Type</p>
                            <label class="radio-inline mx-2">
                                <input type="radio" class="seller_buss_type_class" name="seller_buss_type" value="Individual"> Individual</label>
                            <label class="radio-inline mx-2">
                                <input type="radio" class="seller_buss_type_class" name="seller_buss_type" value="Business" checked> Business</label>
                        </div>

                        <div class="form-group business_type_company">
                            <label for="exampleFormControlInput2">Business Name</label>
                            <input type="name" class="form-control" id="exampleFormControlInput2" name="seller_full_name_buss" value="{{ old('seller_full_name_buss') }}" placeholder="Business Name">
                        </div>

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

                        <div class="form-group">
                            <label for="exampleFormControlInput3">Commission</label>
                            <input type="text" class="form-control" id="exampleFormControlInput3" name="commission" value="{{ old('commission') }}" placeholder="Commission">
                        </div>

                        <button class="btn btn-gradient-primary float-right">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pagescript')
<script src="{{ asset('assets/js/seller.js') }}"></script>
@endsection
