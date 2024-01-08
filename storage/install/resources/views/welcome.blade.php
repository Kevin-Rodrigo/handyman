@extends('layouts.landing.app')

@section('title',bs_data($settings,'business_name', 1))

@section('content')
    <!-- Banner Section Start -->
    <section class="banner-section">
        <div class="container">
            <div class="banner-wrapper justify-content-between">
                <div class="banner-content wow animate__fadeInUp">
                    <h6 class="subtitle text--btn">{{bs_data($settings,'top_title', 1)}}</h6>
                    <h1 class="title">{{bs_data($settings,'top_description', 1)}}</h1>
                    <p class="txt text--title">
                        {{bs_data($settings,'top_sub_title', 1)}}
                    </p>
                    <div class="app-btns d-flex flex-wrap">
                        @if($settings->where('key_name','app_url_appstore')->first()->is_active??0)
                            <a href="{{bs_data($settings,'app_url_appstore', 1)}}">
                                <img src="{{asset('public/assets/landing')}}/img/app-btn/app-store.png" alt="">
                            </a>
                        @endif

                        @if($settings->where('key_name','app_url_playstore')->first()->is_active??0)
                            <a href="{{bs_data($settings,'app_url_playstore', 1)}}">
                                <img src="{{asset('public/assets/landing')}}/img/app-btn/google-play.png" alt="">
                            </a>
                        @endif

                        @if($settings->where('key_name','web_url')->first()->is_active??0)
                            <a href="{{bs_data($settings,'web_url', 1)}}">
                                <img src="{{asset('public/assets/landing')}}/img/app-btn/brows_button.png" alt="">
                            </a>
                        @endif
                    </div>
                </div>
                <div class="banner-thumb">
                    <img class="wow animate__dropIn"
                         src="{{asset('storage/app/public/landing-page')}}/{{bs_data($settings,'top_image_1', 1)}}"
                         onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                         alt="banner">
                    <img class="wow animate__dropIn"
                         src="{{asset('storage/app/public/landing-page')}}/{{bs_data($settings,'top_image_2', 1)}}"
                         onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                         alt="banner">
                    <img class="wow animate__dropIn"
                         src="{{asset('storage/app/public/landing-page')}}/{{bs_data($settings,'top_image_3', 1)}}"
                         onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                         alt="banner">
                    <img class="wow animate__dropIn"
                         src="{{asset('storage/app/public/landing-page')}}/{{bs_data($settings,'top_image_4', 1)}}"
                         onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                         alt="banner">
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section End -->
    <!-- Service Section Start -->
    <section class="service-section py-25">
        <div class="scroll-elem" id="service"></div>
        <div class="container position-relative">
            <h3 class="section-title">{{bs_data($settings,'mid_title', 1)}}</h3>
            <div class="service-slide-nav">
                <span class="service-slide-prev slide-icon">
                    <i class="las la-arrow-left"></i>
                </span>
                <span class="service-slide-next slide-icon">
                    <i class="las la-arrow-right"></i>
                </span>
            </div>
            <div class="slider-wrapper">
                <div class="service-slider owl-theme owl-carousel">
                    <!-- Service Item Start -->
                    @foreach($categories as $category)
                        <a href="javascript:void(0)" class="service__item" data-target="slide-{{$category->id}}">
                            <div class="service__item-icon">
                                <img src="{{asset('storage/app/public/category')}}/{{$category->image}}"
                                     onerror="this.src='{{asset('public/assets/placeholder.png')}}'" alt="">
                            </div>
                            <div class="service__item-content">
                                <h6 class="title">{{$category['name']}}</h6>
                                <p class="txt">
                                    {{translate('this_category_is_available_in')}} ( {{$category->zones_count}}
                                    ) {{translate('zones')}}. {{translate('and_available_subcategories')}}
                                    ( {{$category->children->count()}} )
                                </p>
                                <span class="service__item-btn">Read More</span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <!-- Service Item Popup -->
                @foreach($categories as $category)
                    <div class="service__item-popup" data-slide="slide-{{$category->id}}">
                        <div class="service__item-popup-inner">
                            <!-- Close Pop Icon -->
                            <button type="button" class="close__popup">
                                <i class="las la-times"></i>
                            </button>
                            <!-- Close Pop Icon -->
                            <div class="left-content">
                                <div class="service__item-icon">
                                    <img src="{{asset('storage/app/public/category')}}/{{$category->image}}"
                                         onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                         alt="">
                                </div>
                                <div class="service__item-content">
                                    <h6 class="title">{{$category['name']}}</h6>
                                    <p class="txt">
                                        {{translate('this_category_is_available_in')}} ( {{$category->zones_count}}
                                        ) {{translate('zones')}}. {{translate('and_available_subcategories')}}
                                        ( {{$category->children->count()}} )
                                    </p>
                                </div>
                            </div>
                            <div class="right-content">
                                <div class="service-inner-slider owl-theme owl-carousel">
                                    @foreach($category->children as $child)
                                        <div class="service-inner-slider-item">
                                            <img src="{{asset('storage/app/public/category')}}/{{$child->image}}"
                                                 class="w-100 rounded"
                                                 onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                                 alt="">
                                            <span>{{$child['name']}}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Service Section End -->
    <!-- About Section Start -->
    <section class="about-section py-25">
        <div class="scroll-elem" id="about"></div>
        <div class="container">
            <div class="about__wrapper">
                <div class="about__wrapper-content  wow animate__fadeInUp">
                    <h3 class="section-title text-start ms-0">{{bs_data($settings,'about_us_title', 1)}}</h3>
                    <p>
                        {{bs_data($settings,'about_us_description', 1)}}
                    </p>
                    <a href="{{route('page.about-us')}}" class="cmn--btn2">
                        Read More <i class="las la-long-arrow-alt-right"></i>
                    </a>
                </div>
                <div class="about__wrapper-thumb">
                    <img class="main-img"
                         src="{{asset('storage/app/public/landing-page')}}/{{bs_data($settings,'about_us_image', 1)}}"
                         onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                         alt="img">
                    <div class="bg-img">
                        <img src="{{asset('public/assets/landing')}}/img/about-us.png" alt="img">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section End -->
    <!-- Counter Section Start -->
    <section class="contact-info-section py-25 wow animate__fadeInUp">
        <div class="container">
            <div class="row g-2 g-sm-3 g-md-4 justify-content-center">
                @foreach(bs_data($settings,'speciality', 1)??[] as $item)
                    <div class="col-sm-6 col-lg-4">
                        <div class="counter__item">
                            <div class="counter__item-left">
                                <img src="{{asset('storage/app/public/landing-page')}}/{{$item['image']}}"
                                     onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                     alt="counter">
                            </div>
                            <div class="counter__item-right">
                                <h3 class="subtitle">
                                    <span class="ms-1">{{$item['title']}}</span>
                                </h3>
                                <div>{{$item['description']}}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Counter Section End -->
    <!-- App Slider Section Start -->
    <section class="app-slider-section pt-25 pb-50">
        <div class="container">
            <div class="app-slider-wrapper">
                <div class="app-content">
                    <div class="app-slider owl-theme owl-carousel">
                        @foreach(bs_data($settings,'features', 1)??[] as $item)
                            <div>
                                <h3 class="subtitle">{{$item['title']}}</h3>
                                <p>{{$item['sub_title']}}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="slider-bottom mt-4 mt-lg-5 d-flex justify-content-center">
                        <div class="owl-btn app-owl-prev">
                            <i class="las la-long-arrow-alt-left"></i>
                        </div>
                        <div class="app-counter mx-3"></div>
                        <div class="owl-btn app-owl-next">
                            <i class="las la-long-arrow-alt-right"></i>
                        </div>
                    </div>
                </div>
                <div class="app-thumb">
                    <div class="main-thumb">
                        <img class="main-img" src="{{asset('public/assets/landing')}}/img/app/iphone-frame.png"
                             onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                             alt="app">
                        <div class="app-slider owl-theme owl-carousel">
                            @foreach(bs_data($settings,'features', 1)??[] as $item)
                                <img src="{{asset('storage/app/public/landing-page')}}/{{$item['image_1']}}"
                                     onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                     alt="app">
                            @endforeach
                        </div>
                    </div>
                    <div class="smaller-thumb">
                        <img class="main-img" src="{{asset('public/assets/landing')}}/img/app/iphone-frame.png"
                             alt="app">
                        <div class="app-slider owl-theme owl-carousel">
                            @foreach(bs_data($settings,'features', 1)??[] as $item)
                                <img src="{{asset('storage/app/public/landing-page')}}/{{$item['image_2']}}"
                                     onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                     alt="app">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- App Slider Section End -->
    <!-- CTA Section Start -->
    <section class="cta-section py-25">
        <div class="container">
            <div class="cta-main">
                <div class="cta-wrapper bg__img" data-img="{{asset('public/assets/landing')}}/img/cta-bg.png">
                    <img width="238"
                        onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                        src="{{asset('storage/app/public/landing-page')}}/{{bs_data($settings,'provider_section_image', 1)}}"
                        alt="" class="left-icon">
                    <div class="content text-center">
                        <h2 class="title text-uppercase">{{bs_data($settings,'registration_title', 1)}}</h2>
                        <p class="text-btn-title">
                            {{bs_data($settings,'registration_description', 1)}}
                        </p>
                    </div>
                    @if(bs_data($settings,'registration_description', 1)??0)
                        <div class="text-center">
                            <a href="{{route('provider.auth.sign-up')}}"
                               class="cmn--btn">{{translate('register_here')}}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- CTA Section End -->
    <!-- Testimonial Section Start -->
    <section class="testimonial-section pt-25 pb-50">
        <div class="container-fluid">
            <h3 class="section-title mb-0">  {{bs_data($settings,'bottom_title', 1)}}</h3>
            <div class="testimonial-slider owl-theme owl-carousel">
                <!-- Testimonial Slider Single Slide -->
                @foreach(bs_data($settings,'testimonial', 1)??[] as $item)
                    <div class="testimonial__item">
                        <div class="testimonial__item-img">
                            <img src="{{asset('storage/app/public/landing-page')}}/{{$item['image']}}"
                                 onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                 alt="client">
                        </div>
                        <div class="testimonial__item-cont">
                            <span class="fw-bold fs-5">{{$item['name']}}</span><br>
                            <span class="text--secondary">{{$item['designation']}} </span>
                            <blockquote>
                                {{$item['review']}}
                            </blockquote>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Testimonial Slider Bottom Counter and Nav Icons -->
            <div class="slider-bottom d-flex justify-content-center">
                <div class="owl-btn testimonial-owl-prev">
                    <i class="las la-long-arrow-alt-left"></i>
                </div>
                <div class="slider-counter mx-3"></div>
                <div class="owl-btn testimonial-owl-next">
                    <i class="las la-long-arrow-alt-right"></i>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Section End -->
@endsection
