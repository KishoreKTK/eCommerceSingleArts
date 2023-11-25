@extends('layouts.dashboard_layout')
@section('pagecss')


@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <a href="{{ Route('admin.home') }}"><span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>
            </span></a>Customer Managment
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ Route('admin.home') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ Route('admin.CustomerList') }}">Customer Lists</a></li>
              <li class="breadcrumb-item active" aria-current="page">Customer Detail</li>
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
        <div class="col-lg-4 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">
                        @if(is_null($customer->profile))
                        <img src="{{ asset('assets/images/faces/blankuser.png') }}" alt="Customer" class="rounded-circle p-1 bg-primary" width="150">

                        @else
                        <img src="{{ asset($customer->profile) }}" class="rounded-circle p-1 bg-primary" width="150" alt="{{ $customer->name }}">

                        {{-- <img src="{{ $customer->profile }}" alt="Customer" class="rounded-circle p-1 bg-primary" width="150"> --}}

                        @endif
                        <div class="mt-3">
                            <h4>{{ $customer->name }}</h4>
                            <p class="text-secondary mb-1">
                                <a href = "mailto: {{ $customer->email }}" class="badge badge-secondary">{{ $customer->email }}</a></p>
                            <p class="text-secondary mb-1">
                                {{ $customer->phone }}</p>
                            <p class="text-muted font-size-sm">
                                @if(is_null($customer->about))
                                -
                                @else
                                    {{ $customer->about }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-4 justify-content-end">
                        <ul class="nav nav-tabs card-header-tabs" id="customer-detailed-list" role="tablist">
                            {{-- <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link " href="#UserDetails"
                                    role="tab" aria-controls="UserDetails" aria-selected="true">Details</a>
                            </li> --}}

                            <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link active" href="#UserAddress"
                                    role="tab" aria-controls="UserAddress" aria-selected="true">Address</a>
                            </li>

                            <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link" href="#CustomerSavedProducts"
                                role="tab" aria-controls="CustomerSavedProducts" aria-selected="false">Saved Products</a>
                            </li>

                            <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link" href="#CustomerOrders"
                                    role="tab" aria-controls="CustomerOrders" aria-selected="true">Orders</a>
                            </li>

                            <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link" href="#Customer_Collections"
                                    role="tab" aria-controls="Customer_Collections" aria-selected="false">Collections</a>
                            </li>

                            {{-- <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link" href="#Customer_reviews"
                                    role="tab" aria-controls="Customer_reviews" aria-selected="false">Reviews</a>
                            </li> --}}

                            {{-- <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link" href="#Customer_activities" role="tab"
                                aria-controls="Customer_activities" aria-selected="false">Activities</a>
                            </li> --}}
                        </ul>
                    </div>
                    <div class="tab-content mt-5">

                        {{-- <div class="tab-pane " id="UserDetails" role="tabpanel">
                            <h4 class="card-title">Customer Details </h4>
                        </div> --}}

                        <div class="tab-pane active" id="UserAddress" role="tabpanel" aria-labelledby="UserAddress-tab">
                            @if(count($customer_address) > 0)
                            <div class="row">
                                @foreach ($customer_address as $addr)
                                <div class="col-xl-4 col-sm-6 stretch-card grid-margin">
                                    <div class="card border border-primary rounded">
                                        <div class="card-body">
                                            <address>
                                                <p class="font-weight-bold">{{ $addr->first_name }}</p>
                                                <p> {{ $addr->phone_num }},</p>
                                                <p> {{ $addr->villa }}, </p>
                                                <p> {{ $addr->address }}, </p>
                                                <p> {{ $addr->city }}</p>
                                            </address>
                                            @if($addr->default_addr == '1')
                                            <hr>
                                            <p class="text-center">
                                                <label class="badge badge-info">Default Address</label>
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                                <p class="text-center mt-3">No Address Added</p>
                            @endif
                        </div>

                        <div class="tab-pane" id="CustomerSavedProducts" role="tabpanel" aria-labelledby="CustomerSavedProducts-tab">
                            @if (count($saved_products) > 0)
                                <div class="row">
                                    @foreach ($saved_products as $saved_prt)
                                    <div class="col-xl-4 col-sm-6 stretch-card grid-margin">
                                        <div class="card border border-primary rounded">
                                            <a href="{{ Route('admin.ProductDetails',[$saved_prt->id]) }}" class="product-item" style="text-decoration: none; color:inherit;">
                                                <img src="{{ asset($saved_prt->image) }}" class="w-100" alt="{{ $saved_prt->name }}">
                                                <div class="px-3 py-4">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h4 class="m-0">{{ $saved_prt->name }}</h4>
                                                        </div>
                                                        <div class="badge badge-gradient-info">{{ $saved_prt->price }}</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center">No Saved Products Yet</p>
                            @endif
                        </div>

                        <div class="tab-pane" id="CustomerOrders" role="tabpanel" aria-labelledby="CustomerOrders-tab">
                            <p class="text-center">No Orders Yet</p>
                        </div>

                        <div class="tab-pane" id="Customer_Collections" role="tabpanel" aria-labelledby="Customer_Collections-tab">
                            <p class="text-center">No Collections Yet</p>
                        </div>

                        <div class="tab-pane" id="Customer_reviews" role="tabpanel" aria-labelledby="Customer_reviews-tab">
                            <p class="text-center">No Reviews Yet</p>
                        </div>

                        {{-- <div class="tab-pane" id="Customer_activities" role="tabpanel" aria-labelledby="Customer_activities-tab">
                            <p class="text-center">No Activities by this Customer Yet</p>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('pagescript')
<script>
$(document).ready(function()
{
    $('#customer-detailed-list a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
});
</script>
@endsection
