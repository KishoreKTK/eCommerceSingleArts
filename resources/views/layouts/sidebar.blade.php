<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <!-- Profile -->
        <!-- <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="#" alt="profile">
                    <span class="login-status online"></span>

                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">TestAdmin</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        -->
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ Route('admin.home') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        <!-- Categories -->
        <li class="nav-item">
            <a class="nav-link" href="{{ Route('admin.categories') }}">
                <span class="menu-title">Categories</span>
                <i class="mdi mdi mdi-apps menu-icon"></i>
            </a>
        </li>

        <!-- Approvals -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <span class="menu-title">Seller</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-nature-people menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.sellerrequest') }}">Seller Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.SellerList') }}">Seller Lists</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.sellerrequest') }}">Seller Reviews</a>
                    </li> --}}
                </ul>
            </div>
        </li>

        <!-- Products -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic2" aria-expanded="false" aria-controls="ui-basic2">
                <span class="menu-title">Products</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-shopping menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic2">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.ProductList') }}">Product Lists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.ProductAttributes') }}">Product Attributes</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.ProductFilters') }}">Product Filters</a>
                    </li> --}}
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.ProductReviews') }}">Product Reviews</a>
                    </li> --}}
                </ul>
            </div>
        </li>

        <!-- Customers -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.CustomerList') }}">
                <span class="menu-title">Customers</span>
                <i class="mdi mdi-human-male-female menu-icon"></i>
            </a>
        </li>

         <!-- Orders -->
         <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic3" aria-expanded="false" aria-controls="ui-basic2">
                <span class="menu-title">Orders</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-cart-plus menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic3">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.OrderList') }}">Order Lists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.OrderStatusList') }}">Order Status</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Settings -->
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic4" aria-expanded="false" aria-controls="ui-basic2">
                <span class="menu-title">Settings</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-settings-box menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic4">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.banners') }}">Banners</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.faq') }}">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.contents') }}">Contents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.contents') }}">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.blogindex') }}">Blog</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>
<!-- partial -->
