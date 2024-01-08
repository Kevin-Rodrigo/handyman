@extends('adminmodule::layouts.master')

@section('title',translate('employee_add'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.css"/>
@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Add_New_employee')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('admin.employee.store')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <h4 class="c1 mb-20">{{translate('General_Information')}}</h4>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="first_name"
                                                    placeholder="{{translate('First_name')}}"
                                                    value="{{old('first_name')}}" required>
                                            <label>{{translate('First_name')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="last_name"
                                                    placeholder="{{translate('Last_name')}}"
                                                    value="{{old('last_name')}}" required>
                                            <label>{{translate('Last_name')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                    placeholder="{{translate('Phone_number')}}"
                                                    value="{{old('phone')}}" required>
                                            <label>{{translate('Phone_number')}}</label>
                                            <small class="text-danger d-flex mt-1">{{translate('* (Country_Code_Required)')}}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" id="address" name="address"
                                                    placeholder="{{translate('address')}}"
                                                    value="{{old('address')}}" required>
                                            <label>{{translate('Address')}}</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <select class="select-identity theme-input-style mb-30" name="role_id"
                                                required>
                                            <option selected disabled>{{translate('Select_role')}}</option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{$role->role_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-30">
                                        <select class="zone-select theme-input-style"
                                                name="zone_ids[]" required multiple>
                                            @foreach($zones as $zone)
                                                <option value="{{$zone->id}}">{{$zone->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-30">
                                        <select class="select-identity theme-input-style" name="identity_type" required>
                                            <option value="0" selected disabled>{{translate('Select_Identity_Type')}}</option>
                                            <option value="passport">{{translate('Passport')}}</option>
                                            <option
                                                value="driving_license">{{translate('Driving_License')}}</option>
                                            <option value="company_id">{{translate('Company_Id')}}</option>
                                            <option value="nid">{{translate('nid')}}</option>
                                            <option
                                                value="trade_license">{{translate('Trade_License')}}</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="identity_number"
                                                    placeholder="Identity Number"
                                                    value="{{old('identity_number')}}" required>
                                            <label>{{translate('Identity_Number')}}</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="row gx-2">
                                            <div class="col-md-6 mb-30 mb-md-0">
                                                <div class="d-flex flex-column align-items-center gap-3">
                                                    <h3 class="mb-0">{{translate('employee_image')}}</h3>
                                                    <div>
                                                        <div class="upload-file">
                                                            <input type="file" class="upload-file__input"
                                                                    name="profile_image" required>
                                                            <div class="upload-file__img">
                                                                <img
                                                                    src="{{asset('public/assets/admin-module')}}/img/media/upload-file.png"
                                                                    alt="">
                                                            </div>
                                                            <span class="upload-file__edit">
                                                                <span class="material-icons">edit</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p class="opacity-75 max-w220 mx-auto">
                                                        {{translate('Image format - jpg, png,jpeg,gif Image Size - maximum size 2 MB Image Ratio - 1:1')}}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex flex-column align-items-center gap-3">
                                                    <h3 class="mb-0">{{translate('Identification_Image')}}</h3>
                                                    <div class="" id="multi_image_picker"></div>
                                                    <p class="opacity-75 max-w220 mx-auto">
                                                        {{translate('Image format - jpg, png,jpeg,gif Image Size - maximum size 2 MB')}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="c1 mb-20 mt-30">{{translate('Account_Information')}}</h4>
                                    <div class="col-12">
                                        <div class="form-floating mb-30">
                                            <input type="email" class="form-control" name="email"
                                                    placeholder="{{translate('Email_*')}}"
                                                    value="{{old('email')}}" required>
                                            <label>{{translate('Email_*')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" name="password" value="password"
                                                    placeholder="{{translate('Password')}}" id="password" required>
                                            <label>{{translate('Password')}}</label>
                                            <span class="material-icons togglePassword">visibility_off</span>
                                        </div>
                                        <small class="text-danger d-flex mb-30 mt-1">{{translate('Password_Must_be_at_Least_8_Digits')}}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-30">
                                            <input type="password" class="form-control" name="confirm-password" value="password"
                                                    placeholder="{{translate('Confirm_Password')}}" id="confirm-password" required>
                                            <label>{{translate('Confirm_Password')}}</label>
                                            <span class="material-icons togglePassword">visibility_off</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-4 flex-wrap justify-content-end">
                                    <button type="reset" class="btn btn--secondary">{{translate('Reset')}}</button>
                                    <button type="submit" class="btn btn--primary">{{translate('Submit')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->
@endsection

@push('script')
    <script src="{{asset('public/assets/admin-module')}}/js/spartan-multi-image-picker.js"></script>
    <script>
        $("#multi_image_picker").spartanMultiImagePicker({
                fieldName: 'identity_images[]',
                maxCount: 2,
                rowHeight: '170px',
                groupClassName: 'item',
                //maxFileSize: '',
                dropFileLabel: "{{translate('Drop_here')}}",
                placeholderImage: {
                    image: '{{asset('public/assets/admin-module')}}/img/media/banner-upload-file.png',
                    width: '100%',
                },

                onRenderedPreview: function (index) {
                    toastr.success('{{translate('Image_added')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onRemoveRow: function (index) {
                    console.log(index);
                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{translate('Please_only_input_png_or_jpg_type_file')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{translate('File_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        );
    </script>

    <script src="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.zone-select').select2({
                placeholder: "Select Zone"
            });
        });
    </script>
@endpush
