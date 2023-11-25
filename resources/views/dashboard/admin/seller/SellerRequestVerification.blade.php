@extends('layouts.dashboard_layout')
@section('pagecss')


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
              <li class="breadcrumb-item"><a href="{{ Route('admin.sellerrequest') }}">Seller Request Lists</a></li>
              <li class="breadcrumb-item active" aria-current="page">Seller</li>
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
        <div class="col-lg-4 grid-margin stretch-card">
            @include('dashboard.admin.seller.seller_profile_card')
        </div>
        <div class="col-lg-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if($seller_det->seller_trade_license != null)
                    <iframe src="{{ asset($seller_det->seller_trade_license) }}" frameborder="1"
                        allowfullscreen="true"  width="700" height="600"></iframe>
                    @else
                        <div class="row">
                            <div class="col-12 text-center">
                                No Trade License
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Seller Request Approval Form</h4>
                    <form action="{{  Route('admin.SellerApproval') }}" method="POST" class="form-sample">
                        @csrf
                        <input type="hidden" name="sellerid" value="{{ $seller_det->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Membership</label>
                                    <div class="col-sm-4">
                                        <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input check_approval_status" name="sellermembership" id="membershipRadios1" value="1"> Approve <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input check_approval_status" name="sellermembership" id="membershipRadios2" value="2"> Reject <i class="input-helper"></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="commission_field">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Commission</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="commission" value="{{ old('commission') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="remarks_n_submission_field">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Remarks</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="actionremarks" id="exampleTextarea1" rows="4" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                            </div>
                        </div>
                    </form>
                  </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pagescript')
<script>
    $(document).ready(function(){
        $("#commission_field").hide();
        $("body").on("click",".check_approval_status",function(){
            approval_type = $(this).val();
            if(approval_type == 1){
                $("#commission_field").show();
            }
            else{
                $("#commission_field").hide();
            }
        });
    });
</script>
@endsection
