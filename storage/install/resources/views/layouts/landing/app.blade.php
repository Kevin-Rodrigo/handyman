<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <title>@yield('title')</title>

    <meta property="og:image" content="{{asset('storage/app/public/landing-page/meta')}}/{{bs_data($settings,'meta_image', 1,true)}}"/>
    <meta property="og:title" content="{{bs_data($settings,'meta_title', 1,true)}}"/>
    <meta property="og:description" content="{{bs_data($settings,'meta_description', 1,true)}}">
    <!-- CSS -->
    <link rel="stylesheet" href="{{asset('public/assets/landing')}}/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/landing')}}/css/line-awesome.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/landing')}}/css/owl.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/landing')}}/css/main.css"/>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('storage/app/public/business')}}/{{bs_data($settings,'business_favicon', 1)}}" type="image/x-icon"/>

    <style>
        :root {
            --body-bg: {{bs_data($settings,'body_background', 1,true)}};
            --header-bg: {{bs_data($settings,'header_background', 1,true)}};
            --footer: {{bs_data($settings,'footer_background', 1,true)}};
            --footer-bottom: {{bs_data($settings,'header_background', 1,true)}};
        }

        .dark-theme {
            --body-bg: #121213;
            --header-bg: #11202be6;
            --footer: #001f35;
            --footer-bottom: #111a21;
        }
    </style>
</head>

<body>

    <div id="landing-loader"></div>
    <header>
        <!-- Header Top -->
        <div class="navbar-top">
            <div class="container">
                <div class="navbar-top-wrapper">
                    <div class="mode--toggle">
                        <img src="" alt="">
                    </div>
                    <div style="padding-left: 20px">
                        <a href="tel:{{bs_data($settings,'business_phone', 1)}}"
                        class="tel-link text--base">
                            <i class="las la-phone"></i>
                            {{bs_data($settings,'business_phone', 1)}}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header Top -->

        <!-- Header Bottom -->
        <div class="navbar-bottom">
            <div class="container">
                <div class="navbar-bottom-wrapper">
                    <a href="{{route('home')}}" class="logo">
                        <img src="{{asset('storage/app/public/business')}}/{{bs_data($settings,'business_logo', 1)}}"
                            onerror="this.src='{{asset('public/assets/placeholder.png')}}'" alt="">
                    </a>
                    <ul class="menu me-lg-4">
                        <li>
                            <a href="{{route('home')}}"
                            class="{{request()->is('/')?'active':''}}"><span>{{translate('home')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('home')}}#service"><span>{{translate('our_service')}}</span></a>
                        </li>
                        <li>
                            <a href="{{route('page.about-us')}}" class="{{request()->is('page/about-us')?'active':''}}">
                                <span>{{translate('about_us')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('page.privacy-policy')}}"
                            class="{{request()->is('page/privacy-policy')?'active':''}}">
                                <span>{{translate('privacy_policy')}}</span>
                            </a>
                        </li>
                        @if($settings->where('key_name', 'terms_and_conditions')->first()->is_active == '1')
                            <li>
                                <a href="{{route('page.terms-and-conditions')}}"
                                class="{{request()->is('page/terms-and-conditions')?'active':''}}">
                                    <span>{{translate('terms_&_conditions')}}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                    <div class="nav-toggle d-lg-none ms-auto me-2 me-sm-4">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <a href="{{route('page.contact-us')}}" class="cmn--btn">{{translate('contact_us')}}</a>
                </div>
            </div>
        </div>
        <!-- Header Bottom -->
    </header>

    @yield('content')

    <!-- Footer Section Start -->
    <footer>
        <div class="footer-top">
            <div class="container">
                <div class="footer__wrapper">
                    <div class="footer__wrapper-widget">
                        <div class="cont">
                            <a href="#" class="logo">
                                <img src="{{asset('storage/app/public/business')}}/{{bs_data($settings,'business_logo', 1)}}"
                                    onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                    alt="logo">
                            </a>
                            <div class="app-btns">
                                @if($settings->where('key_name','app_url_appstore')->first()->is_active??0)
                                    <a href="{{bs_data($settings,'app_url_appstore', 1)}}" class="d-block">
                                        <img class="w-100"
                                            onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                            src="{{asset('public/assets/landing')}}/img/app-btn/app-store.png" alt="">
                                    </a>
                                @endif

                                @if($settings->where('key_name','app_url_playstore')->first()->is_active??0)
                                    <a href="{{bs_data($settings,'app_url_playstore', 1)}}" class="d-block">
                                        <img class="w-100"
                                            onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                            src="{{asset('public/assets/landing')}}/img/app-btn/google-play.png" alt="">
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="footer__wrapper-widget">
                        <ul class="footer__wrapper-link">
                            <li>
                                <a href="{{route('home')}}">{{translate('home')}}</a>
                            </li>
                            <li>
                                <a href="{{route('page.about-us')}}">{{translate('about_us')}}</a>
                            </li>
                            <li>
                                <a href="{{route('page.contact-us')}}">{{translate('contact_us')}}</a>
                            </li>
                            <li>
                                <a href="{{route('page.privacy-policy')}}">{{translate('privacy_policy')}}</a>
                            </li>
                            @if($settings->where('key_name', 'terms_and_conditions')->first()->is_active == '1')
                                <li>
                                    <a href="{{route('page.terms-and-conditions')}}">{{translate('terms_&_conditions')}}</a>
                                </li>
                            @endif
                            @if($settings->where('key_name', 'cancellation_policy')->first()->is_active == '1')
                                <li>
                                    <a href="{{route('page.cancellation-policy')}}">{{translate('cancellation_policy')}}</a>
                                </li>
                            @endif
                            @if($settings->where('key_name', 'refund_policy')->first()->is_active == '1')
                                <li>
                                    <a href="{{route('page.refund-policy')}}">{{translate('refund_policy')}}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="footer__wrapper-widget">
                        <div class="footer__wrapper-contact">
                            <img class="icon" src="{{asset('public/assets/landing')}}/img/footer/mail.png" alt="footer">
                            <h6>
                                {{translate('send_us_mail')}}
                            </h6>
                            <a href="Mailto:{{bs_data($settings,'business_email', 1)}}">{{bs_data($settings,'business_email', 1)}}</a>
                        </div>
                    </div>
                    <div class="footer__wrapper-widget">
                        <div class="footer__wrapper-contact">
                            <img class="icon" src="{{asset('public/assets/landing')}}/img/footer/tel.png" alt="footer">
                            <h6>
                                {{translate('contact_us')}}
                            </h6>
                            <a href="Tel:{{bs_data($settings,'business_phone', 1)}}">{{bs_data($settings,'business_phone', 1)}}</a>
                        </div>
                    </div>
                    <div class="footer__wrapper-widget">
                        <div class="footer__wrapper-contact">
                            <img class="icon" src="{{asset('public/assets/landing')}}/img/cta-icon.png" alt="footer">
                            <h6>
                                {{translate('become_a_provider')}}
                            </h6>
                            @if(business_config('provider_self_registration','provider_config')->live_values??0)
                                <div>
                                    <a href="{{route('provider.auth.sign-up')}}"
                                    class="cmn--btn">{{translate('register_here')}}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-center py-3">
            {{bs_data($settings,'footer_text', 1)}}
        </div>
    </footer>
    <!-- Footer Section End -->
    <script src="{{asset('public/assets/landing')}}/js/jquery-3.6.0.min.js"></script>
    <script src="{{asset('public/assets/landing')}}/js/viewport.jquery.js"></script>
    <script src="{{asset('public/assets/landing')}}/js/wow.min.js"></script>
    <script src="{{asset('public/assets/landing')}}/js/owl.min.js"></script>

    <script>
        (function ($) {
            "user strict";
            $(window).on("load", () => {
                $("#landing-loader").fadeOut(1000);
                var img = $(".bg__img");
                img.css("background-image", function () {
                    var bg = "url(" + $(this).data("img") + ")";
                    var bg = `url(${$(this).data("img")})`;
                    return bg;
                });
            });
            $(document).ready(function () {
                $(".accordion-title").on("click", function (e) {
                    var element = $(this).parent(".accordion-item");
                    if (element.hasClass("open")) {
                        element.removeClass("open");
                        element.find(".accordion-content").removeClass("open");
                        element.find(".accordion-content").slideUp(200, "swing");
                    } else {
                        element.addClass("open");
                        element.children(".accordion-content").slideDown(200, "swing");
                        element
                            .siblings(".accordion-item")
                            .children(".accordion-content")
                            .slideUp(200, "swing");
                        element.siblings(".accordion-item").removeClass("open");
                        element
                            .siblings(".accordion-item")
                            .find(".accordion-title")
                            .removeClass("open");
                        element
                            .siblings(".accordion-item")
                            .find(".accordion-content")
                            .slideUp(200, "swing");
                    }
                });

                var fixed_top = $(".navbar-bottom");
                $(window).on("scroll", function () {
                    if ($(this).scrollTop() > 110) {
                        fixed_top.addClass("active");
                    } else {
                        fixed_top.removeClass("active");
                    }
                });

                $(".owl-prev").html('<i class="fas fa-angle-left">');
                $(".owl-next").html('<i class="fas fa-angle-right">');

                if ($(".wow").length) {
                    var wow = new WOW({
                        boxClass: "wow",
                        animateClass: "animated",
                        offset: 0,
                        mobile: true,
                        live: true,
                    });
                    wow.init();
                }

                $(".mode--toggle").on("click", function () {
                    setTheme(localStorage.getItem("theme"));
                });
                if (localStorage.getItem("theme") == "light-theme") {
                    localStorage.setItem("theme", "dark-theme");
                } else {
                    localStorage.setItem("theme", "light-theme");
                }
                setTheme(localStorage.getItem("theme"));

                function setTheme(theme) {
                    if (theme == "dark-theme") {
                        localStorage.setItem("theme", "light-theme");
                        $("html").addClass(theme);
                        $(".mode--toggle").find("img").attr("src", "{{asset('public/assets/landing')}}/img/moon.png");
                    } else {
                        localStorage.setItem("theme", "dark-theme");
                        $("html").removeClass("dark-theme");
                        $(".mode--toggle").find("img").attr("src", "{{asset('public/assets/landing')}}/img/sun.png");
                    }
                }

                //Header Bar
                $(".nav-toggle").on("click", () => {
                    $(".nav-toggle").toggleClass("active");
                    $(".menu").toggleClass("active");
                    $(".navbar-bottom-wrapper").toggleClass("rounded-0");
                });

                /* Testimonial Slider */
                var testimonial = $(".testimonial-slider")
                    .on("initialized.owl.carousel changed.owl.carousel", function (e) {
                        if (!e.namespace) {
                            return;
                        }
                        var carousel = e.relatedTarget;
                        $(".slider-counter").text(
                            carousel.relative(carousel.current()) +
                            1 +
                            " / " +
                            carousel.items().length
                        );
                    })
                    .owlCarousel({
                        items: 1,
                        loop: true,
                        margin: 0,
                        nav: false,
                        mouseDrag: true,
                        touchDrag: true,
                        center: true,
                        autoplay: true,
                        speed: 1000,
                        navSpeed: 1000,
                        autoplaySpeed: 1000,
                        smartSpeed: 1000,
                        fluidSpeed: 1000,
                        responsive: {
                            768: {
                                items: 3,
                            },
                        },
                    });
                $(".testimonial-owl-prev").on("click", function () {
                    testimonial.trigger("prev.owl.carousel");
                });
                $(".testimonial-owl-next").on("click", function () {
                    testimonial.trigger("next.owl.carousel");
                });

                /* App Slider */
                var app = $(".app-slider")
                    .on("initialized.owl.carousel changed.owl.carousel", function (e) {
                        if (!e.namespace) {
                            return;
                        }
                        var carousel = e.relatedTarget;
                        $(".app-counter").text(
                            carousel.relative(carousel.current()) +
                            1 +
                            " / " +
                            carousel.items().length
                        );
                    })
                    .owlCarousel({
                        items: 1,
                        loop: true,
                        margin: 0,
                        nav: false,
                        mouseDrag: false,
                        touchDrag: false,
                        autoplay: true,
                        autoplayTimeout: 2500,
                        speed: 1000,
                        autoplaySpeed: 1000,
                        smartSpeed: 1000,
                        fluidSpeed: 1000,
                    });
                $(".app-owl-prev").on("click", function () {
                    app.trigger("prev.owl.carousel");
                    $(".owl-stage").css("transition", "all 0.3s ease 1s !important");
                });
                $(".app-owl-next").on("click", function () {
                    app.trigger("next.owl.carousel");
                });
                /* Service Slider */
                var owl = $(".service-slider").owlCarousel({
                    loop: true,
                    margin: 0,
                    responsiveClass: true,
                    nav: false,
                    dots: false,
                    loop: false,
                    autoplay: true,
                    autoplayTimeout: 1500,
                    autoplayHoverPause: true,
                    mouseDrag: true,
                    touchDrag: true,
                    responsive: {
                        0: {
                            items: 1,
                        },
                        500: {
                            items: 2,
                        },
                        768: {
                            items: 3,
                        },
                        992: {
                            items: 3,
                        },
                        1200: {
                            items: 4,
                        },
                    },
                });
                /*Slider Trigger*/
                $(".service-slide-prev").on("click", function () {
                    owl.trigger("prev.owl.carousel");
                });
                $(".service-slide-next").on("click", function () {
                    owl.trigger("next.owl.carousel");
                });

                $(".service-inner-slider").owlCarousel({
                    loop: true,
                    margin: 0,
                    responsiveClass: true,
                    nav: false,
                    dots: false,
                    loop: false,
                    autoplay: true,
                    autoplayTimeout: 1500,
                    autoplayHoverPause: true,
                    responsive: {
                        0: {
                            items: 3,
                            margin: 10,
                        },
                        500: {
                            items: 4,
                            margin: 10,
                        },
                        576: {
                            items: 3,
                            margin: 10,
                        },
                        768: {
                            items: 5,
                            margin: 20,
                        },
                        992: {
                            items: 6,
                            margin: 30,
                        },
                        1200: {
                            items: 6,
                            margin: 40,
                        },
                    },
                });
                $("[data-target]").on("click", function () {
                    const slide = $(this).data("target");
                    $(".service__item-popup").each(function () {
                        if ($(this).data("slide") === slide) {
                            $(this).addClass("active");
                        } else {
                            $(this).removeClass("active");
                        }
                    });
                });
                $(".close__popup").on("click", function () {
                    $(".service__item-popup").removeClass("active");
                });
            });
        })(jQuery);
    </script>

</body>
</html>
