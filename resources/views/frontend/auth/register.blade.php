<!DOCTYPE html>

<html lang="en" dir="ltr">



<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Service</title>

    <!-- <link rel="icon" type="image" sizes="16x16"
    href="../storage/app/public/admin-assets/images/about/favicon/favicon-64d4e00eb67cb.png"> -->

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

    <!-- Responsive CSS -->
    <style>
        :root {
            /* Color */
            --bs-primary: #ef2f2c;
            --bs-secondary: #6fa92a10;
        }
    </style>
</head>



<body>

    <main>
        <section class="register">
            <div class="row m-0 vh-100">
                <div class="col-lg-6 col-md-7 d-none d-lg-block p-0">
                    <!------------- register form image ------------->
                    <div class="left-side vh-100 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('public/assets/landing') }}/admin/images/form/auth-64d0e411d88bb.jpg"
                            class="login-page-img w-100 vh-100 object-fit-cover" style="height: 180px" alt="">
                    </div>
                    <!------------- register form image ------------->
                </div>
                <div
                    class="col-lg-6 col-md-7 col-12 vh-100 d-flex justify-content-center align-items-center m-auto overflow-y-scroll">
                    <div class="right-side vh-100 row justify-content-center align-items-center w-100">
                        <div class="col-xl-7 col-lg-9 col-md-11 col-auto px-3">
                            <!-------------------- register-title -------------------->
                            <div class="register-title">

                                <h2 class="fw-bold mb-2">Create a new account</h2>
                                <p class="text-muted">Already have an account?
                                    <a href="{{ route('login') }}" class="text-primary-color fw-semibold">Login here</a>
                                </p>
                            </div>
                            <!-------------------- register-title -------------------->

                            <!-------------------- register form -------------------->
                            <form class="row my-3 needs-validation" method="POST"
                                action="{{ route('customer-register') }}" novalidate="">
                                @csrf
                                <div class="form-group">
                                    <label for="name" class="form-label text-muted">First Name</label>
                                    <input type="text" class="form-control input-h" name="first_name"
                                        value="{{ old('last_name') }}" placeholder="First Name" required>
                                    <div class="invalid-feedback">
                                        Please fill in the first name
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="form-label text-muted">Last Name</label>
                                    <input type="text" class="form-control input-h" name="last_name" id="name"
                                        placeholder="Last Name" value="{{ old('last_name') }}" required>
                                    <div class="invalid-feedback">
                                        Please fill in the last name
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label text-muted">Email</label>
                                    <input type="email" class="form-control input-h" name="email"
                                        value="{{ old('email') }}" placeholder="Email" required>
                                    <div class="invalid-feedback">
                                        Please fill in the email
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Phone Number (LK Format)</label>
                                    <input type="text" class="form-control phone-number" name="phone"
                                        value="{{ old('phone') }}" required>
                                    <div class="invalid-feedback">
                                        Please fill in the phone
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="form-label text-muted">Password</label>
                                    <input type="password" class="form-control input-h" name="password" id="password"
                                        placeholder="Password" required>
                                    <div class="invalid-feedback">
                                        Please fill in the password
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="form-label text-muted">Confirm Password</label>
                                    <input type="password" class="form-control input-h" name="confirm_password"
                                        placeholder="Confirm Password" required>
                                    <div class="invalid-feedback">
                                        Please fill in the confirm password
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input class="form-check-input" type="checkbox" id="flexCheckChecked"
                                        checked="">
                                    <label class="form-check-label" for="flexCheckChecked">I accept this
                                        <a href="#" class="text-primary fw-semibold">Terms &
                                            Coditions</a>
                                    </label>
                                </div>

                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-primary w-100 mt-3 btn-submit rounded-4"
                                        type="submit">Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
    <script src="{{ asset('public/assets/landing') }}/landing/js/cleave-js/dist/cleave.min.js"></script>
    <script src="{{ asset('public/assets/landing') }}/landing/js/cleave-js/dist/addons/cleave-phone.lk.js"></script>
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
        var cleavePN = new Cleave('.phone-number', {
            phone: true,
            phoneRegionCode: 'LK'
        });
    </script>
    @if (session()->has('notify'))
        @foreach (session('notify') as $msg)
            <script>
                "use strict";
                iziToast.{{ $msg[0] }}({
                    message: "{{ __($msg[1]) }}",
                    position: "topRight"
                });
            </script>
        @endforeach
    @endif

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
