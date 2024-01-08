@extends('frontend.layouts.master')
@section('title')
    Service Detail
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/owl-carousel/owl.theme.default.min.css">
@endpush
@section('content')
    <div class="container">
        <div class="breadcrumb-div pt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-primary-color"><i
                                class="fa-solid fa-house fs-7 me-2"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active breadcrumb-item-left" aria-current="page">Servicedetails
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="service-view-sec">
        <div class="container">
            <h2 class="section-title fw-bold">Service Details</h2>
        </div>
        <div class="container-fluid px-0 my-4">
            <div id="services-view" class="owl-carousel owl-theme">
                <div class="item">
                    <a id="gallery" class="w-100 h-100 glightbox" data-glightbox="type: image"
                        href="../storage/app/public/admin-assets/images/service/service.png">
                        <div class="card card-element-hover card-overlay-hover rounded-0 overflow-hidden">
                            <!-- Image -->
                            <img src="{{ asset('public/assets/landing') }}/admin-assets/images/service/service.png"
                                class="w-100 h-100" alt="">
                            <!-- Full screen button -->
                            <div class="hover-element w-100 h-100">
                                <i
                                    class="fa-solid fa-expand fs-6 text-white position-absolute top-50 start-50 translate-middle bg-dark rounded-1 p-2 lh-1"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="col-12 mt-4">
                <div class="card border-0 bg-lights py-md-4 rounded-4">
                    <!-- Card body -->
                    <div class="card-body">
                        <!-- Title and badge -->
                        <div>
                            <div class="d-flex gap-3">
                                @foreach ($service->tags as $tg)
                                    <div class="d-flex">
                                        <!-- Badge -->
                                        <div class="badge text-bg-dark">{{ $tg->tag }}</div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Title -->
                            <h2 class="my-2 fw-bold">{{ $service->name }}</h2>
                            <div class="d-flex">
                                <!-- Rating -->
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item me-1 h6 mb-0 fw-bold">
                                        0.0
                                    </li>
                                    <li class="list-inline-item me-0 small"><i class="fa-regular fa-star text-warning"></i>
                                    </li>
                                    <li class="list-inline-item me-0 small"><i class="fa-regular fa-star text-warning"></i>
                                    </li>
                                    <li class="list-inline-item me-0 small"><i class="fa-regular fa-star text-warning"></i>
                                    </li>
                                    <li class="list-inline-item me-0 small"><i class="fa-regular fa-star text-warning"></i>
                                    </li>
                                    <li class="list-inline-item me-0 small"><i class="fa-regular fa-star text-warning"></i>
                                    </li>
                                </ul>
                                <a href="#" class="mb-0 m-0 mx-2 text-muted">(0
                                    Reviews)</a>
                            </div>
                        </div>
                        <!-- Buttons -->
                    </div>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-lg-12">
                    <div class="card border-0 rounded-4 shadow-sm bg-lights p-4 mb-3">
                        <div class="row">
                            @php
                                $variationData = [];
                            @endphp

                            @foreach ($service->variations as $index => $var)
                                <div class="col-lg-8 d-flex justify-content-between flex-column">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $var->variant_key }}</h3>
                                            <p class="price">{{ with_currency_symbol($var->price) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 d-flex align-items-center">
                                    <div class="d-flex justify-content-between align-items-center gap-5">
                                        <div class="input-group mb-3">
                                            <button class="btn btn-outline-secondary decrementBtn" type="button"
                                                data-index="{{ $index }}">-</button>
                                            <input type="text" class="w-25 text-center quantityInput" value="1"
                                                readonly style="background: transparent; border: 1px solid lightgray;">
                                            <button class="btn btn-outline-secondary incrementBtn" type="button"
                                                data-index="{{ $index }}">+</button>
                                        </div>
                                        <!-- Checkbox for variation selection -->
                                        <input type="checkbox" class="form-check-input variationCheckbox"
                                            data-index="{{ $index }}" data-key="{{ $var->variant_key }}">
                                    </div>
                                </div>

                                @php
                                    $variationData[] = [
                                        'id' => $var->id,
                                        'price' => $var->price,
                                    ];
                                @endphp
                            @endforeach
                            <div class="row  d-flex justify-content-end my-3">
                                <div class="col-lg-3">
                                    <!------------- Book now item ------------->
                                    <div class="card-body">
                                        <!-- Button -->
                                        <div class="d-grid gap-2">
                                            <a class="btn btn-primary fw-semibold btn-submit rounded-4 addToCartBtn">
                                                Add To Cart
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="container">
            <div class="row g-4 my-4">
                <div class="col-lg-12 col-xl-8 order-2 order-xl-0">
                    <!-- Card START -->
                    <div class="card border-0 bg-transparent mb-4">
                        <!-- Card header -->
                        <div class="card-header bg-transparent border-bottom px-0 pt-0 pb-3">
                            <h3 class="mb-0 fw-semibold">Description</h3>
                        </div>
                        <!-- Card body -->
                        <div class="card-body px-0 pb-0" style="font-size: 15px;">
                            <p class="mb-3 text-muted">{!! $service->description !!}</p>
                        </div>
                    </div>
                    <!-- Card END -->

                    <!-- related product -->
                    <div class="card border-0 bg-transparent">
                        <div class="card-header border-bottom bg-transparent px-0 pt-0 pb-3">
                            <h3 class="card-title mb-0 fw-semibold">Related Services</h3>
                        </div>
                        <div class="card-body px-0">
                            <div class="row g-3 related-pro">
                                @foreach ($relatedServices as $relatedService)
                                    <div class="col-md-4 col-xl-4 col-lg-4 col-12">
                                        <div class="card shadow h-100 w-100 border-0 rounded-4 overflow-hidden">
                                            <div class="position-relative overflow-hidden">
                                                <img src="{{ asset('public/assets/landing') }}/admin-assets/images/service/service.png"
                                                    class="card-img-top" alt="...">
                                            </div>
                                            <div class="card-body">
                                                <div class="card-text">
                                                    <h5 class="mb-0 fw-semibold">
                                                        <a
                                                            href="{{ route('service-detail', $relatedService->id) }}">{{ $relatedService->name }}</a>
                                                    </h5>
                                                    <div class="d-flex align-items-center justify-content-between my-2">
                                                        <small
                                                            class="fs-7 text-muted">{{ $relatedService->category->name }}</small>
                                                        <p class="fw-semibold m-0"><i
                                                                class="fas fa-star fa-fw text-warning"></i>
                                                            0.0
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-white border-top p-4 px-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="fw-bold my-0">
                                                        {{ with_currency_symbol($relatedService->min_bidding_price) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                    <!-- Customer Review -->
                    {{-- <div class="card border-0 bg-transparent mt-4">
                        <div class="card-header border-bottom bg-transparent px-0 pt-0">
                            <h2 class="card-title mb-0 fw-bold">Customer review</h2>
                        </div>
                        <div class="card-body pt-4 p-0">
                            <div class="card border-0 bg-lights p-4 mb-4">
                                <div class="row g-4 align-items-center">
                                    <!-- Rating info -->
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <!-- Info -->
                                            <h2 class="mb-0 fw-bold">0.0</h2>
                                            <p class="mb-2 text-muted">Bezogen auf 0 Rezensionen</p>
                                            <!-- Star -->

                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item me-0 small"><i
                                                        class="fa-regular fa-star text-warning"></i>
                                                </li>
                                                <li class="list-inline-item me-0 small"><i
                                                        class="fa-regular fa-star text-warning"></i>
                                                </li>
                                                <li class="list-inline-item me-0 small"><i
                                                        class="fa-regular fa-star text-warning"></i>
                                                </li>
                                                <li class="list-inline-item me-0 small"><i
                                                        class="fa-regular fa-star text-warning"></i>
                                                </li>
                                                <li class="list-inline-item me-0 small"><i
                                                        class="fa-regular fa-star text-warning"></i>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Progress-bar START -->
                                    <div class="col-md-8">
                                        <div class="card-body p-0">
                                            <div class="row gx-3 g-2 align-items-center">
                                                <!-- Progress bar and Rating -->
                                                <div class="col-9 col-sm-10">
                                                    <!-- Progress item -->
                                                    <div class="progress progress-sm bg-warning bg-opacity-15">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Percentage -->
                                                <div class="col-3 col-sm-2 text-end">
                                                    <span class="h6 fw-semibold mb-0">0.0%</span>
                                                </div>

                                                <!-- Progress bar and Rating -->
                                                <div class="col-9 col-sm-10">
                                                    <!-- Progress item -->
                                                    <div class="progress progress-sm bg-warning bg-opacity-15">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Percentage -->
                                                <div class="col-3 col-sm-2 text-end">
                                                    <span class="h6 fw-semibold mb-0">0.0%</span>
                                                </div>

                                                <!-- Progress bar and Rating -->
                                                <div class="col-9 col-sm-10">
                                                    <!-- Progress item -->
                                                    <div class="progress progress-sm bg-warning bg-opacity-15">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Percentage -->
                                                <div class="col-3 col-sm-2 text-end">
                                                    <span class="h6 fw-semibold mb-0">0.0%</span>
                                                </div>

                                                <!-- Progress bar and Rating -->
                                                <div class="col-9 col-sm-10">
                                                    <!-- Progress item -->
                                                    <div class="progress progress-sm bg-warning bg-opacity-15">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Percentage -->
                                                <div class="col-3 col-sm-2 text-end">
                                                    <span class="h6 fw-semibold mb-0">0.0%</span>
                                                </div>

                                                <!-- Progress bar and Rating -->
                                                <div class="col-9 col-sm-10">
                                                    <!-- Progress item -->
                                                    <div class="progress progress-sm bg-warning bg-opacity-15">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Percentage -->
                                                <div class="col-3 col-sm-2 text-end">
                                                    <span class="h6 fw-semibold mb-0">0.0%</span>
                                                </div>
                                            </div> <!-- Row END -->
                                        </div>
                                    </div>
                                    <!-- Progress-bar END -->
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <!-- Rating end -->
                    {{-- <form class="mb-5" action="https://bookingdo.paponapps.co.in/theme-1/postreview" method="POST">
                        <input type="hidden" name="_token" value="SIoJBZfAvdB04rF3HwuFYJQ41PQcrVsOpvSSuFz7">
                        <!-- Rating start -->
                        <div class="rating mb-3">
                            <input type="hidden" name="service_id" id="service_id" value="1">
                            <select class="form-select border-0 bg-lights py-2 px-3" name="ratting"
                                aria-label="Default select example">
                                <option value="5" selected>★★★★★ (5/5)</option>
                                <option value="4">★★★★☆ (4/5)</option>
                                <option value="3">★★★☆☆ (3/5)</option>
                                <option value="2">★★☆☆☆ (2/5)</option>
                                <option value="1">★☆☆☆☆ (1/5)</option>
                            </select>
                        </div>
                        <div class="form-control bg-lights mb-3 border-0">
                            <textarea class="form-control border-0 bg-lights p-0" name="review" id="review" placeholder="Deine Bewertung"
                                rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-4 btn-submit">Beitragsbewertung</button>
                    </form> --}}
                    <!------- review start ------->



                </div>

            </div>
        </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{ asset('public/assets/landing') }}/front/js/owl.carousel.js"></script>
    <script src="{{ asset('public/assets/landing') }}/front/js/glightbox.js"></script><!-- glightbox JS -->
    <script src="{{ asset('public/assets/landing') }}/front/js/owl.carousel.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const incrementBtns = document.querySelectorAll(".incrementBtn");
            const decrementBtns = document.querySelectorAll(".decrementBtn");
            const quantityInputs = document.querySelectorAll(".quantityInput");
            const addToCartBtn = document.querySelector(".addToCartBtn");
            const variationCheckboxes = document.querySelectorAll('.variationCheckbox');
            console.log(variationCheckboxes)
            const variationData = @json($variationData);

            incrementBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    const index = this.getAttribute("data-index");
                    quantityInputs[index].value = parseInt(quantityInputs[index].value) + 1;
                });
            });

            decrementBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    const index = this.getAttribute("data-index");
                    const currentValue = parseInt(quantityInputs[index].value);
                    quantityInputs[index].value = currentValue > 1 ? currentValue - 1 : 1;
                });
            });

            addToCartBtn.addEventListener("click", function() {
                // Get the selected variations using a closure to capture the correct index
                const selectedVariations = Array.from(document.querySelectorAll(
                        '.variationCheckbox:checked'))
                    .map(checkbox => {
                        const index = parseInt(checkbox.getAttribute('data-index'));
                        const variant_key = checkbox.getAttribute('data-key');
                        return {
                            variationKey: variant_key, // Access variant_key here
                            variationId: variationData[index].id,
                            price: variationData[index].price,
                            quantity: quantityInputs[index].value,
                        };
                    });

                // Prepare data to be sent to the server
                const data = {
                    serviceId: "{{ $service->id }}",
                    variations: selectedVariations,
                };

                console.log(data.variations);
                $.ajax({
                    type: "POST",
                    url: "{{ route('cart-store') }}",
                    data: JSON.stringify(data),
                    contentType: "application/json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    dataType: "json",
                    success: function(response) {
                        iziToast.success({
                            message: response.message,
                            position: "topRight"
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr.responseText);
                        var errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse && errorResponse.error) {
                            // Display the error message using iZiToast
                            iziToast.error({
                                message: errorResponse.error,
                                position: 'topRight',
                            });
                        } else {
                            errorResponse.errors.variations.forEach(function(error) {
                                iziToast.error({
                                    message: error,
                                    position: "topRight"
                                });
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
