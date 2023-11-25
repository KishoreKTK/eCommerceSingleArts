@extends('website.website_layout')
@section('page_title','')
@section('page_content')

    <section class="banner-static" id="explore">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-lg-6 col-md-5 align-self-center">
                    <div class="banner-moc-box clearfix">
                        <img src="{{ asset('web_assets/img/cover-screen.png') }}" alt="" class="float-right img-fluid" />
                    </div>
                </div>
                <div class="col-lg-6 col-md-7 align-self-center">
                    <div class="banner-content">
                        <h3>Awesome App for Your <br /> Modern Lifestyle</h3>
                        <p>Increase productivity with a simple to-do app. app for <br /> managing your personal budgets.
                        </p>
                        <a href="#" class="download-btn">
                            <i class="fab fa-apple"></i>
                            <span class="inner"> <span class="avail">Available on</span> <span class="store-name">App
                                    Store</span></span>
                        </a>
                        <a href="#" class="download-btn">
                            <i class="fab fa-google-play"></i>
                            <span class="inner"><span class="avail">Available on</span> <span class="store-name">Google
                                    play</span></span>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="separator no-border mt135 full-width"></div>

    <section class="features-style-one">
        <div class="container">
            <div class="row justify-content-lg-start justify-content-center">
                <div class="col-md-6 align-self-center order-lg-1 order-2">
                    <div class="feature-style-content">

                        <h5>what is bazaart?</h5>
                        <h3>An app specially designed for Art</h3>
                        <p>In order to design a mobile app that is going to be module <br /> downloaded and accessed
                            frequently by users, you need <br /> offer an experience that isn’t available elsewhere.
                            Often <br /> businesses get caught up.</p>
                        <a href="#" class="white-btn">Download App <i class="fa fa-arrow-down"></i></a>
                    </div>
                </div>
                <div class="col-md-6 col-9 align-self-center order-lg-2 order-1">
                    <img src="{{ asset('web_assets/img/what-is-bazaart-img.png') }}" alt="" />
                </div>
            </div>
        </div>
    </section>

    <div class="separator no-border mt135 full-width"></div>

    <section class="features-style-one">
        <div class="container">
            <div class="row justify-content-lg-start justify-content-center">
                <div class="col-md-5 col-8 align-self-center clearfix  order-lg-1 order-1">
                    <img src="{{ asset('web_assets/img/what-is-special-img.png') }}" alt="" />
                </div>
                <div class="col-md-6 align-self-center  order-lg-2 order-2">
                    <div class="feature-style-content">

                        <h5>what is special?</h5>
                        <h3>Responsive Design for All <br /> Devices with Quality</h3>
                        <p>In order to design a mobile app that is going to be module <br /> downloaded and accessed
                            frequently by users, you need <br /> offer an experience that isn’t available elsewhere.
                            Often <br /> businesses get caught up.</p>
                        <a href="#" class="white-btn">Download App <i class="fa fa-arrow-down"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="separator no-border mb90 full-width"></div>


    <section class="video-box">
        <div class="container text-center">

            <a href="" class="video-popup video-btn hvr-pulse"><i class="fa fa-play"></i></a>
            <h3>Watch the release ad</h3>
        </div>
    </section>


    <section class="how-app-work-section" id="how-it-works">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-11">
                    <div class="sec-title text-center">
                        <h6>Discover Bazaart</h6>
                        <h2>Art browsing is now not just easier, but more fun.</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-4 align-self-center">
                    <div class="discoverbox">

                        <div class="d-box mt-lg-5 mt-3 text-lg-right text-center">


                            <h6>Time Saver</h6>
                            <p>Nam vestibulum non tellus vitae consectetur. Etiam egestas ante ipsum, in porttitor eros
                                tristique euismod. Donec suscipit dictum efficitur.
                            </p>

                        </div>
                        <div class="d-box mt-lg-5 mt-3  text-lg-right text-center">

                            <h6>Unlimited Options</h6>

                            <p>Nam vestibulum non tellus vitae consectetur. Etiam egestas ante ipsum, in porttitor eros
                                tristique euismod. Donec suscipit dictum efficitur.
                            </p>

                        </div>

                    </div>
                </div>
                <div class="col-md-4 align-self-center">
                    <img class="d-block mx-auto img-fluid" src="{{ asset('web_assets/img/features-img.jpg') }}" />
                </div>
                <div class="col-md-4 align-self-center">
                    <div class="discoverbox">

                        <div class="d-box mt-lg-5 mt-3  text-lg-right text-center">

                            <h6>Artistic Inspirations</h6>

                            <p>Nam vestibulum non tellus vitae consectetur. Etiam egestas ante ipsum, in porttitor eros
                                tristique euismod. Donec suscipit dictum efficitur.
                            </p>

                        </div>
                        <div class="d-box mt-lg-5 mt-3  text-lg-right text-center">


                            <h6>Easy Navigation</h6>
                            <p>Nam vestibulum non tellus vitae consectetur. Etiam egestas ante ipsum, in porttitor eros
                                tristique euismod. Donec suscipit dictum efficitur.
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>


    <div class="separator no-border mt135 full-width"></div>

    <section class="app-secreenshots">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-11">
                    <div class="sec-title text-center">
                        <h2>Art browsing is now not just easier, but more fun.</h2>
                    </div>
                </div>
            </div>

        </div>
        <div class="swiper-slider-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12  appScreenshotCarousel-container swiper-container">
                        <div class="screen-mobile-image"></div>
                    <div class="swiper-wrapper">
                        <div class="swiper-slide"
                            style="background-image:url({{ asset('web_assets/img/screen-1.jpg') }})"></div>
                        <div class="swiper-slide"
                            style="background-image:url({{ asset('web_assets/img/screen-2.jpg') }})"></div>
                        <div class="swiper-slide"
                            style="background-image:url({{ asset('web_assets/img/screen-5.jpg') }})"></div>
                        <div class="swiper-slide"
                            style="background-image:url({{ asset('web_assets/img/screen-4.jpg') }})"></div>
                        <div class="swiper-slide"
                            style="background-image:url({{ asset('web_assets/img/screen-3.jpg') }})"></div>
                    </div>

                    </div>

                </div>
            </div>
        </div>
    </section>

    <div class="separator no-border mb115 full-width"></div>

    <section class="testimonials-style-one">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-11">
                    <div class="sec-title text-center">
                        <h2>Hear what our clients say about the brand Bazaart</h2>
                    </div>
                </div>
            </div>

            <div class="testimonials-slider">

                <ul class="slider">
                    <li class="slide-item">
                        <div class="single-testimonial text-center">
                            <div class="img-box">
                                <img src="{{ asset('web_assets/img/comment-1-1.png') }}" alt="" />
                            </div>
                            <div class="clearfix"></div>

                            <div class="text-box">
                                <h5>Kyong Bacco</h5>
                                <h6>Google</h6>
                                <img src="{{ asset('web_assets/img/testi-qoute.png') }}" alt="" />
                                <p>The number of ICOs being launched is increasing every day. The right team can help
                                    your ICO stand out from the crowd. We're that team.</p>

                            </div>
                        </div>
                    </li>
                    <li class="slide-item">
                        <div class="single-testimonial text-center">
                            <div class="img-box">
                                <img src="{{ asset('web_assets/img/comment-1-2.png') }}" alt="" />
                            </div>
                            <div class="clearfix"></div>

                            <div class="text-box">
                                <h5>Kyong Bacco</h5>
                                <h6>Google</h6>
                                <img src="{{ asset('web_assets/img/testi-qoute.png') }}" alt="" />
                                <p>The number of ICOs being launched is increasing every day. The right team can help
                                    your ICO stand out from the crowd. We're that team.</p>

                            </div>
                        </div>
                    </li>
                    <li class="slide-item">
                        <div class="single-testimonial text-center">
                            <div class="img-box">
                                <img src="{{ asset('web_assets/img/comment-1-3.png') }}" alt="" />
                            </div>
                            <div class="clearfix"></div>

                            <div class="text-box">
                                <h5>Kyong Bacco</h5>
                                <h6>Google</h6>
                                <img src="{{ asset('web_assets/img/testi-qoute.png') }}" alt="" />
                                <p>The number of ICOs being launched is increasing every day. The right team can help
                                    your ICO stand out from the crowd. We're that team.</p>

                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </section>



    <section class="download-section" id="downloads">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-11">
                    <div class="sec-title text-center">
                        <h6>ART. EASY. SIMPLE.</h6>
                        <h2>Find the shortest way to nirvana
                            just with your app.</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-auto">
                    <a href="#" class="download-btn">
                        <i class="fab fa-apple"></i>
                        <span class="inner"> <span class="avail">Available on</span> <span class="store-name">App
                                Store</span></span>
                    </a>
                    <a href="#" class="download-btn">
                        <i class="fab fa-google-play"></i>
                        <span class="inner"><span class="avail">Available on</span> <span class="store-name">Google
                                play</span></span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="separator no-border mb115 full-width"></div>

    <section class="blog-style-one">
        <div class="container">
            <div class="sec-title text-center">
                <h2>Latest blogs</h2>
            </div>
            <div class="row row-eq-height blog-carousel">
                @foreach ($blog_posts as $post)
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 item">
                        <div class="single-blog-post">
                            <div class="img-box">
                                <img class="img-thumbnail" src="{{ asset($post->blog_image) }}" alt="" />
                            </div>
                            <div class="text-box">
                                <ul class="meta-info">
                                    <li><a>{{ date("F jS, Y", strtotime($post->created_at)); }}</a></li>
                                </ul>
                                <a href="#">
                                    <h3>{{ $post->title }}</h3>
                                </a>

                                <a href="#" class="read-more">Read more<i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row justify-content-center">
                <div class="col-auto">
                    <a href="{{ Route('blog') }}" class="white-btn">More Blogs
                    <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <div class="separator no-border mb135 full-width"></div>

@endsection
