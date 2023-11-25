@extends('website.website_layout')
@section('page_title','- FAQ')
@section('page_content')
<section class="inner-banner">
    <div class="container text-center">
        <h3>FAQs <span>& Support</span></h3>
        <div class="breadcumb">
            <a href="#">Home</a>
            <i class="fa fa-angle-right"></i>
            <span>FAQs & Support</span>
        </div>
    </div>
</section>

<div class="separator no-border mb115 full-width"></div>
<section class="blog-style-one">
    <div class="container">

        <div class="row">
            @foreach ($result['data'] as $data)
            <div class="col-md-12">
                @if($result['status'] == true)
                <h4>
                    <p>{!! $data->question !!}</p>
                </h4>
                <p></p>
                {!! $data->answer !!}
                <p></p>
                @else
                <p>
                    <center>Something Went Wrong. Please Come Again Later</center>
                </p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
<div class="separator no-border mb115 full-width"></div>


@endsection
