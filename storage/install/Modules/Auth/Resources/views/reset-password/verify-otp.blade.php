@extends('auth::layouts.master')

@section('title',translate('Reset Password'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Login Form -->
    <div class="register-form dark-support"
         data-bg-img="{{asset('public/assets/provider-module')}}/img/media/login-bg.png">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <form action="{{route('provider.auth.reset-password.verify-otp')}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card p-4">
                            <h4 class="mb-30">{{translate('Verify OTP')}}</h4>

                            <div class="row">
                                <div class="col-10">
                                    <div class="mb-30">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="otp"
                                                   placeholder="{{translate('Enter OTP')}} *"
                                                   required>
                                            <input type="hidden" name="identity" value="{{session('identity')}}">
                                            <input type="hidden" name="identity_type" value="{{session('identity_type')}}">
                                            <label>{{translate('Enter OTP')}} *</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn--primary">{{translate('Verify OTP')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Login Form -->
@endsection

@push('script')


@endpush
