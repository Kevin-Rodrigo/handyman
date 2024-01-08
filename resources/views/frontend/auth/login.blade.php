<html lang="en" dir="ltr">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Service</title>

    <!-- Favicon icon -->

    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/admin/css/bootstrap/bootstrap.min.css">

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/admin/css/fontawesome/all.min.css">

    <!-- FontAwesome CSS -->

    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/admin/css/toastr/toastr.min.css">

    <!-- FontAwesome CSS -->

    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/style.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/responsive.css">

    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/landing/css/iziToast.min.css">
    <script src="{{ asset('public/assets/landing') }}/landing/js/iziToast.min.js"></script>
    <!-- IF VERSION 3  -->
    <style>
        :root {
            /* Color */
            --bs-primary: #bb4044;
            --bs-secondary: #6fa92a10;
        }
    </style>
</head>

<body>
    <main>

        <div class="wrapper">
            <section class="login">
                <div class="row m-0 vh-100">
                    <div class="col-lg-6 col-md-7 d-none d-lg-block p-0">
                        <!------------- login form image ------------->
                        <div class="left-side vh-100 d-flex justify-content-center align-items-center">
                            <img src="{{ asset('public/assets/landing') }}/admin/images/form/auth-64d0e411d88bb.jpg"
                                class="login-page-img w-100 vh-100 object-fit-cover" style="height: 180px"
                                alt="">
                        </div>
                        <!------------- login form image ------------->
                    </div>
                    <div
                        class="col-lg-6 col-md-7 col-12 vh-100 d-flex justify-content-center align-items-center m-auto overflow-y-scroll">
                        <div class="right-side vh-100 row justify-content-center align-items-center w-100">
                            <div class="col-xl-7 col-lg-9 col-md-11 col-auto">
                                <!-------------------- login-title -------------------->
                                <div class="login-title">
                                    <a href="{{ url('/') }}" class="logo p-0">
                                        <img src="{{ asset('public/assets/landing') }}/landing/images/png/logo.jpeg"
                                            alt="" class="mb-2 login-imag object-fit-cover">
                                    </a>
                                    <h2 class="fw-bold mb-2">Welcome back !</h2>
                                    <p class="text-muted">Don't have an account yet?
                                        <a href="{{ route('register') }}"
                                            class="text-primary-color fw-semibold">Register here</a>
                                    </p>
                                </div>
                                <!-------------------- login-title -------------------->

                                <!-------------------- login form -------------------->
                                <form class="row my-3 needs-validation" method="POST"
                                    action="{{ route('customer-login') }}" novalidate="">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email" class="form-label text-muted">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control input-h" name="email_or_phone"
                                            placeholder="Email" required>
                                        <div class="invalid-feedback">
                                            Please fill in the email
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="form-label text-muted">Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control input-h" name="password"
                                            placeholder="Password" required>
                                        <div class="invalid-feedback">
                                            Please fill in the password
                                        </div>
                                    </div>
                                    <div class="mb-3 text-end">
                                        {{-- <a href="forgotpassword" class="text-dark fs-7 fw-500">
                                            <i class="fa-solid fa-lock mx-2 fs-7"></i>Forgot Password?
                                        </a> --}}
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-primary w-100 btn-submit rounded-4"
                                            type="submit">Login</button>
                                    </div>
                                </form>
                                <!-------------------- login form -------------------->

                                <!-------------------- social login -------------------->
                                {{-- <div class="or_section mb-3 m-auto text-muted">
                                    <div class="line ms-4"></div>
                                    <p class="text-center mx-2 fs-7 m-0">Or register with</p>
                                    <div class="line me-4"></div>
                                </div>
                                <div class="">
                                    <a onclick="myFunction()" class="btn btn-light mb-4 rounded-4"><i
                                            class="fa-brands fa-google text-google-icon mx-2"></i>Register with
                                        Google</a>
                                    <a onclick="myFunction()" class="btn btn-light rounded-4"><i
                                            class="fa-brands fa-facebook-f text-facebook mx-2"></i>Register with
                                        Facebook</a>
                                </div> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </main>
    <script src="{{ asset('public/assets/landing') }}/front/js/jquery/jquery.min.js"></script>
    <script src="{{ asset('public/assets/landing') }}/front/js/bootstrap/bootstrap.bundle.js"></script>
    <script src="{{ asset('public/assets/landing') }}/admin/js/toastr/toastr.min.js"></script><!-- Toastr JS -->
    <script>
        toastr.options = {

            "closeButton": true,

        }
    </script>
    <script src="{{ asset('public/assets/landing') }}/front/js/common.js"></script>

    <script>
        "use strict";
        // Bootstrap 4 Validation
        $(".needs-validation").submit(function() {
            var form = $(this);
            if (form[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.addClass("was-validated");
        });
    </script>

    @if ($errors->isNotEmpty())
        @php
            $uniqueErrors = collect($errors->all())
                ->unique()
                ->all();
        @endphp

        <script>
            "use strict";
            @foreach ($uniqueErrors as $error)
                iziToast.error({
                    message: '{{ __($error) }}',
                    position: "topRight"
                });
            @endforeach
        </script>
    @endif
</body>

</html>
