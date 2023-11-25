@extends('layouts.dashboard_layout')
@section('pagecss')

@endsection
@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <a href="{{ Route('admin.home') }}"><span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>
            </span></a>Order Managment
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ Route('admin.home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ Route('admin.OrderList') }}">Order List</a></li>
                <li class="breadcrumb-item active" aria-current="page">Order Detail</li>
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
        <div class="col-md-4 grid-margin">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Order Details</h4>
                <table class="table table-light table-bordered">
                    <tbody>
                        <tr>
                            <td>ID</td>
                            <td>{{ $order_det['order']->order_id }}</td>
                        </tr>
                        <tr>
                            <td>STATUS</td>
                            <td><label for="orderstatus" class="badge badge-info">{{ $order_det['order']->statusname }}</label></td>
                        </tr>
                        <tr>
                            <td>TOTAL</td>
                            <td>{{ $order_det['order']->grand_total }}</td>
                        </tr>
                        <tr>
                            <td>DATE</td>
                            <td>{{ $order_det['order']->created_at }}</td>
                        </tr>
                    </tbody>
                </table>
              </div>
            </div>
        </div>

        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                  <h4>Ordered By</h4>
                  <table class="table table-light table-bordered">
                      <tbody>
                          <tr>
                              <td>Name</td>
                              <td>{{ $order_det['user_det']->name }}</td>
                          </tr>
                          <tr>
                              <td>Email</td>
                              <td>{{ $order_det['user_det']->email }}</td>
                          </tr>
                          <tr>
                              <td>Mobile</td>
                              <td>{{ $order_det['user_det']->phone }}</td>
                          </tr>
                      </tbody>
                  </table>
                </div>
            </div>
        </div>

        <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                  <h4>Shipping Address</h4>
                  <div class="alert alert-info" >
                      <address>
                          <p class="font-weight-bold">{{ $order_det['address']->first_name }},</p>
                          <p> {{ $order_det['address']->phone_num }},</p>
                          <p> {{ $order_det['address']->villa }},</p>
                          <p> {{ $order_det['address']->address }}, {{ $order_det['address']->city }}, </p>
                      </address>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Orders & Tracks</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Seller</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Track</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                                $ordqty = $order_det['qty_det']->toArray();
                            ?>
                            @if(count($ordqty) > 0)

                            @foreach ($ordqty as $key=>$qty)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $qty->sellername }}</td>
                                    <td>{{ $qty->prod_name }}</td>
                                    <td>{{ $qty->prod_qty }}</td>
                                    <td>{{ $qty->total_amount }}</td>
                                    <td> <label class="badge badge-info" for="orderstatus">{{ $qty->orderstatusname }}</label></td>
                                    <td>
                                        <button type="button" class="btn btn-outline btn-primary btn-sm get_orderstrackdet" data-suborderid="{{ $qty->suborderid }}">View</button>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4"><center>No Record Found</center></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

              </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('pagescript')

@endsection
