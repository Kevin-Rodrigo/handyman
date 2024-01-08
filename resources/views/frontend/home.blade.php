@extends('frontend.layouts.master')
@section('title')
    Home
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/owl-carousel/owl.theme.default.min.css">
@endpush
@section('content')
    <!-- top-banner -->
    <section class="theme-1-home position-relative home-banner py-8 py-lg-9"
        style="background-image:url({{ asset('public/assets/landing') }}/admin-assets/images/banner/banner-6399aa46a3870.jpg); background-position: center left; background-size: cover; height:100vh;">
        <div class="bg-overlay"></div>
        <div class="container z-index-9 position-relative">
            <div class="row py-sm-5">
                <div class="col-xl-8 m-auto text-center mt-2 mt-md-0">
                    <p class="fs-6 fw-medium text-primary-color">Service Booking</p>
                    <h1 class="fw-bold text-white home-subtitle m-0">
                        Seamless Booking, Effortless Scheduling Your Time, Your Way!</h1>
                </div>
                {{-- <div class="col-xl-8 mx-auto">
                    <div class="bg-blur bg-white bg-opacity-10 border border-light border-opacity-25 rounded-3 p-4 my-5">
                        <form action="https://bookingdo.paponapps.co.in/theme-1/services" method="get"
                            class="row g-3 align-items-center">
                            <div class="col-md-10">
                                <!-- Input -->
                                <div class="form-input-dropdown position-relative">
                                    <input class="form-control form-control-lg ps-5 rounded-4 fs-7" type="text"
                                        name="search_input" id="search_input" value=""
                                        placeholder="Search Service Name">
                                    <span class="position-absolute top-50 translate-middle end-0 pe-1"><i
                                            class="fa-light fa-magnifying-glass fs-5"></i></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <!-- Search btn -->
                                <button type="submit"
                                    class="btn btn-lg btn-primary mb-0 rounded-4 btn-submit w-100">Search</button>
                            </div>
                        </form>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
    <!-- banner section-1  -->
    <section class="py-5">
        <div class="container">
            <div class="card-carousel owl-carousel owl-loaded">
                <div class="owl-stage-outer">
                    <div class="owl-stage carousel">
                        <div class="owl-item">
                            <div class="card-top">
                                <div class="card-overlay">
                                    <a href="theme-1/servicesfbf1.html?category=car-service">
                                        <img src="{{ asset('public/assets/landing') }}/admin-assets/images/banner/banner-6399c99cccfb8.jpg"
                                            class="card-imp-top" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="owl-item">
                            <div class="card-top">
                                <div class="card-overlay">
                                    <a href="theme-1/services50e2.html?category=beauty-salon">
                                        <img src="{{ asset('public/assets/landing') }}/admin-assets/images/banner/banner-64d4e446f220d.png"
                                            class="card-imp-top" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="owl-item">
                            <div class="card-top">
                                <div class="card-overlay">
                                    <a href="theme-1/services8863.html?category=ac-repair-40">
                                        <img src="{{ asset('public/assets/landing') }}/admin-assets/images/banner/banner-6399c78ebf8a4.jpg"
                                            class="card-imp-top" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner secction-1 -->

    <!-- category section -->
    <section class="category">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between pb-5">
                <div>
                    <h3 class="fw-bold fs-1">See All Category</h3>
                    <p class="text-font text-muted m-0">Our most popular category</p>
                </div>
                <a class="fs-6 fw-semibold btn btn-primary-rgb" href="{{ route('categories') }}">View All <i
                        class=" mx-1 fa-solid fa-arrow-right-long"></i></a>
            </div>
            <div class="category-carousel owl-carousel owl-theme owl-loaded category-slide">
                <div class="owl-stage-outer">
                    <div class="owl-stage carousel">
                        @foreach ($categories as $category)
                            <div class="owl-item">
                                <a href="{{route('categoryservices', $category->id)}}">
                                    <div
                                        class="card border-0 rounded-4 h-100 w-100 bg-light text-center align-items-center">
                                        <div class="card-body">
                                            <div class="icon-xl bg-mode rounded-circle mb-2 mx-auto">
                                                <img src="{{ asset('storage/app/public/category') }}/{{ $category->image }}"
                                                    class="card-img-top" alt="">
                                            </div>
                                            @php
                                                $totalSevices = \Modules\ServiceManagement\Entities\Service::where('category_id', $category->id)->count();
                                            @endphp
                                            <P class="text-center fs-7 text-primary-color m-0 text-truncate">
                                                Services
                                                {{ $totalSevices }}+
                                            </P>
                                            <p class="mb-1 text-center text-dark fw-bold text-truncate">
                                                {{ $category->name }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Category Section -->

    <!-- service section -->
    <section class="service-div w-100">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between py-5">
                <div class="">
                    <h5 class="fw-bold fs-1">Services</h5>
                    <p class="text-font text-muted m-0">Our Populer
                        Services</p>
                </div>
                <a class="fw-semibold btn btn-primary-rgb" href="{{ route('services') }}">View All <i
                        class=" mx-1 fa-solid fa-arrow-right-long"></i></a>
            </div>
            <div class="row g-4">
                @foreach ($services as $service)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="card shadow h-100 w-100 border-0 rounded-4 overflow-hidden">
                            <div class="position-relative overflow-hidden img-over">
                                <img src="{{ asset('storage/app/public/service') }}/{{ $service->thumbnail }}"
                                    class="card-img-top w-100" alt="...">
                            </div>

                            <div class="card-body">
                                <div class="card-text">
                                    <h5 class="mb-0 fw-semibold text_truncation2">
                                        <a href="{{ route('service-detail', $service->id) }}">{{ $service->name }}</a>
                                    </h5>
                                    <div class="d-flex align-items-center justify-content-between mt-2">
                                        <small class="fs-7 text-muted text-truncate">{{ $service->category->name }}</small>
                                        <p class="fw-semibold m-0 fs-7"><i class="fas fa-star fa-fw text-warning"></i>
                                            3.8
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-top p-4 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="fw-bold my-0 text-truncate">
                                        {{ with_currency_symbol($service->min_bidding_price) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!----------------------------------------------------- banner-section-2 ----------------------------------------------------->
    <section class="banner-section-2 py-5">
        <div id="carouselExampleSlides1" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('public/assets/landing') }}/admin-assets/images/banner/banner-64d4c466a56a3.webp"
                        class="d-block w-100">
                    <div class="container">
                        <div class="carousel-caption py-lg-6 z-index-9">
                            <div class="col-lg-6  ms-auto text-start">
                                <h2 class="mb-4 fs-1 fw-bold text-white text_truncation2">
                                    The Best Online Home Cleaning Service
                                </h2>
                                <p class="text-white fs-7 mb-4 text_truncation3">Lorem ipsum dolor sit, amet
                                    consectetur adipisicing elit. Laboriosam, praesentium sint libero eos dolor
                                    officia quaerat, delectus voluptate expedita mollitia nobis laudantium fuga
                                </p>
                                {{-- <a class="btn btn-primary mb-0 btn-submit rounded-4"
                                    href="theme-1/services3cde.html?category=home-cleaning">
                                    BOOK NOW</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item ">
                    <img src="{{ asset('public/assets/landing') }}/admin-assets/images/banner/banner-64d4c9af9c0fe.webp"
                        class="d-block w-100">
                    <div class="container">
                        <div class="carousel-caption py-lg-6 z-index-9">
                            <div class="col-lg-6  ms-auto text-start">
                                <h2 class="mb-4 fs-1 fw-bold text-white text_truncation2">
                                    Online Car Service Appointment Booking
                                </h2>
                                <p class="text-white fs-7 mb-4 text_truncation3">Lorem ipsum dolor sit, amet
                                    consectetur adipisicing elit. Laboriosam, praesentium sint libero eos dolor
                                    officia quaerat, delectus voluptate expedita mollitia nobis laudantium fuga,
                                    quod quo dolorum deserunt perspiciatis amet ullam.
                                </p>
                                {{-- <a class="btn btn-primary mb-0 btn-submit rounded-4" href="theme-1/service-14.html">
                                    BOOK NOW</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item ">
                    <img src="{{ asset('public/assets/landing') }}/admin-assets/images/banner/banner-64d4c9b6c20b5.webp"
                        class="d-block w-100">
                    <div class="container">
                        <div class="carousel-caption py-lg-6 z-index-9">
                            <div class="col-lg-6  ms-auto text-start">
                                <h2 class="mb-4 fs-1 fw-bold text-white text_truncation2">
                                    Online Spa Appointment Service Booking
                                </h2>
                                <p class="text-white fs-7 mb-4 text_truncation3">Lorem ipsum dolor sit, amet
                                    consectetur adipisicing elit. Laboriosam, praesentium sint libero eos dolor
                                    officia quaerat, delectus voluptate expedita mollitia nobis laudantium fuga,
                                    quod quo dolorum deserunt perspiciatis amet ullam.
                                </p>
                                {{-- <a class="btn btn-primary mb-0 btn-submit rounded-4"
                                    href="theme-1/services50e2.html?category=beauty-salon">
                                    BOOK NOW</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleSlides1"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleSlides1"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </div>
        </div>
    </section>

    <!---------------------------------------------------- banner-section-2 ---------------------------------------------------->

    <!------------------------------------------------- Why choose section start ------------------------------------------------->
    <section class="why-choose mb-5">
        <div class="container">
            <div class="row g-4 justify-content-between align-items-center">
                <!-- Left side START -->
                <div class="col-lg-5 position-relative">
                    <!-- Image -->
                    <img src="{{ asset('public/assets/landing') }}/admin-assets/images/index/choose-64d4bad0df364.png"
                        class="rounded-4 w-100 position-relative" alt="">
                </div>
                <!-- Left side END -->

                <!-- Right side START -->
                <div class="col-lg-6 choose-content">
                    <h2 class="pb-1 fw-bold text_truncation2">The Best Online Appointment Booking!
                    </h2>
                    <p class="mb-3 mb-lg-5 text-muted text_truncation3">
                        When choosing the best platform for your needs, consider factors like your business size,
                        budget, specific requirements ease of use, and any unique features that stand out to you</p>

                    <!-- Features START -->
                    <div class="row g-4">
                        <!-- Item -->
                        <div class="col-sm-6">
                            <img src="{{ asset('public/assets/landing') }}/admin-assets/images/index/choose-64d4baf589205.png"
                                class="icon-lg bg-success bg-opacity-10 text-success rounded-circle" alt="">
                            <h5 class="mt-2 fw-bold text-truncate">Seamless User Experience</h5>
                            <p class="mb-0 text-muted text_truncation2">Booking SaaS System offers a seamless user
                                experience</p>
                        </div>
                        <div class="col-sm-6">
                            <img src="{{ asset('public/assets/landing') }}/admin-assets/images/index/choose-64d4bb09cff9f.png"
                                class="icon-lg bg-success bg-opacity-10 text-success rounded-circle" alt="">
                            <h5 class="mt-2 fw-bold text-truncate">Customisable &amp; Scalable</h5>
                            <p class="mb-0 text-muted text_truncation2">We pride a highly customisable and scalable
                                online appointment</p>
                        </div>
                        <div class="col-sm-6">
                            <img src="{{ asset('public/assets/landing') }}/admin-assets/images/index/choose-64d4bb2a4624e.png"
                                class="icon-lg bg-success bg-opacity-10 text-success rounded-circle" alt="">
                            <h5 class="mt-2 fw-bold text-truncate">Robust Feature Set</h5>
                            <p class="mb-0 text-muted text_truncation2">set of features designed to streamline
                                appointment management</p>
                        </div>
                        <div class="col-sm-6">
                            <img src="{{ asset('public/assets/landing') }}/admin-assets/images/index/choose-64d4bb3b1b9e1.png"
                                class="icon-lg bg-success bg-opacity-10 text-success rounded-circle" alt="">
                            <h5 class="mt-2 fw-bold text-truncate">Security &amp; Support</h5>
                            <p class="mb-0 text-muted text_truncation2">security and customer support are at the
                                forefront of our priorities</p>
                        </div>
                    </div>
                    <!-- Features END -->

                </div>
                <!-- Right side END -->
            </div>
        </div>
    </section>

    <!-------------------------------------------------- Why choose section end -------------------------------------------------->

    <section class="bg-lights py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-3">
                    <div class="footer-widget border-0 bg-white rounded-4">
                        <div class="widget-wrapper d-flex align-items-start">
                            <div class="widget-icon fs-3 text-primary-color"> <i class="fa-duotone fa-user"></i>
                            </div>
                            <div class="widget-content">
                                <h3 class="pst fw-bold">Create Account</h3>
                                <p class="fs-7 m-0 text-muted">An account is where it all start</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-3">
                    <div class="footer-widget border-0 bg-white rounded-4">
                        <div class="widget-wrapper d-flex align-items-start">
                            <div class="widget-icon fs-3 text-primary-color"> <i class="fa-duotone fa-calendar-plus"></i>
                            </div>
                            <div class="widget-content">
                                <h3 class="pst fw-bold">Add Services</h3>
                                <p class="fs-7 m-0 text-muted">You have the service in hand</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-3">
                    <div class="footer-widget border-0 bg-white rounded-4">
                        <div class="widget-wrapper d-flex align-items-start">
                            <div class="widget-icon fs-3 text-primary-color"> <i class="fa-duotone fa-clock"></i>
                            </div>
                            <div class="widget-content">
                                <h3 class="pst fw-bold">Start Booking</h3>
                                <p class="fs-7 m-0 text-muted">It’s time to accept booking</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-3">
                    <div class="footer-widget border-0 bg-white rounded-4">
                        <div class="widget-wrapper d-flex align-items-start">
                            <div class="widget-icon fs-3 text-primary-color"> <i class="fa-duotone fa-headset"></i>
                            </div>
                            <div class="widget-content">
                                <h3 class="pst fw-bold">Online Support</h3>
                                <p class="fs-7 m-0 text-muted">24 hours a day, 7 days a week</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{ asset('public/assets/landing') }}/front/js/owl.carousel.js"></script>
    <script src="{{ asset('public/assets/landing') }}/front/js/owl.carousel.min.js"></script>
@endpush
