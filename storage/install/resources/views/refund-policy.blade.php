@extends('layouts.landing.app')

@section('title',translate('refund_policy'))

@section('content')
    <div class="container pt-3">
        <section class="page-header bg__img" data-img="{{asset('public/assets/landing')}}/img/privacy-page-header.png">
            <h3 class="title">{{translate('refund_policy')}}</h3>
        </section>
    </div>
    <!-- Page Header End -->
    <section class="privacy-section py-5">
        <div class="container">
            {!! bs_data($settings,'refund_policy', 1) !!}
        </div>
    </section>
@endsection
