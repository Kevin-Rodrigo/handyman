<nav id="navbar_top" class="navbar navbar-expand-lg navbar-background sticky-top shadow-sm">
    <div class="container container-position">
        <a class="navbar-brand" href="{{ url('/') }}"><img
                src="{{ asset('public/assets/landing') }}/landing/images/png/logo.jpeg" class="logoimage"
                alt=""></a>

        <div class="d-flex gap-2 align-items-center ms-auto">

            <!--------- new tablet language button --------->
            {{-- <div class="dropdown d-block d-lg-none">
                <a class="btn btn-group dropdown-toggle d-flex align-items-center" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('public/assets/landing') }}/admin-assets/images/language/flag-64005c4be9359.png"
                        alt="" class="language-dropdown-image">
                </a>
                <ul class="dropdown-menu user-dropdown-menu dropdown-menu-ltr">
                    <li>
                        <a class="dropdown-item language-items" href="theme-1.html?lang=en">
                            <img src="{{ asset('public/assets/landing') }}/admin-assets/images/language/flag-64005c4be9359.png"
                                alt="" class="language-items-img">
                            <p>English</p>
                        </a>
                    </li>
                </ul>
            </div> --}}
            <!--------- new tablet language button --------->
            <!------ day-night btn ------>


            <!------ day-night btn ------>

            <!-- new after login btn -->
            @auth
                <a href="{{ route('profile') }}" class="btn btn-outline-primary rounded-3 d-none"><i
                        class="fa-solid fa-user"></i></a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-3 d-none"><i
                        class="fa-solid fa-user"></i></a>
            @endauth

            <!-- new after login btn -->

            <a class="btn btn-outline-primary d-block d-lg-none" data-bs-toggle="offcanvas" href="#offcanvasExample"
                role="button" aria-controls="offcanvasExample">
                <i class="fa-solid fa-bars"></i>
            </a>

            <div class="offcanvas offcanvas-end w-75 d-block d-lg-none" tabindex="-1" id="offcanvasExample"
                aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link navbar-text border-bottom {{ request()->routeIs('home') ? 'active' : '' }}"
                                aria-current="page" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link navbar-text border-bottom {{ request()->routeIs('categories') ? 'active' : '' }}"
                                href="{{ route('categories') }}">Category</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link navbar-text border-bottom {{ request()->routeIs('services') ? 'active' : '' }}"
                                href="{{ route('services') }}">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link navbar-text border-bottom  {{ request()->routeIs('blogs') ? 'active' : '' }}"
                                href="{{ route('blogs') }}">Blogs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link navbar-text border-bottom {{ request()->routeIs('cart') ? 'active' : '' }}"
                                href="{{ route('cart') }}">Cart
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="Menu">
            <ul class="navbar-nav mb-2 mb-lg-0 text-center me-auto ms-3">
                <li class="nav-item">
                    <a class="nav-link navbar-text {{ request()->routeIs('home') ? 'active' : '' }}"
                        aria-current="page" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navbar-text {{ request()->routeIs('categories') ? 'active' : '' }}"
                        href="{{ route('categories') }}">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navbar-text {{ request()->routeIs('services') ? 'active' : '' }}"
                        href="{{ route('services') }}">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navbar-text  {{ request()->routeIs('blogs') ? 'active' : '' }}"
                        href="{{ route('blogs') }}">Blogs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navbar-text {{ request()->routeIs('cart') ? 'active' : '' }}"
                        href="{{ route('cart') }}">Cart</a>
                </li>
            </ul>
            <!-- dekstop-language-dropdown-button-start-->

            <!--------- new dekstop language button --------->
            {{-- <div class="btn-group">
                <a class="nav-link d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="{{ asset('public/assets/landing') }}/admin-assets/images/language/flag-64005c4be9359.png"
                        alt="" class="language-dropdown-image">
                </a>
                <ul class="dropdown-menu user-dropdown-menu drop-menu">
                    <li>
                        <a class="dropdown-item language-items" href="theme-1.html?lang=en">
                            <img src="{{ asset('public/assets/landing') }}/admin-assets/images/language/flag-64005c4be9359.png"
                                alt="" class="language-items-img">
                            <p>English</p>
                        </a>
                    </li>
                </ul>
            </div> --}}
            <!--------- new language button --------->

            <!-- day-night btn -->

            <!-- day-night btn -->

            <!-- dekstop-language-dropdown-button--end-->
            @auth
                <a href="{{ route('profile') }}" class="btn btn-outline-primary rounded-3 mx-2"><i
                        class="fa-solid fa-user"></i></a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-3 mx-2"><i
                        class="fa-solid fa-user"></i></a>
            @endauth
        </div>
        <div class="collapse  collapse-horizontal" id="slide">
            <ul class="navbar-nav sidebar-li d-lg-none d-md-block mb-2 mb-lg-0 p-4 sidebar  ">
                <li class="nav-item">
                    <button class="close-btn" data-bs-toggle="collapse" data-bs-target="#slide" aria-controls="slide"
                        aria-expanded="false"><i class="fa-solid fa-xmark"></i></button>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page"
                        href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('categories') ? 'active' : '' }}"
                        href="{{ route('categories') }}">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('services') ? 'active' : '' }}"
                        href="{{ route('services') }}">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('blogs') ? 'active' : '' }}"
                        href="{{ route('blogs') }}">Blogs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('cart') ? 'active' : '' }}"
                        href="{{ route('cart') }}">Cart</a>
                </li>
                @auth
                    <a href="{{ route('profile') }}"
                        class="btn btn-outline-primary bg-primary text-white rounded-3">Login</a>
                @else
                    <a href="{{ route('login') }}"
                        class="btn btn-outline-primary bg-primary text-white rounded-3">Login</a>
                @endauth


            </ul>
        </div>
    </div>
</nav>
