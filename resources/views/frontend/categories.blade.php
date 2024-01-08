@extends('frontend.layouts.master')
@section('title')
    Categories
@endsection
@section('content')
    <div class="container">
        <div class="breadcrumb-div pt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-primary-color"><i
                                class="fa-solid fa-house fs-7 me-2"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active breadcrumb-item-left" aria-current="page">Categories</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- service listing section -->
    <section class="categorylist">
        <div class="container">
            <h2 class="section-title fw-bold">All Categories</h2>
            <!-- dropdown div -->
            <div class="contain py-4">
                <div class="row category-carousel category-slide">
                    @foreach ($categories as $category)
                        <div class="col-xl-2 col-md-3 mb-3 responsive-col">
                            <a href="#">
                                <div class="card shadow border-0 rounded-4 h-100 bg-light text-center align-items-center">
                                    <div class="card-body">
                                        <div class="icon-xl bg-mode rounded-circle mb-2 mx-auto shadow-sm">
                                            <img src="{{ asset('storage/app/public/category') }}/{{ $category->image }}"
                                                class="card-img-top" alt="">
                                        </div>
                                        @php
                                            $totalSevices = \Modules\ServiceManagement\Entities\Service::where('category_id', $category->id)->count();
                                        @endphp
                                        <div class="mt-2">
                                            <p class="mb-1 text-center text-dark fw-bold">
                                                {{ $category->name }}</p>
                                        </div>
                                        <P class="text-center fs-7 text-primary-color m-0">
                                            Services
                                            {{ $totalSevices }}+ </P>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $categories->links() }}
                </div>
            </div>
            <!---------------------------------------------------- subscription popup ---------------------------------------------------->
            {{-- <section class="subscription my-5">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="card rounded-4 border-0 bg-lights p-4">
                            <div class="row align-items-center">
                                <div class="col-4 d-none d-lg-block">
                                    <img src="{{ asset('public/assets/landing') }}/admin-assets/images/index/subscription-64d4b9edb4eef.webp"
                                        alt="" class="w-100 object-fit-cover rounded-4">
                                </div>
                                <div class="col-12 col-lg-8">
                                    <h2 class="subscribe-title">Stay Connected with HANDYMAN Subscribe to Our Email
                                        Updates</h2>
                                    <p class="fw-light">Subscribe to our email updates and stay in the loop with the
                                        latest news, insights, and exciting developments. Join our community to
                                        receive curated content, exclusive offers, and valuable resources delivered
                                        right to your inbox</p>
                                    <form action="https://bookingdo.paponapps.co.in/theme-1/subscribe" method="POST">
                                        <input type="hidden" name="_token"
                                            value="SIoJBZfAvdB04rF3HwuFYJQ41PQcrVsOpvSSuFz7">
                                        <div class="bg-body rounded-2 p-2 shadow-sm rounded-4 mb-3 mb-md-0">
                                            <div class="input-group">
                                                <input class="form-control border-0 me-1" type="email" name="email"
                                                    placeholder="Email">
                                                <button type="submit"
                                                    class="btn btn-primary rounded-2 mb-0 btn-submit rounded-4 d-none d-md-inline-block">Abonnieren!</button>
                                            </div>
                                        </div>
                                        <button type="submit"
                                            class="btn btn-primary w-100 rounded-2 mb-0 btn-submit rounded-4 d-inline-block d-md-none">Abonnieren!</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> --}}
            <!---------------------------------------------------- subscription popup ---------------------------------------------------->
        </div>
    </section>
    <!-- service listing section -->
@endsection
