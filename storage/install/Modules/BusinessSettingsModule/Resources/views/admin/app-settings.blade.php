@extends('adminmodule::layouts.master')

@section('title',translate('app_settings'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('app_settings')}}</h2>
                    </div>

                    <!-- Nav Tabs -->
                    <div class="mb-3">
                        <ul class="nav nav--tabs nav--tabs__style2">
                            <li class="nav-item">
                                <button data-bs-toggle="tab" data-bs-target="#customer" class="nav-link active">
                                    {{translate('Customer')}}
                                </button>
                            </li>
                            <li class="nav-item">
                                <button data-bs-toggle="tab" data-bs-target="#provider"
                                        class="nav-link">
                                    {{translate('Provider')}}
                                </button>
                            </li>
                            <li class="nav-item">
                                <button data-bs-toggle="tab" data-bs-target="#serviceman" class="nav-link">
                                    {{translate('Serviceman')}}
                                </button>
                            </li>
                            <li class="nav-item">
                                <button data-bs-toggle="tab" data-bs-target="#social-login" class="nav-link">
                                    {{translate('Social_login')}}
                                </button>
                            </li>
                        </ul>
                    </div>
                    <!-- End Nav Tabs -->

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="customer">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="page-title">{{translate('Customer_app_configuration')}}</h4>
                                </div>
                                <div class="card-body p-30">
                                    <div class="alert alert-danger mb-30">
                                        <p>
                                            <i class="material-icons">info</i>
                                            {{translate('If there is any update available in the admin panel and for that the previous app will not work. You can force the customer from here by providing the minimum version for force update. That means if a customer has an app below this version the customers must need to update the app first. If you do not need a force update just insert here zero (0) and ignore it.')}}
                                        </p>
                                    </div>
                                    <form action="{{route('admin.configuration.set-app-settings')}}" method="POST"
                                          id="google-map-update-form" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="discount-type">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="mb-30">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control"
                                                                   name="min_version_for_android"
                                                                   placeholder="{{translate('min_version_for_android')}} *"
                                                                   required=""
                                                                   value="{{$customer_data_values->min_version_for_android??''}}">
                                                            <label>{{translate('min_version_for_android')}} *</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="mb-30">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control"
                                                                   name="min_version_for_ios"
                                                                   placeholder="{{translate('min_version_for_IOS')}} *"
                                                                   required=""
                                                                   value="{{$customer_data_values->min_version_for_ios??''}}">
                                                            <label>{{translate('min_version_for_IOS')}} *</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input name="app_type" value="customer" class="hide-div">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn--primary demo_check">
                                                {{translate('update')}}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade" id="provider">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="page-title">{{translate('Provider_app_configuration')}}</h4>
                                </div>
                                <div class="card-body p-30">
                                    <div class="alert alert-danger mb-30">
                                        <p>
                                            <i class="material-icons">info</i>
                                            {{translate('If there is any update available in the admin panel and for that the previous app will not work. You can force the user from here by providing the minimum version for force update. That means if a user has an app below this version the users must need to update the app first. If you do not need a force update just insert here zero (0) and ignore it.')}}
                                        </p>
                                    </div>
                                    <form action="{{route('admin.configuration.set-app-settings')}}" method="POST"
                                          id="google-map-update-form" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="discount-type">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="mb-30">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control"
                                                                   name="min_version_for_android"
                                                                   placeholder="{{translate('min_version_for_android')}} *"
                                                                   required=""
                                                                   value="{{$provider_data_values->min_version_for_android??''}}">
                                                            <label>{{translate('min_version_for_android')}} *</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="mb-30">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control"
                                                                   name="min_version_for_ios"
                                                                   placeholder="{{translate('min_version_for_IOS')}} *"
                                                                   required=""
                                                                   value="{{$provider_data_values->min_version_for_ios??''}}">
                                                            <label>{{translate('min_version_for_IOS')}} *</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input name="app_type" value="provider" class="hide-div">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn--primary demo_check">
                                                {{translate('update')}}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade" id="serviceman">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="page-title">{{translate('Serviceman_app_configuration')}}</h4>
                                </div>
                                <div class="card-body p-30">
                                    <div class="alert alert-danger mb-30">
                                        <p>
                                            <i class="material-icons">info</i>
                                            {{translate('If there is any update available in the admin panel and for that the previous app will not work. You can force the user from here by providing the minimum version for force update. That means if a user has an app below this version the users must need to update the app first. If you do not need a force update just insert here zero (0) and ignore it.')}}
                                        </p>
                                    </div>
                                    <form action="{{route('admin.configuration.set-app-settings')}}" method="POST"
                                          id="google-map-update-form" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="discount-type">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="mb-30">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control"
                                                                   name="min_version_for_android"
                                                                   placeholder="{{translate('min_version_for_android')}} *"
                                                                   required=""
                                                                   value="{{$serviceman_data_values->min_version_for_android??''}}">
                                                            <label>{{translate('min_version_for_android')}} *</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="mb-30">
                                                        <div class="form-floating">
                                                            <input type="text" class="form-control"
                                                                   name="min_version_for_ios"
                                                                   placeholder="{{translate('min_version_for_IOS')}} *"
                                                                   required=""
                                                                   value="{{$serviceman_data_values->min_version_for_ios??''}}">
                                                            <label>{{translate('min_version_for_IOS')}} *</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input name="app_type" value="serviceman" class="hide-div">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn--primary demo_check">
                                                {{translate('update')}}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade" id="social-login">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="page-title">{{translate('Social_login_setup')}}</h4>
                                </div>
                                @php($mediums = ['google', 'facebook'])
                                <div class="card-body p-30">
                                    <div class="table-responsive">
                                        <table id="example" class="table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>{{translate('Medium')}}</th>
                                                    <th>{{translate('Status')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($mediums as $medium)
                                                @php($config = $social_login_configs->where('key_name', $medium.'_social_login')->first())
                                                <tr>
                                                    <td class="text-capitalize">{{translate($medium)}}</td>
                                                    <td>
                                                        <label class="switcher">
                                                            <input class="switcher_input"
                                                                   onclick="update_social_media_status('{{$medium}}_social_login', $(this).is(':checked')===true?1:0)"
                                                                   type="checkbox" {{isset($config) && $config->live_values ? 'checked' : ''}}>
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Tab Content -->

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#google-map').on('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(document.getElementById("google-map-update-form"));
            // Set header if need any otherwise remove setup part
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.configuration.set-third-party-config')}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                success: function (response) {
                    console.log(response)
                    toastr.success('{{translate('successfully_updated')}}')
                },
                error: function () {

                }
            });
        });

        $('#firebase-form').on('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(document.getElementById("firebase-form"));
            // Set header if need any otherwise remove setup part
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.configuration.set-third-party-config')}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                success: function (response) {
                    console.log(response)
                    toastr.success('{{translate('successfully_updated')}}')
                },
                error: function () {

                }
            });
        });

        $('#recaptcha-form').on('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(document.getElementById("recaptcha-form"));
            // Set header if need any otherwise remove setup part
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.configuration.set-third-party-config')}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                success: function (response) {
                    console.log(response)
                    toastr.success('{{translate('successfully_updated')}}')
                },
                error: function () {

                }
            });
        });

        function update_social_media_status(key_name, value) {
            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: '{{translate('want_to_update_status')}}',
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonColor: 'var(--c2)',
                confirmButtonColor: 'var(--c1)',
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.configuration.social-login-config-set')}}",
                        data: {
                            key: key_name,
                            value: value,
                        },
                        type: 'put',
                        success: function (response) {
                            toastr.success('{{translate('successfully_updated')}}')
                        },
                        error: function () {

                        }
                    });
                }
            })
        }
    </script>
@endpush
