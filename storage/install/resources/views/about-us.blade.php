@extends('layouts.landing.app')

@section('title',bs_data($settings,'business_name', 1))

@section('content')
    <section class="about-section pt-50 pb-50">
        <div class="scroll-elem" id="about"></div>
        <div class="container">
            <div class="about__wrapper">
                <div class="about__wrapper-content  wow animate__fadeInUp">
                    <h3 class="section-title text-start ms-0">{{bs_data($settings,'about_us_title', 1)}}</h3>
                    <p>
                        {!! bs_data($settings,'about_us', 1) !!}
                    </p>
                </div>
                <div class="about__wrapper-thumb ps-xl-4">
                    <img class="main-img" src="{{asset('storage/app/public/landing-page')}}/{{bs_data($settings,'about_us_image', 1)}}" alt="img">
                    <div class="bg-img">
                        <img src="{{asset('public/assets/landing')}}/img/about-us.png" alt="img">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
