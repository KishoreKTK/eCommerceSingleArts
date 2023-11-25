@extends('layouts.dashboard_layout')
@section('pagecss')
<style>
.card-horizontal {
    display: flex;
    flex: 1 1 auto;
}
.modal-lg {
    max-width: 75% !important;
}

/* .card-horizontal {
  flex-direction: row;
} */

/* .card-horizontal img {
    width: 218px;
    height: 268px;
    object-fit: cover ;
} */
.card-horizontal img {
  width: 50%;
}
</style>

@endsection
@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>
            </span> Category Management
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="http://127.0.0.1:8000/admin">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
        </nav>
    </div>


    <div class="row">
        {{-- Add New Category --}}
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4>Add New Category</h4>
                            <form class="forms-sample" action="{{ route('admin.NewCategory') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>{{ $message }}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                                @if ($message = Session::get('fail'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>{{ $message }}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="exampleInputUsername1">Image</label>
                                    <input type="file" class="form-control mb-2 mr-sm-2" name="image" id="" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputUsername1">Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="exampleInputUsername1" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputUsername1">Description</label>
                                    <textarea type="text" name="description" class="form-control mb-2 mr-sm-2" rows="10" cols="50" required>{{ old('description') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Category Lists --}}
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4>Category List</h4>
                    <div class="table-responsive">
                        <table class="table table-inverse">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    {{-- <th>Uploaded</th> --}}
                                    <th>Products</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($categories) > 0)
                                    @foreach ($categories as $key=>$category)
                                        <tr>
                                            <td scope="row">{{ $key+1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img class="img-sm" src="{{ asset($category->image_url) }}" alt="{{ $category->name }}">
                                                    <div class="wrapper ml-3">
                                                        <h5 class="ml-1 mb-1 font-weight-normal">{{ $category->name }}</h5>
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- <td>
                                                @if(!empty($category->sellername))
                                                    <a  href="{{Route('admin.SellerDetail',[$category->uploaded_by])}}" class="badge badge-gradient-primary">{{ $category->sellername }}</label></a>
                                                @else
                                                    <span> - </span>
                                                @endif
                                            </td> --}}
                                            <td>{{ $category->ProductCount }}</td>
                                            <td id="category_action_td_{{ $category->id }}">
                                                @if($category->is_active != '2')
                                                <div class="d-flex flex-row">
                                                    <button type="button" class="btn btn-inverse-info btn-icon viewcatdet"
                                                            data-category_id="{{ $category->id }}"
                                                            data-categoryname="{{ $category->name }}"
                                                            data-categorydesc="{{ $category->description }}"
                                                            data-cat_img="{{ asset($category->image_url) }}"
                                                            data-cat_uploader="{{ $category->sellername }}"
                                                            data-cat_products="{{ $category->ProductCount }}"
                                                            data-cat_created_dt="{{ $category->created_at }}"
                                                            data-cat_remarks="{{ $category->remarks }}"
                                                            data-cat_reason="{{ $category->reason }}">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-inverse-warning btn-icon mx-1 edit_category"
                                                        data-category_id="{{ $category->id }}"
                                                        data-categoryname="{{ $category->name }}"
                                                        data-categorydesc="{{ $category->description }}"
                                                        data-cat_img="{{ asset($category->image_url) }}"
                                                        data-cat_uploader="{{ $category->sellername }}"
                                                        data-cat_products="{{ $category->ProductCount }}"
                                                        data-cat_created_dt="{{ $category->created_at }}"
                                                        data-cat_remarks="{{ $category->remarks }}"
                                                        data-cat_reason="{{ $category->reason }}">
                                                        <i class="mdi mdi-lead-pencil"></i>
                                                    </button>
                                                    @if($category->is_active == '1')
                                                    <form action="{{  Route('admin.ChangeCatStatus') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="cat_id" value="{{ $category->id }}">
                                                        <input type="hidden" name="is_active" value="0">
                                                        <button type="submit" class="btn btn-inverse-secondary btn-icon mx-1">
                                                            <i class="mdi mdi-lock"></i>
                                                        </button>
                                                    </form>

                                                    @elseif($category->is_active == '0')
                                                    <form action="{{  Route('admin.ChangeCatStatus') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="cat_id" value="{{ $category->id }}">
                                                        <input type="hidden" name="is_active" value="1">
                                                        <button type="submit" class="btn btn-inverse-success btn-icon mx-1">
                                                            <i class="mdi mdi-lock-open"></i>
                                                        </button>
                                                    </form>
                                                    @endif

                                                    <form action="{{  Route('admin.ChangeCatStatus') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="cat_id" value="{{ $category->id }}">
                                                        <input type="hidden" name="is_active" value="4">
                                                        <button type="submit" class="btn btn-inverse-danger btn-icon">
                                                            <i class="mdi mdi-delete" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                @else
                                                    <button type="button" class="btn btn-outline-danger
                                                        btn-block btn-icon-text btn-sm verify_category"
                                                        data-category_id="{{ $category->id }}"
                                                        data-categoryname="{{ $category->name }}"
                                                        data-categorydesc="{{ $category->description }}"
                                                        data-cat_img="{{ asset($category->image_url) }}"
                                                        data-cat_uploader="{{ $category->sellername }}"
                                                        data-cat_products="{{ $category->ProductCount }}"
                                                        data-cat_created_dt="{{ $category->created_at }}"
                                                        data-cat_remarks="{{ $category->remarks }}"
                                                        data-cat_reason="{{ $category->reason }}">
                                                        <i class="mdi mdi-account-convert" aria-hidden="true"></i> Verify
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="align-center">No Categories Found</td>
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

{{-- Category Details --}}

<div class="modal fade" id="ShowCategoryDetail" tabindex="-1" aria-labelledby="ShowCategoryDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="category_title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="wp-block-group bs-card-image-left-step-1">
                <div class="wp-block-group__inner-container">
                    <div class="card-horizontal">
                        <img id="cat_img_show_id" src="" class="card-img-top" alt="Category Image" ezimgfmt="rs rscb1 src ng ngcb1" loading="eager" srcset="" sizes="">
                        <div class="card-body">
                            <h5 class="card-title" id="cat_title_show"></h5>
                            <p class="card-text" id="cat_desc_show"></p>
                            <p>Category Details</p>
                            <table class="table table-dark">
                                <tbody>
                                    <tr id="product_count_row">
                                        <th>No of Products</th>
                                        <td id="product_count_show"></td>
                                    </tr>
                                    <tr>
                                        <th>Uploaded By</th>
                                        <td id="uploaded_show_id"></td>
                                    </tr>
                                    {{-- <tr>
                                        <th>Reason</th>
                                        <td id="new_cat_reason"></td>
                                    </tr> --}}
                                    {{-- <tr>
                                        <th>Approved By</th>
                                        <td></td>
                                    </tr> --}}
                                    {{-- <tr>
                                        <th>Approved Date</th>
                                        <td></td>
                                    </tr> --}}
                                    <tr>
                                        <th>Created Date</th>
                                        <td id="created_dt_show"></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="card card-grey mt-2" id="verify_form">
                                <div class="card-body">
                                    <div id="approvalerror" class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong id="approvalerrormsg"></strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <h6 class="mr-2">Reason ?</h6>
                                    <p class="font-weight-normal new_cat_request_reason"></p>
                                    <form>
                                        <input type="hidden" id="update_category_id" value="">
                                        <div class="form-group">
                                            <div class="form-check">
                                              <label class="form-check-label">
                                                <input type="radio" class="form-check-input check_approval" name="approval" value="1"> Approve <i class="input-helper"></i></label>
                                            </div>
                                            <div class="form-check">
                                              <label class="form-check-label">
                                                <input type="radio" class="form-check-input check_approval" name="approval" value="3"> Reject<i class="input-helper"></i></label>
                                            </div>

                                            <div class="form-group">
                                                <label for="exampleInputUsername1">Remarks</label>
                                                <textarea type="text" name="remarks" class="form-control mb-2 mr-sm-2 approval_remarks" rows="10" cols="50" required=""></textarea>
                                            </div>

                                            <button type="button" id="submit_verified_form" class="btn btn-gradient-primary float-right mr-2">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>


<div class="modal fade" id="EditCategoryModel" tabindex="-1" aria-labelledby="EditCategoryModelLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" id="EditCategory" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="category_id" id="edit_category_id" value="">
                        <div class="form-group">
                            <label for="exampleInputUsername1">Name</label>
                            <input type="text" class="form-control" name="name" value="" id="Edit_cat_name" placeholder="Category Name" required="" >
                        </div>

                        <div class="form-group">
                            <label for="exampleInputUsername1">Image</label>
                            <input type="file" class="form-control mb-2 mr-sm-2" name="image">
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>Existing Image</p>
                            <img class="img-thumbnail existing_img" src="" alt="" height="200" width="100">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="exampleInputUsername1">Description</label>
                            <textarea type="text" name="description" id="edit_desc_id" class="form-control mb-2 mr-sm-2" rows="10" cols="50" required=""></textarea>
                        </div>
                        <button type="submit" class="btn btn-gradient-primary mr-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

@endsection

@section('pagescript')
<script>
    $(document).ready(function(){
        var base_url = window.location.origin;

        $("body").on("click",".viewcatdet",function()
        {
            var cat_id          =   $(this).attr("data-category_id");
            var catname = $(this).attr("data-categoryname");
            var catdesc = $(this).attr("data-categorydesc");
            var cat_img = $(this).attr("data-cat_img");
            var cat_uploader =  $(this).attr("data-cat_uploader");
            var cat_products =  $(this).attr("data-cat_products");
            var created_at   =  $(this).attr("data-cat_created_dt");
            var cat_remarks  =  $(this).attr("data-cat_remarks");
            var cat_reason   =  $(this).attr("data-cat_reason");

            $("#update_category_id").val(cat_id);
            $("#category_title").html(catname+" Details");
            $("#cat_img_show_id").attr("src",cat_img);
            $("#cat_title_show").html(catname);
            $("#cat_desc_show").html(catdesc);
            $("#product_count_show").html(cat_products);
            $("#uploaded_show_id").html(cat_uploader);
            // $("#new_cat_reason").html(cat_reason);
            $("#created_dt_show").html(created_at);
            $("#product_count_row").show();
            $("#verify_form").hide();
            $("#ShowCategoryDetail").modal("show");
            $("#approvalerror").hide();
        });

        $("body").on("click",".verify_category",function(){
            var cat_id          =   $(this).attr("data-category_id");
            var catname         =   $(this).attr("data-categoryname");
            var catdesc         =   $(this).attr("data-categorydesc");
            var cat_img         =   $(this).attr("data-cat_img");
            var cat_uploader    =   $(this).attr("data-cat_uploader");
            var cat_products    =   $(this).attr("data-cat_products");
            var created_at      =   $(this).attr("data-cat_created_dt");
            var cat_remarks     =   $(this).attr("data-cat_remarks");
            var cat_reason      =   $(this).attr("data-cat_reason");
            $("#update_category_id").val(cat_id);
            $('.new_cat_request_reason').html(cat_reason);
            $("#category_title").html(catname+" Details");
            $("#cat_img_show_id").attr("src",cat_img);
            $("#cat_title_show").html(catname);
            $("#cat_desc_show").html(catdesc);
            $("#product_count_show").html(cat_products);
            $("#uploaded_show_id").html(cat_uploader);
            $("#new_cat_reason").html(cat_reason);
            $("#created_dt_show").html(created_at);
            $("#product_count_row").hide();
            $("#verify_form").show();
            $("#approvalerror").hide();
            $("#ShowCategoryDetail").modal("show");
        });

        $("body").on('click', '#submit_verified_form', function()
        {
            var formdata = {};
            formdata.cat_id             = $("#update_category_id").val();
            formdata.approval_type      = $('input[name="approval"]:checked').val();
            formdata.approval_remarks   = $(".approval_remarks").val();
            // console.log(formdata);
            $.ajax({
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                url: base_url + "/admin/categories/verify_category",
                data: formdata,
                dataType: "JSON",
                success: function(msg) {
                    console.log(msg);
                    if (msg['status'] == true)
                    {
                        window.location.href =  base_url + "/admin/categories/";
                        $("#approvalerror").hide();
                        $("#ShowCategoryDetail").modal("hide");
                    }
                    else
                    {
                        $("#ShowCategoryDetail").modal("show");
                        $("#approvalerror").show();
                        $("#approvalerrormsg").html(msg['message']);
                    }
                }
            });
        });

        $("body").on("click",".edit_category",function(){
            var cat_id          =   $(this).attr("data-category_id");
            var catname         =   $(this).attr("data-categoryname");
            var catdesc         =   $(this).attr("data-categorydesc");
            var cat_img         =   $(this).attr("data-cat_img");
            $("#edit_category_id").val(cat_id);
            $(".existing_img").attr("src",cat_img);
            $("#Edit_cat_name").val(catname);
            $("#edit_desc_id").val(catdesc);
            $("#EditCategoryModel").modal("show");
        });


        $('#EditCategory').validate({ // your rules and options,
            rules: {
                name: {
                    required: true,
                },
                description: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "<font color='red'>Please provide a title for Category</font>",
                },
                description: {
                    required: "<font color='red'>Please provide Description</font>",
                },
            },
            submitHandler: function(form) {
                var data = new FormData(form);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: base_url + "/admin/categories/UpdateCategory",
                    type: "POST",
                    dataType: 'json',
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        if (result['status'] == true) {
                            window.location.href =  base_url + "/admin/categories";
                            $('#EditCategoryModel').modal('hide');
                            iziToast.success({
                                timeout: 3000,
                                id: 'success',
                                title: 'Success',
                                message: result['message'],
                                position: 'bottomRight',
                                transitionIn: 'bounceInLeft',
                            });
                        } else {
                            // $('#EditCategoryModel').modal('hide');
                            iziToast.error({
                                timeout: 3000,
                                id: 'error',
                                title: 'Error',
                                message: result['message'],
                                position: 'topRight',
                                transitionIn: 'fadeInDown'
                            });
                        }
                    }
                });
            }
        });
    });
</script>
@endsection
