@extends('adminmodule::layouts.master')

@section('title',translate('payment_gateway_configuration'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.css"/>
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-4">
                        <h2 class="page-title">{{translate('payment_gateway_configuration')}}</h2>
                    </div>

                    <!-- Tab Menu -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
                        <ul class="nav nav--tabs nav--tabs__style2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{$type=='digital_payment'?'active':''}}"
                                href="{{route('admin.configuration.payment-get')}}??type=digital_payment">{{translate('Digital Payment Gateways')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$type=='offline_payment'?'active':''}}"
                                href="{{route('admin.configuration.offline-payment.list')}}?type=offline_payment">{{translate('Offline Payment')}}</a>
                            </li>
                        </ul>
                    </div>

                    @if($published_status == 1)
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-body d-flex justify-content-around">
                                <h4 class="text-danger pt-2">
                                    <i class="tio-info-outined"></i>
                                    Your current payment settings are disabled, because you have enabled
                                    payment gateway addon, To visit your currently active payment gateway settings please follow
                                    the link.</h4>

                                <a href="{{!empty($payment_url) ? $payment_url : ''}}" class="btn btn-outline-primary">{{translate('settings')}}</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Tab Content -->
                    <div class="row">
                        @php($is_published = $published_status == 1 ? 'disabled' : '')
                        @foreach($data_values as $gateway)
                            <div class="col-12 col-md-6 mb-30">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="page-title">{{translate($gateway->key_name)}}</h4>
                                    </div>
                                    <div class="card-body p-30">
                                        <form action="{{route('admin.configuration.payment-set')}}" method="POST"
                                              id="{{$gateway->key_name}}-form" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            @php($additional_data = $gateway['additional_data'] != null ? json_decode($gateway['additional_data']) : [])
                                            <div class="discount-type">
                                                <div class="d-flex align-items-center gap-4 gap-xl-5 mb-30">
                                                    <div class="custom-radio">
                                                        <input type="radio" id="{{$gateway->key_name}}-active"
                                                               name="status"
                                                               value="1" {{$data_values->where('key_name',$gateway->key_name)->first()->live_values['status']?'checked':''}} {{$is_published}}>
                                                        <label
                                                            for="{{$gateway->key_name}}-active">{{translate('active')}}</label>
                                                    </div>
                                                    <div class="custom-radio">
                                                        <input type="radio" id="{{$gateway->key_name}}-inactive"
                                                               name="status"
                                                               value="0" {{$data_values->where('key_name',$gateway->key_name)->first()->live_values['status']?'':'checked'}} {{$is_published}}>
                                                        <label
                                                            for="{{$gateway->key_name}}-inactive">{{translate('inactive')}}</label>
                                                    </div>
                                                </div>

                                                <div class="payment--gateway-img justify-content-center d-flex align-items-center">
                                                    <img style="max-width:100%; height:100px" id="{{$gateway->key_name}}-image-preview"
                                                         src="{{asset('storage/app/public/payment_modules/gateway_image')}}/{{$additional_data != null ? $additional_data->gateway_image : ''}}"
                                                         onerror="this.src='{{asset('public/assets/admin-module')}}/img/placeholder.png'"
                                                         alt="public">
                                                </div>

                                                <input name="gateway" value="{{$gateway->key_name}}" class="hide-div">

                                                @php($mode=$data_values->where('key_name',$gateway->key_name)->first()->live_values['mode'])
                                                <div class="form-floating mb-30 mt-30">
                                                    <select class="js-select theme-input-style w-100" name="mode" {{$is_published}}>
                                                        <option value="live" {{$mode=='live'?'selected':''}}>{{translate('live')}}</option>
                                                        <option value="test" {{$mode=='test'?'selected':''}}>{{translate('test')}}</option>
                                                    </select>
                                                </div>

                                                @php($skip=['gateway','mode','status'])
                                                @foreach($data_values->where('key_name',$gateway->key_name)->first()->live_values as $key=>$value)
                                                    @if(!in_array($key,$skip))
                                                        <div class="form-floating mb-30 mt-30">
                                                            <input type="text" class="form-control"
                                                                   name="{{$key}}"
                                                                   placeholder="{{translate($key)}} *"
                                                                   value="{{env('APP_ENV')=='demo'?'':$value}}" {{$is_published}}>
                                                            <label>{{translate($key)}} *</label>
                                                        </div>
                                                    @endif
                                                @endforeach

                                                <div class="form-floating" style="margin-bottom: 10px">
                                                    <input type="text" class="form-control" id="{{$gateway->key_name}}-title"
                                                           name="gateway_title"
                                                           placeholder="{{translate('payment_gateway_title')}}"
                                                           value="{{$additional_data != null ? $additional_data->gateway_title : ''}}" {{$is_published}}>
                                                           <label for="{{$gateway->key_name}}-title"
                                                            class="form-label">{{translate('payment_gateway_title')}}</label>
                                                </div>

                                                <div class="form-floating mb-3">
                                                    <input type="file" class="form-control" name="gateway_image" accept=".jpg, .png, .jpeg|image/*" id="{{$gateway->key_name}}-image">
                                                    {{-- <label for="{{$gateway->key_name}}-image"
                                                        class="form-label">{{translate('logo')}}</label> --}}
                                                </div>

                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn--primary demo_check" {{$is_published}}>
                                                    {{translate('update')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- End Tab Content -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.js-select').select2();
        });
    </script>

    <script>
        // Function to update the image preview
        function readURL(input, gatewayName) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#' + gatewayName + '-image-preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Trigger the image preview when a file input changes
        $(document).on('change', 'input[name="gateway_image"]', function () {
            var gatewayName = $(this).attr('id').replace('-image', '');
            readURL(this, gatewayName);
        });
    </script>


@endpush
