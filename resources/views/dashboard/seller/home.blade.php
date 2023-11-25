<?php
use Illuminate\Support\Carbon;
?>
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
        <div class="d-lg-flex w-100 justify-content-between align-items-center">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-home"></i>
                </span> Dashboard
            </h3>
            <p class="mb-0 text-muted">{{ Carbon::now()->format('l \\, jS \\of F Y'); }}</p>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-4 col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="{{ asset($dashboard['seller']['profile']->sellerprofile) }}" alt="Seller" class="rounded-circle p-1 bg-primary" width="150">
                                <div class="mt-3">
                                    <h4>{{ $dashboard['seller']['profile']->sellername }}</h4>
                                    <p class="text-secondary mb-1">{{ $dashboard['seller']['profile']->selleremail }}</p>
                                    <p class="text-muted font-size-sm">
                                            -
                                    </p>
                                </div>
                            </div>
                            <hr class="my-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap" data-toggle="tooltip" data-placement="top" title="Business Name">
                                    <h6 class="mb-0">
                                        <i class="mdi mdi-briefcase" aria-hidden="true"></i>
                                    </h6>
                                    <span class="text-secondary">
                                        {{ $dashboard['seller']['profile']->seller_full_name_buss }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap" data-toggle="tooltip" data-placement="top" title="Business Category">
                                    <h6 class="mb-0">
                                        <i class="mdi mdi-lan" aria-hidden="true"></i>
                                    </h6>
                                    <span class="text-secondary">
                                        {{ $dashboard['seller']['profile']->seller_buss_type }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap" data-toggle="tooltip" data-placement="top" title="Trade License Expiry Date">
                                    <h6 class="mb-0">
                                        <i class="mdi mdi-calendar-clock" aria-hidden="true"></i>
                                    </h6>
                                    <span class="text-secondary">
                                        {{ $dashboard['seller']['profile']->seller_trade_exp_dt }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap" data-toggle="tooltip" data-platcement="top" title="Seller Approved Date">
                                    <h6 class="mb-0">
                                        <i class="mdi mdi-calendar-today" aria-hidden="true"></i>
                                    </h6>
                                    <span class="text-secondary">
                                        {{ $dashboard['seller']['profile']->created_at }}
                                    </span>
                                </li>
                            </ul>
                            <div class="mt-2 d-flex justify-content-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-block view_trade_license" data-seller_name="Seller1"
                                data-trade_licenseurl="{{ $dashboard['seller']['profile']->seller_trade_license }}">View License</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-sm-12">
            <div class="row">
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-success card-img-holder text-white">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Total Products<i class="mdi mdi-diamond mdi-24px float-right"></i>
                            </h4>
                            <h2>{{ $dashboard['counts']['products'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-info card-img-holder text-white">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Total Orders <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                            </h4>
                            <h2>{{ $dashboard['counts']['orders'] }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 stretch-card grid-margin">
                    <div class="card bg-gradient-danger card-img-holder text-white">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">Total Revenue <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                            </h4>
                            <h2>{{ $dashboard['counts']['revenues'] }} AED</h2>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-md-5 grid-margin grid-margin-lg-0  stretch-card">
                    <div class="card">
                        <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <h4 class="card-title">Restaurant Rating</h4>
                        <canvas id="restaurant-rating" width="488" height="243" style="display: block; height: 195px; width: 391px;" class="chartjs-render-monitor"></canvas>
                        <div id="restaurant-rating-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"><ul><li><span class="legend-dots" style="background:linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))"></span>Food<span class="float-right">30%</span></li><li><span class="legend-dots" style="background:linear-gradient(to right, rgba(6, 185, 157, 1), rgba(132, 217, 210, 1))"></span>Service<span class="float-right">30%</span></li><li><span class="legend-dots" style="background:linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))"></span>Waiting Time<span class="float-right">40%</span></li></ul></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 grid-margin grid-margin-lg-0  stretch-card">
                    <div class="card">
                        <div class="card-body">
                        <h4 class="card-title">Top Menus</h4>
                        <div class="wrapper d-flex align-items-start justify-content-between align-items-center py-2 border-bottom">
                            <div class="d-flex align-items-center">
                            <img class="img-sm" src="../../assets/images/dashboard/food/food_1.jpg" alt="profile">
                            <div class="wrapper ml-3">
                                <h5 class="ml-1 mb-1 font-weight-normal">Pizza slices</h5>
                            </div>
                            </div>
                            <span class="font-weight-semi-bold">$268</span>
                        </div>
                        <div class="wrapper d-flex align-items-start justify-content-between align-items-center py-2 border-bottom">
                            <div class="d-flex align-items-center">
                            <img class="img-sm" src="../../assets/images/dashboard/food/food_2.jpg" alt="profile">
                            <div class="wrapper ml-3">
                                <h5 class="ml-1 mb-1 font-weight-normal">grill bbq chicken</h5>
                            </div>
                            </div>
                            <span class="font-weight-semi-bold">$680</span>
                        </div>
                        <div class="wrapper d-flex align-items-start justify-content-between align-items-center py-2 border-bottom">
                            <div class="d-flex align-items-center">
                            <img class="img-sm" src="../../assets/images/dashboard/food/food_3.jpg" alt="profile">
                            <div class="wrapper ml-3">
                                <h5 class="ml-1 mb-1 font-weight-normal">fish with vegetables</h5>
                            </div>
                            </div>
                            <span class="font-weight-semi-bold">$143</span>
                        </div>
                        <div class="wrapper d-flex align-items-start justify-content-between align-items-center py-2 border-bottom">
                            <div class="d-flex align-items-center">
                            <img class="img-sm" src="../../assets/images/dashboard/food/food_4.jpg" alt="profile">
                            <div class="wrapper ml-3">
                                <h5 class="ml-1 mb-1 font-weight-normal">Italian spaghetti </h5>
                            </div>
                            </div>
                            <span class="font-weight-semi-bold">$564</span>
                        </div>
                        <div class="wrapper d-flex align-items-start justify-content-between align-items-center py-2 border-bottom">
                            <div class="d-flex align-items-center">
                            <img class="img-sm" src="../../assets/images/dashboard/food/food_5.jpg" alt="profile">
                            <div class="wrapper ml-3">
                                <h5 class="ml-1 mb-1 font-weight-normal">Sandwich with chicken </h5>
                            </div>
                            </div>
                            <span class="font-weight-semi-bold">$187</span>
                        </div>
                        <div class="wrapper d-flex align-items-start justify-content-between align-items-center py-2 border-bottom">
                            <div class="d-flex align-items-center">
                            <img class="img-sm" src="../../assets/images/dashboard/food/food_6.jpg" alt="profile">
                            <div class="wrapper ml-3">
                                <h5 class="ml-1 mb-1 font-weight-normal">Grilled toast </h5>
                            </div>
                            </div>
                            <span class="font-weight-semi-bold">$228</span>
                        </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title">Recent Orders</h4>
                                <a class="text-info" href="{{ route('seller.OrderList') }}" style="text-decoration: none;">View all</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Status</th>
                                            <th>Last Date</th>
                                            <th>Order Id</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dashboard['order_vendors'] as $key=>$order)
                                        <tr>
                                            <td><img src="{{ asset($order->productimage) }}" class="mr-2"
                                                alt="image">{{ $order->productname }}</td>
                                            <td>{{ $order->prod_qty }}</td>
                                            <td><label class="badge badge-gradient badge-info">{{ $order->statusname }}</label></td>
                                            <td>{{ Carbon::parse($order->updated_at)->toFormattedDateString() }}</td>
                                            <td>{{ $order->orderid }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="modal fade" id="ViewLicenceModel" tabindex="-1" aria-labelledby="ViewLicenceModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ViewLicenceModelLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="ViewLicencePdf">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('pagescript')
<script src="{{ asset('assets/js/seller.js') }}"></script>
@endsection
