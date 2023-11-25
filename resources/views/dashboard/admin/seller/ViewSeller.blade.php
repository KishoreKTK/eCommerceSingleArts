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
                    <div class="d-flex mb-4 justify-content-end">
                        <ul class="nav nav-tabs card-header-tabs" id="seller-detailed-list" role="tablist">
                            <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link active" href="#sellerproducts" role="tab" aria-controls="sellerproducts" aria-selected="true">Products</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link"  href="#sellercategories" role="tab" aria-controls="sellercategories" aria-selected="false">Categories</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link" href="#seller_reviews" role="tab" aria-controls="seller_reviews" aria-selected="false">Reviews</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link" href="#seller_transactions" role="tab" aria-controls="seller_transactions" aria-selected="false">Trasanctions</a>
                            </li>

                            {{-- <li class="nav-item">
                                <a class="btn btn-outline-primary nav-link" href="#seller_activities" role="tab" aria-controls="seller_activities" aria-selected="false">Activities</a>
                            </li> --}}
                        </ul>
                    </div>
                    <div class="tab-content mt-3">

                        {{-- Product Details --}}
                        <div class="tab-pane active" id="sellerproducts" role="tabpanel">
                            <h4 class="card-title">Product Lists</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th># </th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($product_list) > 0)
                                            @foreach ($product_list as $key=>$product)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td><img src="{{ asset($product->image) }}" alt=""></td>
                                                <td>
                                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                        <a class="text-primary" href="{{ Route('admin.ProductDetails',[$product->id]) }}" target="_blank" style="text-decoration: none;">{{ $product->name }}</a>
                                                        @if($product->is_featured == '1')
                                                            <i class="mdi mdi mdi-star text-warning icon-sm" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Featured Product"></i>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $product->categoryname }}</td>
                                                <td>{{ $product->price }}</td>
                                                <td>{{ $product->available_qty }}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7">
                                                    <center>No Products from this Seller Yet</center>
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Seller Categories --}}
                        <div class="tab-pane" id="sellercategories" role="tabpanel" aria-labelledby="sellercategories-tab">
                            <h4 class="card-title">Seller Category</h4>
                            <div class="row">
                                @if(count($seller_categories) > 0)
                                @foreach ($seller_categories as $cat)
                                <div class="col-xl-4 col-sm-12 stretch-card grid-margin">
                                    <div class="card border border-warning rounded">
                                        <img src="{{ asset($cat->image_url) }}" class="w-100" alt="Product 1">
                                        <div class="px-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h4 class="mt-1"><u>{{ $cat->name }}</u></h4>
                                            </div>
                                            <p>{{ $cat->description }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="col-12 justify-content-md-center">
                                    <p><center>No Categories Found</center></p>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Seller Reviews --}}
                        {{-- <div class="tab-pane" id="seller_reviews" role="tabpanel" aria-labelledby="seller_reviews-tab">
                            <h4 class="card-title">Seller Reviews</h4>
                            <p>No Reviews about this Seller Yet</p>
                        </div> --}}

                        {{-- Seller Transactions --}}
                        <div class="tab-pane" id="seller_transactions" role="tabpanel" aria-labelledby="seller_transactions-tab">
                            <h4 class="card-title">Seller Trasactions</h4>
                            <p class=" d-flex justify-content-md-center">No Transactions from this Seller Yet</p>
                        </div>

                        {{-- Seller Activities --}}
                        {{-- <div class="tab-pane" id="seller_activities" role="tabpanel" aria-labelledby="seller_activities-tab">
                            <h4 class="card-title">Seller Activities</h4>
                            <p>No Activities by this Seller Yet</p>
                        </div> --}}

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
@endsection

@section('pagescript')
<script src="{{ asset('assets/js/seller.js') }}"></script>
@endsection
