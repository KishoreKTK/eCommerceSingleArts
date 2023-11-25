<!DOCTYPE html>
<html lang="en">


<head>
	<meta charset="UTF-8" />
	<title>Bazaart @yield('page_title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('web_assets/img/favicon.png') }}">
    <link rel="manifest" href="{{ asset('web_assets/img/favicon/manifest.js') }} on">
    <link rel="stylesheet" href="{{ asset('web_assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('web_assets/css/responsive.css') }}">
    @yield('pagecss')
</head>

<body>

	<div class="preloader">
		<div class="spinner"></div>
	</div><!-- /.preloader -->


    <header class="header home-page-one">
        <div class="container">
            <div class="appilo-menu clearfix">
                <nav class="navbar navbar-expand-lg navbar-custom navbar-light">

                    <a class="navbar-brand  mr-auto " href="{{ url('/') }}">
                        <img src="{{ asset('web_assets/img/bazaart-logo-horizontal.png') }}" alt=""
                            class="default-logo">
                        <img src="{{ asset('web_assets/img/bazaart-logo-horizontal.png') }}" alt="" class="stick-logo">
                    </a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler"
                        aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="collapse navbar-collapse navbar-nav" id="navbarToggler">
                        <ul class="ml-auto">
                            <li><a class="nav-link active" href="{{ url('/') }}#explore">Explore</a></li>
                            <li><a class="nav-link" href="{{ url('/') }}#how-it-works">How it works</a></li>
                            <li><a class="nav-link" href="{{ Route('blog') }}">Our Blogs</a></li>
                            <li><a class="nav-link" href="{{ Route('faqsupport') }}">FAQs & Support</a></li>
                            <li><a class="nav-link" href="{{ url('/seller_login') }}">Interested to sell</a></li>
                        </ul>
                    </div>
                    <div class="sign-up-btn">
                        <a href="#downloads" class="sign-btn">Download App</a>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    @yield('page_content')

	<footer class="footer">
        @if(Route::current()->getName() == 'webpage')
            <div class="subscribe-section">
                <div class="container">
                    <div class="sec-title text-center">
                        <h6>Our newsletter</h6>
                        <h2>Subscribe to Our Newsletter</h2>
                    </div>
                    <form action="#" class="subscribe-form clearfix">
                        <div class="row justify-content-center">
                            <div class="col-md-6 left-contentclearfix">
                                <input type="text" placeholder="your email" />
                            </div><!-- /.left-content -->
                            <div class="col-auto right-content">
                                <button class="thm-btn" type="submit"><span>Subscribe Now</span></button>
                            </div><!-- /.right-content -->
                        </div>
                    </form><!-- /.subscribe-form -->
                </div>
            </div><!-- /.subscribe-section -->
		@endif

        <div class="footer-widget-wrapper">
            <div class="container">
                <div class="row masonary-layout">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="footer-widget links-widget">
                            <div class="title">
                                <h3>Quick Links</h3>
                            </div>
                            <ul class="list-inline link-list">
                                <li><a href="#">Explore</a></li>

                                <li><a href="#">How it works</a></li>

                                <li><a href="#">Our Blogs</a></li>

                                <li><a href="#">Contact</a></li>

							</ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="footer-widget links-widget">
                            <div class="title">
                                <h3>Help</h3>
                            </div>
                            <ul class="list-inline link-list">
                                <li><a href="{{ Route('privacy_policy') }}">FAQs & Support</a></li>

                                <li><a href="{{ Route('privacy_policy') }}">Privacy Policy</a></li>

                                <li><a href="{{ Route('terms_n_condition') }}">Terms & Conditions</a></li>


                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="footer-widget links-widget">
                            <div class="title">
                                <h3>Contact Us</h3>
                            </div>
                            <ul class="list-inline link-list">
                                <li><a href="tel:+9710555555145">+971 05 555 55145</a></li>
                                <li><a href="mailto:info@bazaart.ae">info@bazaart.ae</a></li>
                                <li><a>Dubai, United Arab Emirates</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="footer-widget about-widget">
                            <div class="title">
                                <h3>Follow Us</h3>
                            </div>
                            <div class="social">
                                <a href="#" class="fab fa-facebook-f"></a>
                            <a href="#" class="fab fa-twitter"></a>

                        <a href="#" class="fab fa-instagram"></a>
                            </div>
                            <p>&copy; {{ date('Y') }} Bazaart.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</footer><!-- /.footer -->

    <div class="scrollup"><span class="fas fa-angle-up"></span></div>

    <script src="{{ asset('web_assets/js/jquery.js') }} "></script>
    <script src="{{ asset('web_assets/js/bootstrap.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/popper.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/bootstrap-select.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/jquery.validate.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/owl.carousel.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/isotope.js') }} "></script>
    <script src="{{ asset('web_assets/js/jquery.magnific-popup.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/waypoints.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/jquery.counterup.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/wow.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/jquery.easing.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/swiper.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/jquery.bxslider.min.js') }} "></script>
    <script src="{{ asset('web_assets/js/custom.js') }} "></script>

    @yield('pagescript')
</body>

</html>
