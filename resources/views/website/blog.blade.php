@extends('website.website_layout')
@section('page_title','- Blogs')
@section('page_content')
<section class="inner-banner">
    <div class="container text-center">
        <h3>Our <span>Blogs</span></h3>
        <div class="breadcumb">
            <a href="#">Home</a>
            <i class="fa fa-angle-right"></i>
            <span>Blogs</span>
        </div>
    </div>
</section>

<div class="separator no-border mb115 full-width"></div>
<section class="blog-style-one">
    <div class="container">
        <div class="row blog-carousel">
            @foreach ($blog_posts as $post)
            <div class="col-lg-4 col-md-6 col-sm-6 col-12 item my-3">
                <div class="single-blog-post">
                    <div class="img-box">
                        <img class="img-thumbnail" src="{{ asset($post->blog_image) }}" alt="" />
                    </div>
                    <div class="text-box">
                        <ul class="meta-info">
                            <li><a>{{ date("F jS, Y", strtotime($post->created_at)); }}</a></li>
                        </ul>
                        <a href="{{ route('blogdet',['title'=>$post->slug]) }}">
                            <h3>{{ $post->title }}</h3>
                        </a>

                        <a href="{{ route('blogdet',['title'=>$post->slug]) }}" class="read-more">Read more<i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center">
            {!! $blog_posts->links() !!}
        </div>
    </div>
</section>

<div class="separator no-border mb135 full-width"></div>
@endsection
