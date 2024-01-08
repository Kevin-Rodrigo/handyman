@extends('frontend.layouts.master')
@section('title')
    Services
@endsection
@section('content')
    <div class="container">
        <div class="breadcrumb-div pt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-primary-color"><i
                                class="fa-solid fa-house fs-7 me-2"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item  active breadcrumb-item-left" aria-current="page">Services</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="servicelist mb-4">
        <div class="container">
            <h2 class="text-font section-title fw-bold mb-4">All Services</h2>
            <div class="card border-0 text-center p-4 p-md-5 rounded-4" style="background-color: #2691bc;">
                <div class="card-body">
                    <div class="col-md-8 mx-auto my-5">
                        <h2 class="fs-1 fw-bold m-0 text-white">All Services</h2>
                    </div>
                </div>
            </div>
            {{-- <div class="row mt-n6 mt-sm-n7">
                <div class="col-11 mx-auto z-index-9">
                    <div class="bg-white shadow p-4 rounded-4">
                        <form action="https://bookingdo.paponapps.co.in/theme-1/services" method="get">
                            <div class="row justify-content-between">
                                <div class="col-lg-5 col-md-6 col-sm-6 mt-2">
                                    <label class="form-label text-muted">Categories</label>
                                    <select class="form-select text-muted border-0 rounded-4 bg-lights py-3 px-4"
                                        onchange="location =  $('option:selected',this).data('value');" name="category"
                                        id="category">
                                        <option value="" data-value="services4377.html?category=&amp;search_input="
                                            selected>-- Choose --</option>
                                        <option value="ac-repair-40"
                                            data-value="servicesaa44.html?category=ac-repair-40&amp;search_input=">
                                            AC Repair</option>
                                        <option value="appliance-repair"
                                            data-value="services1c39.html?category=appliance-repair&amp;search_input=">
                                            Appliance Repair</option>
                                        <option value="beauty-salon"
                                            data-value="services8e81.html?category=beauty-salon&amp;search_input=">
                                            Beauty &amp; Salon</option>
                                        <option value="car-service"
                                            data-value="services5374.html?category=car-service&amp;search_input=">
                                            Car Service</option>
                                        <option value="emergency"
                                            data-value="serviceseea4.html?category=emergency&amp;search_input=">
                                            Emergency</option>
                                        <option value="gadget-repair"
                                            data-value="services9532.html?category=gadget-repair&amp;search_input=">
                                            Gadget Repair</option>
                                        <option value="home-cleaning"
                                            data-value="servicesf0f3.html?category=home-cleaning&amp;search_input=">
                                            Home Cleaning</option>
                                        <option value="painting"
                                            data-value="services1f60.html?category=painting&amp;search_input=">
                                            Painting</option>
                                        <option value="plumbing"
                                            data-value="servicesb2d8.html?category=plumbing&amp;search_input=">
                                            Plumbing</option>
                                        <option value="shifting"
                                            data-value="servicesb54a.html?category=shifting&amp;search_input=">
                                            Shifting</option>
                                    </select>
                                </div>
                                <div class="col-lg-5 col-md-6 col-sm-6 mt-2">
                                    <label class="form-label text-muted">Service</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-0 rounded-4 bg-lights py-3 px-4 "
                                            name="search_input" id="search_input" value=""
                                            placeholder="Search service name" />
                                    </div>
                                </div>
                                <div class="col-lg-2 d-flex align-items-end mt-3 mt-lg-0">
                                    <button type="submit"
                                        class="btn bg-primary rounded-4 w-100 text-white btn-submit m-0 mb-1 ">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="dropdown-search">
            </div> --}}
            <!-- filter -->
            <!-- filter -->

        </div>
    </section>

    <section class="my-3">
        <div class="container">
            <div class="row g-4 listing-view">
                @foreach ($services as $service)
                    <div class="col-lg-6 col-md-12">
                        <div class="card my-cart-categories shadow h-100 w-100 border-0 rounded-4 overflow-hidden p-2">
                            <div class="test-img">
                                <div class="rounded-4 position-relative overflow-hidden">
                                    <img src="{{ asset('storage/app/public/service') }}/{{ $service->thumbnail }}"
                                        class="card-img-top p-0 border rounded-4" alt="...">
                                </div>
                            </div>

                            <div class="card-body py-0 px-2 p-md-3">
                                <div class="text-section">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small
                                            class="text-muted text-truncate col-md-10 col-9">{{ $service->category->name }}</small>
                                    </div>
                                    <div class="h-50px mb-3 mb-md-0">
                                        <h5 class="title fs-18 text_truncation2"><a
                                                href="{{ route('service-detail', $service->id) }}">{{ $service->name }}</a>
                                    </div>
                                    </h5>
                                    <div class="d-flex justify-content-between align-items-center ">
                                        <p class="price fs-6 m-0 fw-bold text-truncate">
                                            {{ with_currency_symbol($service->min_bidding_price) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="d-flex justify-content-center mt-3">
                    {{ $services->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
