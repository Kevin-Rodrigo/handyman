<div class="container">
    <div class="row justify-content-between py-md-4">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <a href="theme-1.html" class="d-flex align-items-center">
                <img src="{{ asset('public/assets/landing') }}/landing/images/png/logo.jpeg" class="logoimage"
                    alt="">

            </a>
            <small class="d-block mt-3 footer-description">HANDYMAN is a complete SaaS-based multi-business
                service booking software, that gives your Users also can create their own business page, receive
                online payments from customers and easily keep track...</small>
        </div>
        <div class="col-xl-7 col-lg-8">
            <div class="row justify-content-between links">
                <div class="col-md-4 col-lg-4 col-xl-4 col-6 mb-4 mb-sm-0">
                    <h5 class="text-white mb-2 mb-md-4 fw-bold">Pages</h5>
                    <ul class="footer-text">
                        <li class="py-1"><a href="{{ route('categories') }}">Categories</a>
                        </li>
                        <li class="py-1"><a href="{{ route('services') }}">Services</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-3 col-6 mb-4 mb-sm-0">
                    <h5 class="text-white mb-2 mb-md-4 fw-bold">Link</h5>
                    <ul class="footer-text">
                        <li class="py-1"><a href="{{ url('login') }}">Sign In</a>
                        </li>
                        <li class="py-1"><a href="{{ route('services') }}">Services</a>
                        </li>
                        <li class="py-1"><a href="{{ route('categories') }}">Categories</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-5 col-lg-5 col-xl-5 col-12 mb-4 mb-sm-0">
                    <h5 class="text-white mb-2 mb-md-4 fw-bold">Get in Touch with Us</h5>
                    <ul class="footer-text">
                        <li class="py-1"><a href="tel:+919016996697" target="_blank"><i
                                    class="fa-light fa-phone me-2"></i>+94 (76) 123 4567</a>
                        </li>
                        <li class="py-1"><a href="mailto:test@example.com"><i
                                    class="fa-regular fa-envelope me-2"></i>info@handyman.com</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 align-items-end justify-content-between mt-0 mt-md-4">
        <!-- Payment card -->
        <div class="col-sm-7 col-md-6 col-lg-4">
            <h5 class="mb-2 text-white fw-bold">Payment &amp; Security</h5>
            <ul class="list-inline mb-0 mt-3">
                <li class="list-inline-item m-0"><img
                        src="{{ asset('public/assets/landing') }}/admin-assets/images/about/payment/cod.png"
                        class="h-30px mb-3 mb-lg-0" alt=""></li>
                <li class="list-inline-item m-0"><img
                        src="{{ asset('public/assets/landing') }}/admin-assets/images/about/payment/razorpay.png"
                        class="h-30px mb-3 mb-lg-0" alt=""></li>
                <li class="list-inline-item m-0"><img
                        src="{{ asset('public/assets/landing') }}/admin-assets/images/about/payment/stripe.png"
                        class="h-30px mb-3 mb-lg-0" alt=""></li>
                <li class="list-inline-item m-0"><img
                        src="{{ asset('public/assets/landing') }}/admin-assets/images/about/payment/flutterwave.png"
                        class="h-30px mb-3 mb-lg-0" alt=""></li>
                <li class="list-inline-item m-0"><img
                        src="{{ asset('public/assets/landing') }}/admin-assets/images/about/payment/paystack.png"
                        class="h-30px mb-3 mb-lg-0" alt=""></li>
                <li class="list-inline-item m-0"><img
                        src="{{ asset('public/assets/landing') }}/admin-assets/images/about/payment/banktransfer.png"
                        class="h-30px mb-3 mb-lg-0" alt=""></li>
                <li class="list-inline-item m-0"><img
                        src="{{ asset('public/assets/landing') }}/admin-assets/images/about/payment/mercadopago.png"
                        class="h-30px mb-3 mb-lg-0" alt=""></li>
                <li class="list-inline-item m-0"><img
                        src="{{ asset('public/assets/landing') }}/admin-assets/images/about/payment/paypal.png"
                        class="h-30px mb-3 mb-lg-0" alt=""></li>
                <li class="list-inline-item m-0"><img
                        src="{{ asset('public/assets/landing') }}/admin-assets/images/about/payment/myfatoorah.png"
                        class="h-30px mb-3 mb-lg-0" alt=""></li>
                <li class="list-inline-item m-0"><img
                        src="{{ asset('public/assets/landing') }}/admin-assets/images/about/payment/toyyibpay.png"
                        class="h-30px mb-3 mb-lg-0" alt=""></li>
                <li class="list-inline-item m-0"><img
                        src="{{ asset('public/assets/landing') }}/admin-assets/images/about/payment/paytab.png"
                        class="h-30px mb-3 mb-lg-0" alt=""></li>
            </ul>
        </div>

        <!-- Social media icon -->
        <div class="col-sm-5 col-md-6 col-lg-3 text-sm-end social-media">
            <h5 class="mb-2 fw-bold text-white">Follow us on</h5>
            <ul class="list-inline mb-0 mt-3">
                <li class="list-inline-item m-0"> <a class="btn-social px-2 bg-facebook mb-0"
                        href="https://www.facebook.com/"><i class="fab fa-fw fa-facebook-f"></i></a> </li>
                <li class="list-inline-item m-0"> <a class="btn-social px-2 bg-instagram mb-0"
                        href="https://www.instagram.com/"><i class="fab fa-fw fa-instagram"></i></a> </li>
                <li class="list-inline-item m-0"> <a class="btn-social px-2 bg-twitter mb-0"
                        href="https://twitter.com/"><i class="fab fa-fw fa-twitter"></i></a> </li>
                <li class="list-inline-item m-0"> <a class="btn-social px-2 bg-linkedin mb-0"
                        href="https://www.linkedin.com/"><i class="fab fa-fw fa-linkedin-in"></i></a> </li>
            </ul>
        </div>
        <hr class="mt-4 text-white mb-0">
    </div>

    <p class="copyright-text py-3 mb-0 text-center">Copyright © 2023 HANDYMAN, All Rights Reserved
    </p>
</div>
