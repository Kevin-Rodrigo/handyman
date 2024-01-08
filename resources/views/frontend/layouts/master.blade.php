<!DOCTYPE html>


<html lang="en" dir="ltr">


<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/webfonts/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/glightbox.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/admin-assets/css/sweetalert/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/calander/fullcalendar.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/admin-assets/css/toastr/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/style.css" />
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/responsive.css">
    <link rel="stylesheet"
        href="{{ asset('public/assets/landing') }}/admin-assets/css/datatables/dataTables.bootstrap5.min.css">
    <!-- wow animation css -->
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/wow.css" />
    <!-- <link rel="icon" type="image" sizes="16x16"
        href="admin-assets/images/about/favicon/favicon-64d4e00eb67cb.png"> -->
    <title> @yield('title')</title>
    <!-- PWA  -->

    <meta name="theme-color" content="#000000">
    <meta name="background-color" content="#015aff">
    <link rel="apple-touch-icon"
        href="{{ asset('public/assets/landing') }}/admin-assets/images/about/logo/logo-64dcc5741edfb.png">

    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/landing/css/iziToast.min.css">

    <!-- Google tag (gtag.js)  END-->
    <style>
        :root {
            --bs-primary: #ef2f2c;
            --bs-secondary: #2691bc;

        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- offer banner -->
    @include('frontend.layouts.includes.navbar')

    <!---------------------------------------------------- subscription popup ---------------------------------------------------->
    <!-- Modal -->
    <div class="modal subscription-popup fade" id="subsciptionmodal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-lights">
                <div class="modal-body p-md-0">
                    <div class="card rounded-4 border-0 bg-lights p-md-3">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="row align-items-center">

                            <div class="col-4 d-none d-lg-block">
                                <img src="storage/app/public/admin-assets/images/index/subscription-64d4b9edb4eef.webp"
                                    alt="" class="w-100 object-fit-cover rounded-4">
                            </div>
                            <div class="col-12 col-lg-8">
                                <h2 class="subscribe-title">Stay Connected with HANDYMAN Subscribe to Our Email
                                    Updates
                                </h2>
                                <p class="fw-light">Subscribe to our email updates and stay in the loop with the latest
                                    news, insights, and exciting developments. Join our community to receive curated
                                    content, exclusive offers, and valuable resources delivered right to your inbox.</p>
                                <form action="https://bookingdo.paponapps.co.in/theme-1/subscribe" method="post">
                                    <input type="hidden" name="_token"
                                        value="SIoJBZfAvdB04rF3HwuFYJQ41PQcrVsOpvSSuFz7">
                                    <div class="bg-body rounded-2 p-2 shadow-sm rounded-4 mb-3 mb-md-0">
                                        <div class="input-group">
                                            <input class="form-control border-0 me-1" type="email" name="email"
                                                placeholder="Enter your email" required>
                                            <button type="submit"
                                                class="btn btn-primary rounded-2 mb-0 btn-submit rounded-4 d-none d-md-inline-block">Subscribe!</button>
                                        </div>
                                    </div>
                                    <button type="submit"
                                        class="btn w-100 btn-primary rounded-2 mb-0 btn-submit rounded-4 d-inline-block d-md-none">Subscribe!</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        @yield('content')
    </div>
    <!-- back-top btn -->
    <div class="back-top-show shadow back-top" id="backtop">
        <i class="fa-solid fa-arrow-up-long"></i>
    </div>
    <!-- back-top btn -->
    <section class="footer-section pt-5">
        @include('frontend.layouts.includes.footer')
    </section>
    <script src="{{ asset('public/assets/landing') }}/front/js/jquery/jquery.min.js"></script>
    <script src="{{ asset('public/assets/landing') }}/front/js/bootstrap/bootstrap.bundle.js"></script>
    <script src="{{ asset('public/assets/landing') }}/front/js/glightbox.js"></script><!-- glightbox JS -->
    <script src="{{ asset('public/assets/landing') }}/admin-assets/js/sweetalert/sweetalert2.min.js"></script><!-- Sweetalert JS -->
    <script src="{{ asset('public/assets/landing') }}/admin-assets/js/toastr/toastr.min.js"></script><!-- Toastr JS -->
    <script src="{{ asset('public/assets/landing') }}/admin-assets/js/sweetalert/sweetalert2.min.js"></script>
    <script src="{{ asset('public/assets/landing') }}/admin-assets/js/datatables/jquery.dataTables.min.js"></script><!-- Datatables JS -->
    <script src="{{ asset('public/assets/landing') }}/admin-assets/js/datatables/dataTables.bootstrap5.min.js"></script>
    <!-- Datatables Bootstrap5 JS -->
    <script src="{{ asset('public/assets/landing') }}/front/js/common.js"></script>
    <script>
        var direction = "ltr";
    </script>
    <script src="{{ asset('public/assets/landing') }}/front/js/index.js"></script>
    <script src="{{ asset('public/assets/landing') }}/landing/js/iziToast.min.js"></script>

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

    <script>
        $(".needs-validation").submit(function() {
            var form = $(this);
            if (form[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.addClass("was-validated");
        });
    </script>
    @stack('scripts')
</body>


</html>
