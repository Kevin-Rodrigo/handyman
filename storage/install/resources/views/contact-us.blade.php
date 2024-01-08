@extends('layouts.landing.app')

@section('title',translate('privacy_policy'))

@section('content')
    <div class="container pt-3">
        <section class="page-header bg__img" data-img="{{asset('public/assets/landing')}}/img/page-header.png">
            <h3 class="title">{{translate('contact_us')}}</h3>
        </section>
    </div>
    <section class="contact-section pb-60 pt-30">
        <div class="container">
            <div class="row g-4">

                <div class="col-6">
                    <div class="contact__item h-100">
                        <div class="contact__item-icon">
                            <i class="las la-map-marker-alt"></i>
                        </div>
                        <h3 class="contact__item-title">{{translate('address')}}</h3>
                        <ul>
                            <li>
                                {{bs_data($settings,'business_address', 1)}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-6">
                    <div class="contact__item h-100">
                        <div class="contact__item-icon">
                            <i class="las la-phone-volume"></i>
                        </div>
                        <h3 class="contact__item-title">{{translate('call_us')}}</h3>
                        <ul>
                            <li>
                                <a href="Tel:{{bs_data($settings,'business_phone', 1)}}">{{bs_data($settings,'business_phone', 1)}}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-6">
                    <div class="contact__item h-100">
                        <div class="contact__item-icon">
                            <i class="las la-envelope-open-text"></i>
                        </div>
                        <h3 class="contact__item-title">{{translate('email')}}</h3>
                        <ul>
                            <li>
                                <a href="mailto:{{bs_data($settings,'business_email', 1)}}">{{bs_data($settings,'business_email', 1)}}</a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
