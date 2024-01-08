@extends('frontend.layouts.master')
@section('title')
    Checkout
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/owl-carousel/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/front/css/calander/fullcalendar.min.css">
@endpush
@section('content')
    <div class="container">
        <div class="breadcrumb-div pt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-primary-color"><i
                                class="fa-solid fa-house fs-7 me-2"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active breadcrumb-item-left" aria-current="page">Servicedetails</li>
                    <li class="breadcrumb-item active breadcrumb-item-left" aria-current="page">Check your booking</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- service detail section -->
    <section class="py-3 appoinment">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="wizzard">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade active show" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab">
                                <div class="card border-0 bg-lights rounded-4 my-4">
                                    <div class="card-body p-4">
                                        <h4 class="fw-semibold mb-4">Booking Information</h4>
                                        <div class="p-3 pb-0 info">
                                            <form action="{{ route('book-now') }}" method="post" id="addressForm">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label class="form-label text-muted">Contact Person Name<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="contact_person_name"
                                                            class="form-control mb-3" placeholder="Name">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form-label text-muted">Contact Person Name<span
                                                                class="text-danger">*</span></label>
                                                        <input type="tel" name="contact_person_number"
                                                            class="form-control mb-3" placeholder="Phone Number">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label class="form-label text-muted">Select Date and Time: <span
                                                                class="text-danger">*</span></label>
                                                        <input type="datetime-local" id="datetime" name="service_schedule"
                                                            class="form-control mb-3">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form-label text-muted">Service Address: <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" id="address-input" class="form-control mb-3"
                                                            placeholder="Enter your address">
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <button type="submit"
                                                            class="btn btn-primary fw-semibold btn-submit rounded-4 float-end">Book
                                                            Now</span></button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tabs navs -->
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Price Summary -->
                    <div class="card mb-3 rounded-4 border overflow-hidden">
                        <div class="card-header p-3 bg-white">
                            <h5 class="fw-bold m-0">Cart Summary</h5>
                        </div>
                        @php
                            $grandTotal = 0;
                        @endphp
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach ($carts as $cart)
                                    <li class="list-group-item d-flex justify-content-between px-0 py-3 fw-semibold">
                                        {{ $cart->variation->variant_key }}
                                        <span class="text-muted">(Inc. Tax) {{ with_currency_symbol($cart->total_cost) }}</span>
                                    </li>
                                    @php
                                        $grandTotal += $cart->total_cost;
                                    @endphp
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between px-0 py-3 fw-semibold">
                                    Subtotal
                                    <span class="text-muted">{{ with_currency_symbol($grandTotal) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 py-3 fw-semibold">
                                    Serviec Fee<span class="text-muted">{{ with_currency_symbol($carts[0]->service->tax) }}</span>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between px-0 pt-3 fw-bold fs-5 text-success">
                                    Total Amount
                                    @php
                                        $grand = $grandTotal + $carts[0]->service->tax;
                                    @endphp
                                    <span id="grand_total">{{ with_currency_symbol($grand) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Price Summary -->
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" id="hidden_grand_total" value="100">
    <input type="hidden" id="timesloturl" value="service/timeslot.html">
    <input type="hidden" id="slotlimiturl" value="service/slotlimit.html">
@endsection
@push('scripts')
    <script src="{{ asset('public/assets/landing') }}/front/js/owl.carousel.js"></script>
    <script src="{{ asset('public/assets/landing') }}/front/js/owl.carousel.min.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0oMAL4zgGPpMVZXoiNiGah-Kti7b6yFc&libraries=places&callback=initAutocomplete"
        async defer></script>

    <script>
        function initAutocomplete() {
            var input = document.getElementById('address-input');
            var options = {
                types: ['geocode']
            };

            var autocomplete = new google.maps.places.Autocomplete(input, options);

            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();

                var addressDetails = {
                    lat: place.geometry.location.lat(),
                    lon: place.geometry.location.lng(),
                    address: place.formatted_address,
                    city: getComponentValue(place, 'locality'),
                    street: getComponentValue(place, 'route'),
                    zip_code: getComponentValue(place, 'postal_code'),
                    country: getComponentValue(place, 'country'),
                    address_type: 'service', // Assuming a default value
                    address_label: '',
                    house: '',
                    floor: ''
                };

                console.log(addressDetails);

                for (var key in addressDetails) {
                    if (addressDetails.hasOwnProperty(key)) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = addressDetails[key];
                        document.getElementById('addressForm').appendChild(input);
                    }
                }
            });
        }

        function getComponentValue(place, type) {
            for (var i = 0; i < place.address_components.length; i++) {
                var component = place.address_components[i];
                for (var j = 0; j < component.types.length; j++) {
                    if (component.types[j] === type) {
                        return component.long_name;
                    }
                }
            }
            return '';
        }
    </script>
@endpush
