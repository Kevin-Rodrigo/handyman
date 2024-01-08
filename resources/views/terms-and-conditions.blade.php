@extends('layouts.landing.app')

@section('title',translate('terms_and_conditions'))

@section('content')
    <div class="container pt-3">
        <section class="page-header bg__img" data-img="{{asset('public/assets/landing')}}/img/terms-page-header.png">
            <h3 class="title">{{translate('terms_and_conditions')}}</h3>
        </section>
    </div>
    <!-- Page Header End -->
    <section class="privacy-section py-5">
        <div class="container">
            {!! bs_data($settings,'terms_and_conditions', 1) !!}
        </div>
    </section>
@endsection
