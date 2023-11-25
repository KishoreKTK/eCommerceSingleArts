<table class="table table-bordered">
    <thead>
        <tr>
            <th># </th>
            <th>Name</th>
            <th>Category</th>
            <th>Qty</th>
            <th>Price</th>
            {{-- <th>Featured</th> --}}
            <th>View</th>
            <th>Status</th>
            <th>Custom</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if (count($products) >0)
        @foreach ($products as $key=>$product)
        <tr>
            <td>{{ $key + $products->firstItem() }}</td>
            <td>
                <img src= "{{ asset($product->image) }}" alt=""> {{ $product->name }}
            </td>
            <td>{{ $product->categoryname }}</td>
            <td>{{ $product->product_price }}</td>
            <td>{{ $product->quantities }}</td>
            {{-- <td>
                @if($product->is_featured == '1')
                    <form action="{{ route('seller.featuredproducts') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="is_featured" value="0">
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="mdi mdi mdi-star text-warning icon-sm" aria-hidden="true"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('seller.featuredproducts') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="is_featured" value="1">
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="mdi mdi-star-outline text-secondary icon-sm" aria-hidden="true"></i>
                        </button>
                    </form>
                @endif
            </td> --}}
            <td>
                <a  class="btn btn-outline-info btn-sm"
                    href="{{ Route('seller.ProductDetails',[$product->id]) }}">
                    <i class="mdi mdi-eye" aria-hidden="true"></i>
                </a>
            </td>

            <td>
                @if ($product->status == '1')
                <span class="badge badge-success">Active</span>
                @else
                <span class="badge badge-danger">Inactive</span>
                @endif
            </td>
            <td><a href="{{ Route('seller.CutomPrice',[$product->id]) }}" class="btn btn-inverse-primary btn-sm">Custom Price</a></td>
            <td>
                <div class="d-flex flex-row justify-content-center">
                    <button class="btn btn-outline-warning btn-sm ">
                        <i class="mdi mdi-account-key" aria-hidden="true"></i>
                    </button>
                    @if ($product->status == '1')
                        <form action="{{ Route('seller.productstatus') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="active_status" value="0">
                            <button type="submit" class="btn mx-1 btn-outline-success btn-sm">
                                <i class="mdi mdi-lock-open" aria-hidden="true"></i>
                            </button>
                        </form>
                    @else
                        <form action="{{  Route('seller.productstatus') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="active_status" value="1">
                            <button class="btn mx-1 btn-outline-secondary btn-sm">
                                <i class="mdi mdi mdi-lock" aria-hidden="true"></i>
                            </button>
                        </form>
                    @endif
                    <form action="{{  Route('seller.productstatus') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="active_status" value="2">
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="mdi mdi-delete" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
        @else
        <tr><td colspan="7"><center>No Products Found</center></td></tr>
        @endif
    </tbody>
</table>
<div class="mt-2 float-right">
    @if(count($products) != 0)
    {!! $products->links() !!}
    @endif
</div>
