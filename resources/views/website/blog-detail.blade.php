@extends('website.website_layout')
@section('page_title','- Blog Detail')
@section('page_content')
<section class="inner-banner">
    <div class="container text-center">
        <h3>Blog <span>Details</span></h3>
        <div class="breadcumb">
            <a href="#">Home</a>
            <i class="fa fa-angle-right"></i>
            <span>Blog Details</span>
        </div>
    </div>
</section>

<div class="separator no-border mb115 full-width"></div>
<section class="blog-list blog-style-two blog-details-page">
        <div class="container">
            <div class="row">
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="has-right-sidebar">
                        <div class="single-blog-post-style-two">
                            <div class="img-box">
                                <img src="{{ asset($check_slug->blog_image) }}" alt="{{ $check_slug->title }}" />
                            </div><!-- /.img-box -->
                            <div class="text-box">
                                <a href="#">
                                    <h3>{{ $check_slug->title }}</h3>
                                </a>
                                <p>
                                    {!! $check_slug->content !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <div class="sidebar sidebar-right">

                        <div class="single-sidebar recent-post-widget">
                            <div class="title">
                                <h3>Recent Blogs</h3>
                            </div>
                            <div class="recent-post-list">
                                @foreach ($recent_posts as $post)
                                    <div class="single-recent-post">
                                        <a href="{{ route('blogdet',['title'=>$post->slug]) }}">
                                            <h3>{{ $post->title }}</h3>
                                        </a>
                                        <a href="#" class="date">{{ date("F jS, Y", strtotime($post->created_at)); }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


<div class="separator no-border mb135 full-width"></div>

@endsection
