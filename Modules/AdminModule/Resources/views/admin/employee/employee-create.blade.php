@extends('adminmodule::layouts.master')

@section('title',translate('add_new_employee'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/select.dataTables.min.css"/>
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('add_new_employee')}}</h2>
                    </div>

                    <!-- Promotional Banner -->
                    <div class="card mb-30">
                        <div class="card-body p-30">
                            <form action="{{route('admin.employee.store')}}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-lg-4 col-6 mb-4 mb-lg-0">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="first_name"
                                                   placeholder="{{translate('first_name')}} *"
                                                   required="">
                                            <label>{{translate('first_name')}} *</label>
                                        </div>

                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="phone"
                                                   placeholder="{{translate('phone_number')}} *"
                                                   required="">
                                            <label>{{translate('phone_number')}} *</label>
                                        </div>

                                        <div class="form-floating mb-30">
                                            <select class="js-select theme-input-style w-100" name="zone_ids[]">
                                                @foreach($roles as $item)
                                                    <option value="{{$item->id}}">{{$item->role_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="identity_number"
                                                   placeholder="{{translate('identity_number')}} *"
                                                   required="">
                                            <label>{{translate('identity_number')}} *</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-6 mb-4 mb-lg-0">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="last_name"
                                                   placeholder="{{translate('last_name')}} *"
                                                   required="">
                                            <label>{{translate('last_name')}} *</label>
                                        </div>

                                        <div class="form-floating mb-30">
                                            <select class="js-select theme-input-style w-100" name="role_id">
                                                @foreach($zones as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-floating mb-30">
                                            <select class="js-select theme-input-style w-100" name="identity_type">
                                                @php($id_types=['passport','driving_licence','nid','trade_license','company_id'])
                                                @foreach($id_types as $item)
                                                    <option value="{{$item}}">{{translate($item)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-12 mb-4 mb-lg-0">
                                         <div class="d-flex flex-column align-items-center gap-3">
                                            <p class="mb-0">{{translate('profile_image')}}</p>
                                            <div>
                                                <div class="upload-file">
                                                    <input type="file" class="upload-file__input"
                                                           name="thumbnail">
                                                    <div class="upload-file__img">
                                                        <img
                                                            src="{{asset('public/assets/admin-module')}}/img/media/upload-file.png"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="opacity-75 max-w220 mx-auto">
                                                {{translate('Image format - jpg, png,jpeg,gif Image Size - maximum size 2 MB Image Ratio - 1:1')}}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-20 mt-30">
                                            <button class="btn btn--secondary"
                                                    type="reset">{{translate('reset')}}</button>
                                            <button class="btn btn--primary"
                                                    type="submit">{{translate('submit')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

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
@endpush
