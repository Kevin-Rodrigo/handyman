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
                    <form action="{{route('provider.auth.reset-password.change-password')}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card p-4">
                            <h4 class="mb-30">{{translate('Change Password')}}</h4>

                            <div class="row">
                                <div class="col-10">
                                    <div class="mb-30">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" name="password"
                                                   placeholder="{{translate('Password')}} *" required>
                                            <label>{{translate('Password')}} *</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="mb-30">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" name="confirm_password"
                                                   placeholder="{{translate('Confirm Password')}} *" required>
                                            <label>{{translate('Confirm Password')}} *</label>
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
