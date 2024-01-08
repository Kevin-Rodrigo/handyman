@extends('adminmodule::layouts.master')

@section('title',translate('Customer_Configuration'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Customer_Settings')}}</h2>
                    </div>

                    <!-- Nav Tabs -->
                    <div class="mb-3">
                        <ul class="nav nav--tabs nav--tabs__style2">
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=loyalty_point"
                                   class="nav-link {{$web_page=='loyalty_point'?'active':''}}">
                                    {{translate('loyalty_point')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=wallet"
                                   class="nav-link {{$web_page=='wallet'?'active':''}}">
                                    {{translate('wallet')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=referral_earning"
                                   class="nav-link {{$web_page=='referral_earning'?'active':''}}">
                                    {{translate('referral_earning')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- End Nav Tabs -->

                    <!-- Tab Content -->
                    @if($web_page=='loyalty_point')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='loyalty_point'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="{{route('admin.customer.settings', ['web_page' => 'loyalty_point'])}}" method="POST" id="landing-info-update-form">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-12 d-flex justify-content-start mb-3">
                                                        @php($value=$data_values->where('key_name','customer_loyalty_point')->first())
                                                        <h4>{{translate('Customer Loyalty Point')}}</h4>
                                                        <label class="switcher mx-2">
                                                            <input class="switcher_input" type="checkbox" value="1" name="customer_loyalty_point"
                                                                {{isset($value) && $value->live_values == '1' ? 'checked' : ''}}>
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-12 row">
                                                        <div class="col-md-4 col-12 mb-30">
                                                            @php($value=$data_values->where('key_name','loyalty_point_percentage_per_booking')->first())
                                                            <label class="mb-1">{{translate('Percentage Of Loyalty Point per Booking Amount')}}
                                                                <i class="material-icons" data-bs-toggle="tooltip" data-bs-placement="top"
                                                                   title="{{translate('On every booking this percent of amount will be added as loyalty point on customer account')}}">info</i>
                                                            </label>
                                                            <input type="number" class="form-control" name="loyalty_point_percentage_per_booking"
                                                                   min="0" max="100" step="any" value="{{$value->live_values??''}}">
                                                        </div>
                                                        @php($value=$data_values->where('key_name','loyalty_point_value_per_currency_unit')->first())
                                                        <div class="col-md-4 col-12 mb-30">
                                                            <label class="mb-1">1 {{currency_code()}} {{translate('equal to how many loyalty points')}}?</label>
                                                            <input type="number" class="form-control" name="loyalty_point_value_per_currency_unit" step="any"
                                                                   min="0" value="{{$value->live_values??''}}">
                                                        </div>
                                                        <div class="col-md-4 col-12 mb-30">
                                                            @php($value=$data_values->where('key_name','min_loyalty_point_to_transfer')->first())
                                                            <label class="mb-1">{{translate('Minimum Loyalty Points To Transfer Into Wallet')}}</label>
                                                            <input type="number" class="form-control" name="min_loyalty_point_to_transfer" step="any"
                                                                   min="0" value="{{$value->live_values??''}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                                                <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($web_page=='wallet')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='wallet'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="{{route('admin.customer.settings', ['web_page' => 'wallet'])}}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-start mb-3">
                                                    @php($value=$data_values->where('key_name','customer_wallet')->first())
                                                    <h4>{{translate('Customer Wallet')}}</h4>
                                                    <label class="switcher mx-2">
                                                        <input class="switcher_input" type="checkbox" value="1" name="customer_wallet"
                                                            {{isset($value) && $value->live_values == '1' ? 'checked' : ''}}>
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- Button -->
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                                                <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($web_page=='referral_earning')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='referral_earning'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="{{route('admin.customer.settings', ['web_page' => 'referral_earning'])}}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-start mb-3">
                                                    @php($value=$data_values->where('key_name','customer_referral_earning')->first())
                                                    <h4>{{translate('Customer Referral Earning')}}</h4>
                                                    <label class="switcher mx-2">
                                                        <input class="switcher_input" type="checkbox" value="1" name="customer_referral_earning"
                                                            {{isset($value) && $value->live_values == '1' ? 'checked' : ''}}
                                                        >
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </div>

                                                <div class="col-12 row">
                                                    @php($value=$data_values->where('key_name','referral_value_per_currency_unit')->first())
                                                    <div class="col-md-12 mb-30">
                                                        <label class="mb-1">{{translate('One Referrer Equal To How Much') . ' ' . currency_code() . '?'}}</label>
                                                        <input type="number" class="form-control" name="referral_value_per_currency_unit" step="any"
                                                               min="0" value="{{$value->live_values??''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Button -->
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                                                <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                @endif
                <!-- End Tab Content -->

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
