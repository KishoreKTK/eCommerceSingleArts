<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="{{ Route('seller.home') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        <!-- Categories -->
        <li class="nav-item">
            <a class="nav-link" href="{{ Route('seller.categories') }}">
                <span class="menu-title">Categories</span>
                <i class="mdi mdi mdi-apps menu-icon"></i>
            </a>
        </li>

        <!-- Products -->
        {{-- <li class="nav-item">
            <a class="nav-link" href="{{ Route('seller.ProductList') }}">
                <span class="menu-title">Products</span>
                <i class="mdi mdi-shopping menu-icon"></i>
            </a>
        </li> --}}


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
                        <a class="nav-link" href="{{ route('seller.AddProduct') }}">Add Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('seller.ProductList') }}">Product List</a>
                    </li>

                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('seller.ProductList') }}">Attributes & Options</a>
                    </li> --}}
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('seller.ProductReviews') }}">Product Reviews</a>
                    </li> --}}
                </ul>
            </div>
        </li>

        {{-- Orders --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ Route('seller.OrderList') }}">
                <span class="menu-title">Orders</span>
                <i class="mdi mdi-cart-plus menu-icon"></i>
            </a>
        </li>

    </ul>
</nav>
<!-- partial -->
