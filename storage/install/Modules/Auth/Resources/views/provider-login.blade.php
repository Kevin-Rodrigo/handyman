<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Page Title -->
    <title>{{translate('Provider_login')}}</title>
    <!-- Meta Data -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <!-- Favicon -->
    <link rel="shortcut icon"
          href="{{asset('storage/app/public/business')}}/{{(business_config('business_favicon', 'business_information'))->live_values ?? null}}"/>
    <!-- Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet"/>

    <!-- ======= BEGIN GLOBAL MANDATORY STYLES ======= -->
    <link href="{{asset('public/assets/provider-module')}}/css/material-icons.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/bootstrap.min.css"/>
    <link rel="stylesheet"
          href="{{asset('public/assets/provider-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.css"/>
    <!-- ======= END BEGIN GLOBAL MANDATORY STYLES ======= -->

    <!-- ======= MAIN STYLES ======= -->
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/style.css"/>
    <!-- ======= END MAIN STYLES ======= -->
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/toastr.css">
</head>

<body>
<!-- Preloader -->
<div class="preloader"></div>
<!-- End Preloader -->
<?php
    $logo = business_config('business_logo', 'business_information');
?>
<!-- Login Form -->
<div class="login-form" data-bg-img="{{asset('public/assets/provider-module')}}/img/media/login-bg.png">
    <div class="container">
        <div class="row justify-content-center my-3">
            <div class="col-lg-8 col-xl-7">
                <form action="{{route('provider.auth.login')}}" enctype="multipart/form-data" method="POST"
                      id="login-form">
                    @csrf
                    <div class="card ov-hidden">
                        <div class="login-wrap">
                            <div class="login-left">
                                <img class="login-img"
                                     src="{{asset('public/assets/provider-module')}}/img/media/login-img.png"
                                     alt="">
                            </div>
                            <div class="login-right-wrap">
                                <div class="d-flex justify-content-end mt-2 mx-2">
                                    <span class="badge badge-success fz-12 opacity-75">
                                        {{translate('Software_Version')}} : {{ env('SOFTWARE_VERSION') }}
                                    </span>
                                </div>
                                <div class="login-right pt-4">
                                    <div class="text-center mb-30">
                                        <img class="login-img login-logo mb-2"
                                             src="{{asset('storage/app/public/business')}}/{{$logo->live_values}}"
                                             onerror="this.src='{{asset('public/assets/admin-module')}}/img/media/upload-file.png'"
                                             alt="">
                                        <h5 class="text-uppercase c1 mb-3">{{(business_config('business_name', 'business_information'))->live_values ?? null}}</h5>
                                        <h2 class="mb-1">{{translate('sign_in')}}</h2>
                                        <p class="opacity-75">{{translate('sign_in_to_stay_connected')}}</p>
                                    </div>

                                    <div class="mb-4">
                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="email" name="email_or_phone" class="form-control"
                                                       placeholder="{{translate('email')}}" required="" id="email">
                                                <label>{{translate('email')}}</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-floating">
                                                <input type="password" name="password" class="form-control"
                                                       placeholder="{{translate('password')}}" required=""
                                                       id="password">
                                                <label>{{translate('password')}}</label>
                                                <span class="material-icons togglePassword">visibility_off</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex gap-1 align-items-center">
{{--                                                <input type="checkbox" name="remember" id="remember">--}}
{{--                                                <label class="lh-1" for="remember">{{translate('remember_me')}}</label>--}}
                                            </div>
                                            <div class="d-flex gap-1 align-items-center">
                                                <a href="{{route('provider.auth.reset-password.index')}}" class="lh-1" >{{translate('Forget Password')}}?</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="recaptcha d-flex justify-content-center mb-3 dark-support">
                                        @php($recaptcha = business_config('recaptcha', 'third_party'))
                                        @if(isset($recaptcha) && $recaptcha->is_active)
                                            <div id="recaptcha_element" class="w-100" data-type="image"></div>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn--primary radius-50 text-uppercase"
                                                type="submit">{{translate('sign_in')}}</button>
                                    </div>
                                </div>
                                @if(business_config('provider_self_registration','provider_config')->live_values??0)
                                <div class="text-center fz-12 pb-4">
                                    {{translate('Want to Register as Provider')}} <a href="{{route('provider.auth.sign-up')}}" class="c2">{{translate('Register Here')}}</a>
                                </div>
                                @endif

                                @if(env('APP_ENV')=='demo')
                                    <div class="login-footer d-flex justify-content-between c1-bg text-white">
                                        <div>
                                            <div>{{translate('email')}} : provider@provider.com</div>
                                            <div>{{translate('password')}} : 12345678</div>
                                        </div>
                                        <button type="button" class="btn login-copy" onclick="copy_cred()">
                                            <span class="material-symbols-outlined m-0">content_copy</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Login Form -->

<!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->
<script src="{{asset('public/assets/provider-module')}}/js/jquery-3.6.0.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/main.js"></script>
<!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->

{{--toastr and sweetalert--}}
<script src="{{asset('public/assets/provider-module')}}/js/sweet_alert.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if(env('APP_ENV')=='demo')
    <script>
        function copy_cred() {
            $('#email').val('provider@provider.com');
            $('#password').val('12345678');
            toastr.success('Copied successfully!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endif

{{-- recaptcha scripts start --}}
@php($recaptcha = business_config('recaptcha', 'third_party'))
@if(isset($recaptcha) && $recaptcha->is_active)
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

    <script type="text/javascript">
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': '{{$recaptcha->live_values['site_key']}}'
            });
        };

        $("#login-form").on('submit', function (e) {
            var response = grecaptcha.getResponse();

            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{translate('please_check_the_recaptcha')}}");
            }
        });
    </script>
@endif
{{-- recaptcha scripts end --}}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif
</body>
</html>
