@extends('frontend.layouts.master')
@section('title')
    Blogs
@endsection
@section('content')
    <div class="container">

        <div class="breadcrumb-div pt-4">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-primary-color"><i
                                class="fa-solid fa-house fs-7 me-2"></i>Home</a>

                    </li>

                    <li class="breadcrumb-item active breadcrumb-item-left" aria-current="page">Blog</li>

                </ol>

            </nav>

        </div>

    </div>

    <section class="mb-4 blog-sec">

        <div class="container">

            <h2 class="text-font section-title fw-bold">Blogs</h2>

            <div class="row py-4">
                @foreach ($blogs as $blog)
                    <div class="col-md-4 d-flex justify-content-sm-center mb-3">

                        <div class="card border-0 rounded-4 overflow-hidden w-100">

                            <div class="img-overlay rounded-4">

                                <img src="{{ asset('storage/app/public/provider/logo') }}/{{ $blog->image }}"
                                    height="250" class="rounded-4 w-100 object-fit-cover" alt="...">

                            </div>

                            <div class="card-body">
                                <p class="mb-1 fw-normal text-muted"><i class="fa-solid fa-calendar-days me-1"></i>
                                    {{ $blog->created_at->format('d,M,Y') }}</p>
                                <div class="d-flex justify-content-between">

                                    <h5 class="fw-bold"><a class="text-dark"
                                            href="{{ route('blog-detail',$blog->id) }}">{{ $blog->title }}</a>
                                    </h5>

                                </div>

                            </div>

                            <div class="card-footer border-top-0 bg-white">

                                <div class="d-flex justify-content-end">

                                    <a class="fw-semibold text-primary-color"
                                        href="{{ route('blog-detail',$blog->id) }}">Read More<span class="mx-1"><i
                                                class="fa-regular fa-arrow-right"></i></span></a>

                                </div>



                            </div>

                        </div>

                    </div>
                @endforeach
            </div>

        </div>

    </section>
@endsection
