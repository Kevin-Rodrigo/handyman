@extends('frontend.layouts.master')
@section('title')
    Blog Detail
@endsection
@section('content')
    <div class="container mb-2">
        <div class="breadcrumb-div pt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-primary-color"><i
                                class="fa-solid fa-house fs-7 me-2"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active breadcrumb-item-left" aria-current="page">Blogdetails</li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="blog-section">
        <div class="container">
            <h2 class="text-font section-title fw-bold mb-4">Blogdetails</h2>
            <div class="row g-4">

                <div class="col-12">
                    <img src="{{ asset('storage/app/public/provider/logo') }}/{{ $blog->image }}"
                        class="blog-details-img rounded-4">
                </div>
                <div class="col-11 col-lg-10 mx-auto position-relative mt-n5 mt-sm-n7 mt-md-n8">
                    <div class="bg-white shadow rounded-4 p-4">
                        <div class="col-auto">
                            <span class="text-muted"><i
                                    class="fa-solid fa-calendar-days me-1"></i>{{ $blog->created_at->format('d,M,Y') }}</span>
                            <p class="fw-bold fs-2 m-0">{{ $blog->title }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5">
        <div class="container">
            <div class="col-lg-10 mx-auto">
                <p class="blog-description-first">
                    {{ $blog->description }}
                </p>
            </div>
        </div>
    </section>
@endsection
