@extends('layouts.dashboard_layout')
@section('pagecss')


@endsection
@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>
            </span> Product Management
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ Route('admin.home') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Product Lists</li>
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
                    <div class="d-flex mb-2">
                        <div class="p-2 flex-grow-1">
                            <h4 class="card-title">Product Lists</h4>
                        </div>
                        <div class="p-2">
                            <a href="#" class="btn btn-outline-secondary btn-fw btn-sm">
                                <i class="mdi mdi-download btn-icon-prepend"></i> Download Report</a>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('admin.AddProduct') }}" class="btn btn-outline-primary btn-fw btn-sm">
                                <i class="mdi mdi-account-plus btn-icon-prepend"></i> Add New Product</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th># </th>
                                    <th>Name</th>
                                    <th>Seller</th>
                                    <th>Category</th>
                                    <th>Featured</th>
                                    <th>Quantity</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if (count($products) >0)
                                @foreach ($products as $key=>$product)
                                <tr>
                                    <td>{{ $key + $products->firstItem() }}</td>
                                    <td>
                                        <a class="text-primary" href="{{ Route('admin.ProductDetails',[$product->id]) }}" style="text-decoration: none;">
                                        <img src= "{{ asset($product->image) }}" alt=""> {{ $product->name }}</a>
                                    </td>
                                    <td>{{ $product->sellername }}</td>
                                    <td>{{ $product->categoryname }}</td>
                                    <td>
                                            @if($product->is_featured == '1')
                                                <form action="{{ Route('admin.featuredproducts') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="is_featured" value="0">
                                                    <button class="btn btn-outline-danger btn-sm">
                                                        <i class="mdi mdi mdi-star text-warning icon-sm" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ Route('admin.featuredproducts') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="is_featured" value="1">
                                                    <button class="btn btn-outline-secondary btn-sm">
                                                        <i class="mdi mdi-star-outline text-secondary icon-sm" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @endif
                                    </td>
                                    <td>{{ $product->quantities }}</td>

                                    <td>
                                        <div class="d-flex flex-row justify-content-center">
                                            <button class="btn btn-outline-warning btn-sm mr-2">
                                                <i class="mdi mdi-account-key" aria-hidden="true"></i>
                                            </button>
                                            @if ($product->status == '1')
                                                <form action="{{ Route('admin.productstatus') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="active_status" value="0">
                                                    <button class="btn btn-outline-success btn-sm"  data-toggle="tooltip" data-placement="top" title="Active Product">
                                                        <i class="mdi mdi-lock-open" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{  Route('admin.productstatus') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="active_status" value="1">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"  data-toggle="tooltip" data-placement="top" title="Inactive Product">
                                                        <i class="mdi mdi-lock" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr><td colspan="7"><center>No Products Found</center></td></tr>
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
