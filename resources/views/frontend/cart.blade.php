@extends('frontend.layouts.master')
@section('title')
    Service Detail
@endsection
@section('content')
    <div class="container">
        <div class="breadcrumb-div pt-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-primary-color" href="{{ url('/') }}"><i
                                class="fa-solid fa-house fs-7 me-2"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item  active breadcrumb-item-left" aria-current="page">Add To Cart</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container contact-us">
        <!---------------------------- contact title ---------------------------->
        <h2 class="section-title fw-bold">Add To Cart</h2>
        <!---------------------------- contact title ---------------------------->

        <!---------------------------- contact info ---------------------------->
        <div class="row py-4">
            <div class="col-lg-8 col-md-12">
                @if (!isset($carts) || count($carts) === 0)
                    <div class="card border-0 rounded-4 shadow-sm bg-lights p-4 mb-3">
                        <div class="row">
                            <div class="col-lg-4">
                                <img class="img-fluid" src="/storage/app/public/landing/images/png/subscriptions.png"
                                    alt="">
                            </div>
                            <div class="col-lg-8 d-flex justify-content-between flex-column">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h3>Empty Cart</h3>
                                        <p>Your cart is currently empty.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @foreach ($carts as $cart)
                        <div class="card border-0 rounded-4 shadow-sm bg-lights p-4 mb-3">
                            <div class="row">
                                <div class="col-lg-4">
                                    <img class="img-fluid" src="/storage/app/public/landing/images/png/subscriptions.png"
                                        alt="">
                                </div>
                                <div class="col-lg-8 d-flex justify-content-between flex-column">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $cart->service->name }}</h3>
                                            <p>{{ $cart->variation->variant_key }}</p>
                                        </div>
                                        <div>
                                            <a href="{{ route('cart-remove', $cart->id) }}">
                                                <span
                                                    style="background-color: lightgray; padding: 3px 9px; border-radius: 50%;"><i
                                                        class="fa fa-close"></i></span></a>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center gap-5">
                                        <div class="input-group mb-3 ">
                                            {{-- <button class="btn btn-outline-secondary" type="button"
                                        id="decrementBtn">-</button> --}}
                                            <input type="text" class="w-25 text-center" id="quantityInput"
                                                value="{{ $cart->quantity }}" readonly
                                                style="background:transparent; border: 1px solid lightgray;">
                                            {{-- <button class="btn btn-outline-secondary" type="button"
                                        id="incrementBtn">+</button> --}}
                                        </div>
                                        <div>
                                            <span>{{ with_currency_symbol($cart->service_cost) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="col-xl-4  col-md-12 col-sm-6 mb-4">
                <div class="card border-0 box-shadow rounded-4 p-3 h-100 text-center">
                    <h5 class="fw-bold"><span></span>Summary</h5>
                    @php
                        $grandTotal = 0;
                    @endphp
                    @foreach ($carts as $cart)
                        <div class="d-flex justify-content-between align-items-center my-2">
                            <p class="mb-0">{{ $cart->variation->variant_key }}
                            </p>
                            <p class="mb-0">{{ $cart->quantity . ' ' . '*' . ' ' . $cart->tax_amount }}</p>
                            <p class="mb-0">Total {{ with_currency_symbol($cart->total_cost) }}</p>
                        </div>
                        @php
                            $grandTotal += $cart->total_cost;
                        @endphp
                    @endforeach
                    <div class="d-flex justify-content-between align-items-center my-2">
                        <p class="mb-0"><strong>Grand Total</strong></p>
                        <p class="mb-0"></p>
                        <p class="mb-0"><strong>{{ with_currency_symbol($grandTotal) }}</strong></p>
                    </div>
                    <a href="{{ route('customer.checkout') }}"
                        class="my-4 btn btn-primary w-100 rounded-2 mb-0 btn-submit rounded-4">Checkout</a>
                </div>
            </div>
        </div>
    </div>
@endsection
