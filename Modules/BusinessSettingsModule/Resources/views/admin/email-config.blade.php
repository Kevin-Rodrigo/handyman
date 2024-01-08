@extends('adminmodule::layouts.master')

@section('title',translate('email_configuration'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('email_configuration')}}</h2>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="google-map">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="page-title">{{translate('email_setup')}}</h4>
                                </div>
                                <div class="card-body p-30">
                                    <form action="{{route('admin.configuration.set-email-config')}}" method="POST"
                                          id="config-form" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="discount-type">
                                            <div class="row">
                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control"
                                                               name="mailer_name"
                                                               placeholder="{{translate('mailer_name')}} *"
                                                               value="{{bs_data($data_values,'email_config')['mailer_name']??''}}">
                                                        <label>{{translate('mailer_name')}} *</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="host"
                                                               placeholder="{{translate('host')}} *"
                                                               value="{{bs_data($data_values,'email_config')['host']??''}}">
                                                        <label>{{translate('host')}} *</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="driver"
                                                               placeholder="{{translate('driver')}} *"
                                                               value="{{bs_data($data_values,'email_config')['driver']??''}}">
                                                        <label>{{translate('driver')}} *</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="port"
                                                               placeholder="{{translate('port')}} *"
                                                               value="{{bs_data($data_values,'email_config')['port']??''}}">
                                                        <label>{{translate('port')}} *</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="user_name"
                                                               placeholder="{{translate('user_name')}} *"
                                                               value="{{bs_data($data_values,'email_config')['user_name']??''}}">
                                                        <label>{{translate('user_name')}} *</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="email_id"
                                                               placeholder="{{translate('email_id')}} *"
                                                               value="{{bs_data($data_values,'email_config')['email_id']??''}}">
                                                        <label>{{translate('email_id')}} *</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="encryption"
                                                               placeholder="{{translate('encryption')}} *"
                                                               value="{{bs_data($data_values,'email_config')['encryption']??''}}">
                                                        <label>{{translate('encryption')}} *</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12 mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="password"
                                                               placeholder="{{translate('password')}} *"
                                                               value="{{bs_data($data_values,'email_config')['password']??''}}">
                                                        <label>{{translate('password')}} *</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn--primary">
                                                {{translate('update')}}
                                            </button>
                                        </div>
                                    </form>
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
        $('#config-form').on('submit', function (event) {
            event.preventDefault();
            if ('{{env('APP_ENV')=='demo'}}') {
                demo_mode()
            } else {
                var formData = new FormData(document.getElementById("config-form"));
                // Set header if need any otherwise remove setup part
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.configuration.set-email-config')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'post',
                    success: function (response) {
                        console.log(response)
                        if (response.response_code === 'default_400') {
                            toastr.error('{{translate('all_fields_are_required')}}')
                        } else {
                            toastr.success('{{translate('successfully_updated')}}')
                        }
                    },
                    error: function () {

                    }
                });
            }
        });
    </script>
@endpush
