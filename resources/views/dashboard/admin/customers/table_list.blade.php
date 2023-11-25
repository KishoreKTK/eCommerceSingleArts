<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Profile</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact</th>
            <th>View</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($customer_list) != 0)
            @foreach($customer_list as $key => $user)
            <tr>
                <th>{{ $key + $customer_list->firstItem() }}</th>
                <td>
                    @if(is_null($user->profile))
                    <img src="{{ asset('assets/images/faces/blankuser.png') }}" alt="No Profile Image">
                    @else
                    <img src="{{ asset($user->profile) }}" alt="{{ $user->name }}">
                    @endif
                </td>
                <td>{{ $user->name }}</td>
                <td><a href = "mailto: {{ $user->email }}" class="badge badge-secondary">{{ $user->email }}</a></td>
                <td>{{ $user->phone }}</td>
                <td>
                    <a href="{{ Route('admin.CustomerDetails',[$user->id]) }}" class="btn btn-outline-info btn-sm">
                        <i class="mdi mdi-eye text-info" aria-hidden="true"></i>
                    </a>
                    {{-- <a href="#"><i class=" icon-md text-info"></i></a> --}}
                </td>
                <td>
                    @if ($user->is_active == '1')
                    <label class="badge badge-success">Active</label>
                    @else
                    <label class="badge badge-danger">InActive</label>
                    @endif
                </td>
                <td>
                    <div class="d-flex flex-row">
                        {{-- <button class="btn btn-outline-warning btn-sm mx-2">
                            <i class="mdi mdi-account-key" aria-hidden="true"></i>
                        </button> --}}
                        @if ($user->is_active == '1')
                            <form action="{{ Route('admin.CustomerStatus') }}" method="POST">
                                @csrf
                                <input type="hidden" name="userid" value="{{ $user->id }}">
                                <input type="hidden" name="active_status" value="0">
                                <button  type="submit"  class="btn btn-outline-danger btn-sm">
                                    <i class="mdi mdi mdi-lock" aria-hidden="true"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{  Route('admin.CustomerStatus') }}" method="POST">
                                @csrf
                                <input type="hidden" name="userid" value="{{ $user->id }}">
                                <input type="hidden" name="active_status" value="1">
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    <i class="mdi mdi-lock-open" aria-hidden="true"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                    {{-- <a href="#"><i class="mdi mdi-tooltip-edit icon-md text-warning mr-2"></i></a>
                    @if($user->is_active == '1')
                    <a href="#"><i class="mdi mdi-account icon-md text-success"></i></a>
                    @else
                    <a href="#"><i class="mdi mdi-account-off icon-md text-danger"></i></a> --}}
                    {{-- @endif --}}
                </td>
            </tr>
            @endforeach
        @else
        <tr><td colspan="7"><center>No Customers Yet.</center></td></tr>
        @endif
    </tbody>
</table>
<div class="mt-2 float-right">
    @if(count($customer_list) != 0)
    {!! $customer_list->links() !!}
    @endif
</div>
