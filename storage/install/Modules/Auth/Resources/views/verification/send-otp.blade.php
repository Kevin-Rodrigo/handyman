@extends('auth::layouts.master')

@section('title',translate('Account Verification'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Login Form -->
    <div class="register-form dark-support"
         data-bg-img="{{asset('public/assets/provider-module')}}/img/media/login-bg.png">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <form action="{{route('provider.auth.verification.send-otp')}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card p-4">
                            <h4 class="mb-30">{{translate('Verify Your Account')}}</h4>
                            <h6 class="mb-2">{{translate('Verify your account in two easy steps-')}}</h6>
                            <ul>
                                <li>{{translate('Fill in your account email/phone below')}}</li>
                                <li>{{translate('We will send you a temporary code ')}}</li>
                                <li>{{translate('Use the code to verify your account on our secure website')}}</li>
                            </ul>

                            <div class="row">
                                <div class="col-10">
                                    <div class="mb-30">
                                        @php($email_verification = business_config('email_verification', 'service_setup')?->live_values ?? 0)
                                        @php($phone_verification = business_config('phone_verification', 'service_setup')?->live_values ?? 0)

                                        @if($email_verification)
                                            <div class="form-floating">
                                                <input type="email" class="form-control" name="identity"
                                                       placeholder="{{translate('Enter your email address')}} *"
                                                       required>
                                                <input type="hidden" name="identity_type" value="email">
                                                <label>{{translate('Enter your email address')}} *</label>
                                            </div>
                                        @elseif($phone_verification)
                                            <div class="form-floating">
                                                <input type="tel" class="form-control" name="identity"
                                                       placeholder="{{translate('Enter your phone number')}} *"
                                                       required>
                                                <input type="hidden" name="identity_type" value="phone">
                                                <label>{{translate('Enter your phone number')}} *</label>
                                            </div>
                                            <label class="small text-danger mb-1">{{translate('Country code is required')}}</label>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn--primary">{{translate('Send OTP')}}</button>
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
