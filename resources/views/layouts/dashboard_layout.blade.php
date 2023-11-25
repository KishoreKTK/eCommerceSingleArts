<!DOCTYPE html>
<?php
$routeName = \Request::route()->getName();
?>
<html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Bazaart</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
        <!-- endinject -->

        <!-- Plugin css for this page -->
        <link rel="stylesheet" href="{{ asset('assets/vendors/IziToast/iziToast.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
        <!-- End plugin css for this page -->

        <!-- Layout styles -->
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
        <!-- End layout styles -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
        <link href="{{ asset('assets/images/favicon.png') }}" rel="icon" />
        @yield('pagecss')

    </head>

    <body>
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo" href="{{ url('/') }}"><img src="{{ asset('assets/images/logo-horizontal.png') }}" alt="logo" /></a>
                    <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}"><img src="{{ asset('assets/images/logo-mini.png') }}" alt="logo" /></a>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-stretch">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                    @if (strpos($routeName, 'admin.') === 0)
                        <ul class="navbar-nav navbar-nav-right">
                            <li class="nav-item nav-profile dropdown">
                                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                                    <div class="nav-profile-img">
                                        <img src="{{ asset('assets/images/faces/blankuser.png') }}" alt="image">
                                        <span class="availability-status online"></span>
                                    </div>
                                    <div class="nav-profile-text">
                                        <p class="mb-1 text-black">{{ Auth::guard('admin')->user()->name }}</p>
                                    </div>
                                </a>
                                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                                    <a class="dropdown-item" href="#">
                                        <i class="mdi mdi-cached mr-2 text-success"></i> Edit Profile </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-logout mr-2 text-primary"></i> Signout </a>
                                        <form action="{{ route('admin.logout') }}" id="logout-form" method="post">@csrf</form>
                                </div>
                            </li>
                        </ul>
                    @else
                        <ul class="navbar-nav navbar-nav-right">
                            <li class="nav-item nav-profile dropdown">
                                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                                    <div class="nav-profile-img">
                                        <img src="{{ asset('assets/images/faces/blankuser.png') }}" alt="image">
                                        <span class="availability-status online"></span>
                                    </div>
                                    <div class="nav-profile-text">
                                        <p class="mb-1 text-black">{{ Auth::guard('seller')->user()->sellername }}</p>
                                    </div>
                                </a>
                                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                                    <a class="dropdown-item" href="#">
                                        <i class="mdi mdi-cached mr-2 text-success"></i> Edit Profile </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('seller.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-logout mr-2 text-primary"></i> Signout </a>
                                        <form action="{{ route('seller.logout') }}" id="logout-form" method="post">@csrf</form>
                                </div>
                            </li>
                        </ul>
                    @endif

                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
            </button>
                </div>
            </nav>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                @if (strpos($routeName, 'admin.') === 0)
                    @include('layouts.sidebar')
                @else
                    @include('layouts.sellersidebar')
                @endif
                <div class="main-panel">
                    @yield('content')
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <footer class="footer">
                        <div class="container-fluid clearfix">
                            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©buzart 2020</span>
                        </div>
                    </footer>
                    <!-- partial -->
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <!-- container-scroller -->
        <!-- plugins:js -->
        <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="{{ asset('assets/vendors/chart.js/Chart.min.js') }}"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
        <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
        <script src="{{ asset('assets/js/misc.js') }}"></script>
        <!-- endinject -->
        <!-- Custom js for this page -->
        <script src="{{ asset('assets/js/dashboard.js') }}"></script>
        <script src="{{ asset('assets/js/todolist.js') }}"></script>
        <!-- End custom js for this page -->
        <script src="{{ asset('assets/js/jquery.validate.js') }}"></script>
        <script src="{{ asset('assets/js/additional-methods.js') }}"></script>
        <script src="{{ asset('assets/vendors/IziToast/iziToast.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
        @yield('pagescript')
    </body>

</html>
