<?php
use Illuminate\Support\Carbon;
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>OrderId</th>
            <th>Customer</th>
            <th>Price</th>
            <th>Status</th>
            {{-- <th>Payment Method</th>
            <th>Payment Status</th> --}}
            <th>Order Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($GetOrderDetails) != 0)
            @foreach($GetOrderDetails as $key => $order)
            <tr>
                <th>{{ $key + $GetOrderDetails->firstItem() }}</th>
                <td>{{ $order->order_id }}</td>
                <td>{{ $order->username }}</td>
                <td>{{ $order->grand_total }}</td>
                <td><label class="badge badge-info">{{ $order->statusname }}</label></td>
                {{-- @if($order->payment_type == '1')
                <td>Cash on Delivary</td>
                @elseif($order->payment_type == '2')
                <td>Credit Card</td>
                @elseif($order->payment_type == '3')
                <td>Debit Card</td>
                @endif
                <td>Payment Pending</td> --}}
                <td>{{ Carbon::parse($order->created_at)->toFormattedDateString() }}</td>
                <td><a href="{{ route('admin.OrderDetail',$order->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                    {{-- <a href="#" class="btn btn-sm btn-outline-danger">Cancel</a></td> --}}
            </tr>
            @endforeach
        @else
        <tr><td colspan="7"><center>No Orders Placed Yet.</center></td></tr>
        @endif
    </tbody>
</table>
<div class="mt-2 float-right">
    @if(count($GetOrderDetails) != 0)
    {!! $GetOrderDetails->links() !!}
    @endif
</div>
