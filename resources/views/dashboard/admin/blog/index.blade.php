@extends('layouts.dashboard_layout')
@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/vendors/summernote/summernote.min.css') }}">
@endsection

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <a href="{{ Route('admin.home') }}"><span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>
            </span></a>Settings
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ Route('admin.home') }}">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">Blog</li>
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4>Blog</h4>
                        <button class="btn btn-outline-primary btn-sm" id="add_new_question_form" type="button"
                        data-toggle="modal" data-target="#viewAddContentForm">
                        <i class="mdi mdi-comment-plus-outline Icon-lg"></i> Add Blog Post</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(count($blogs) != 0)
            @foreach ($blogs as $post)
                <div class="col-lg-4 col-sm-12  grid-margin stretch-card">
                    <div class="card m-2 border border-primary rounded">
                        <img class="card-img-top" class="img-thumbnail" src="{{ asset($post->blog_image) }}" alt="Card image cap" height="300" width="150">
                        <div class="card-body">
                            {{-- <img src="{{ asset($post->blog_image) }}" alt="" class="" > --}}
                            <h5 class="card-title">{!! $post->title !!}</h5>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('blogdet',['title'=>$post->slug]) }}"
                                    class="btn btn-outline-primary btn-sm" target="_blank">View Post</a>

                                <div class="d-flex">
                                    <button type="button" class="btn btn-outline-warning btn-sm edit_content"
                                    data-title="{{ $post->title }}" data-category="{{ $post->category }}"
                                    data-blog_image="{{ asset($post->blog_image) }}" data-content="{{ $post->content }}"
                                    data-content-id="{{ $post->id }}"><i class="mdi mdi-pencil"></i></button>

                                    @if ($post->status == '1')
                                        <form action="{{ Route('admin.updatestatuspost') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                                            <input type="hidden" name="active_status" value="0">
                                            <button  type="submit"  class="btn btn-outline-danger mx-2 btn-sm">
                                                <i class="mdi mdi mdi-lock" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{  Route('admin.updatestatuspost') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                                            <input type="hidden" name="active_status" value="1">
                                            <button type="submit" class="btn btn-outline-success mx-2 btn-sm">
                                                <i class="mdi mdi-lock-open" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{  Route('admin.deletepost') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="mdi mdi-delete-forever"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12 grid-margin stretch-card">
                <div class="card m-2 border border-primary rounded">
                    <div class="card-body">
                        <h5 class="card-title text-center">No Contents Added Yet</h5>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="viewAddContentForm" tabindex="-1" role="dialog" aria-labelledby="viewAddContentFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewAddContentFormLabel">Add New Content</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('admin.blogpost') }}" method="POST" enctype="multipart/form-data" autocomplete="off" >
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputUsername1">Blog Image</label>
                            <input type="file" class="form-control mb-2 mr-sm-2" name="blog_image" id="" required>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputUsername1">Title</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" name="title" required placeholder="Blog Title" value="">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Blog Category</label>
                            <select class="form-control" name="category" id="categories">
                                <option value="General">General</option>
                                <option value="Arts">Arts</option>
                                <option value="Fashion">Fashion</option>
                                <option value="LifeStyle">Life Style</option>
                            </select>
                            <span class="text-danger">@error('category'){{ $message }}@enderror</span>
                        </div>

                        <div class="form-group">
                            <label for="my-textarea">Content</label>
                            <textarea id="my-textarea2" class="form-control summernote" name="content" required></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end p-2  m-2">
                        <button type="button" class="btn btn-secondary mx-2" data-dismiss="modal">Close</button>
                        <button type="Submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
</div>


<div class="modal fade" id="viewContent_modal" tabindex="-1" role="dialog" aria-labelledby="viewContent_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewContent_modalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body" id="content_view_id">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
      </div>
    </div>
</div>

<div class="modal fade" id="UpdateContentForm" tabindex="-1" role="dialog" aria-labelledby="UpdateContentFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="UpdateContentFormLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="{{ route('admin.updatepostblog') }}" method="POST" enctype="multipart/form-data" autocomplete="off" >
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            @csrf
                            <input type="hidden" name="post_id" id="hidden_content_id" value="">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="exampleInputUsername1">Blog Image</label>
                                        <input type="file" class="form-control mb-2 mr-sm-2" name="blog_image">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <img src="" id="existing_img_Pic" class="img-thumbnail" height="200" width="125"  alt="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_title_val">Title</label>
                                <input type="text" class="form-control" id="edit_title_val" name="title" required placeholder="Status Name" value="">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Blog Category</label>
                                <select class="form-control" name="category" id="updatecategories">
                                    <option value="General">General</option>
                                    <option value="Arts">Arts</option>
                                    <option value="Fashion">Fashion</option>
                                    <option value="LifeStyle">Life Style</option>
                                </select>
                                <span class="text-danger">@error('category'){{ $message }}@enderror</span>
                            </div>
                            <div class="form-group">
                                <label for="my-textarea">Content</label>
                                <textarea class="form-control summernote" name="content" id="edit_content_val" required></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end p-2  m-2">
                            <button type="button" class="btn btn-secondary mx-2" data-dismiss="modal">Close</button>
                            <button type="Submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pagescript')
<script src="{{ asset('assets/vendors/summernote/summernote.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $("#faq_form_id").hide();

        $('.summernote').summernote({
            placeholder: 'Add Content Here',
            tabsize: 2,
            height: 300,
            toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $("body").on("click",".view_content",function(){
            title   = $(this).attr('data-title');
            content = $(this).attr('data-content');
            $("#viewContent_modalLabel").html(title);
            $("#content_view_id").html(content);
            $("#viewContent_modal").modal("show");
        });

        $("body").on("click",".edit_content",function(){
            id      = $(this).attr('data-content-id');
            title   = $(this).attr('data-title');
            blog_img = $(this).attr('data-blog_image');
            categories = $(this).attr('data-category');
            content = $(this).attr('data-content');

            console.log(content);
            $("#hidden_content_id").val(id);
            $("#edit_title_val").val(title);
            $('#edit_content_val').summernote('code',content);
            $("#existing_img_Pic").attr("src", blog_img);
            $("#updatecategories").val(categories).change();

            $("#UpdateContentForm").modal("show");
        });
    });
</script>
@endsection
