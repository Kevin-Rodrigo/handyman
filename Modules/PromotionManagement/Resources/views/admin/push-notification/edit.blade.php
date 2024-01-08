@extends('adminmodule::layouts.master')

@section('title',translate('push_notification'))

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
                        <h2 class="page-title">{{translate('push_notification')}}</h2>
                    </div>

                    <div class="card mb-30">
                        <div class="card-body p-30">
                            <form action="{{route('admin.push-notification.update',[$pushNotification->id])}}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" id="floatingInput" name="title"
                                                   placeholder="Title" required="" value="{{$pushNotification->title}}">
                                            <label for="floatingInput">{{translate('title')}}</label>
                                        </div>
                                        <div class="form-floating mb-30">
                                            <textarea class="form-control" id="floatingInput2"
                                                      placeholder="{{translate('description')}}"
                                                      name="description">{{$pushNotification->description}}</textarea>
                                            <label for="floatingInput2">{{translate('description')}}</label>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-30">
                                                    <select class="select-zone theme-input-style w-100"
                                                            name="zone_ids[]" multiple="multiple">
                                                        @foreach($zones as $zone)
                                                            <option value="{{$zone->id}}" {{in_array($zone->id,collect($pushNotification['zone_ids'])->pluck('id')->toArray())?'selected':''}}>{{$zone->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-30">
                                                    <select class="select-user theme-input-style w-100"
                                                            name="to_users[]" multiple="multiple">
                                                        <option value="customer" {{in_array('customer',$pushNotification->to_users)?'selected':''}}>
                                                            {{translate('customer')}}
                                                        </option>
                                                        <option value="provider-admin" {{in_array('provider-admin',$pushNotification->to_users)?'selected':''}}>
                                                            {{translate('provider')}}
                                                        </option>
                                                        <option value="provider-serviceman" {{in_array('provider-serviceman',$pushNotification->to_users)?'selected':''}}>
                                                            {{translate('serviceman')}}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="d-flex flex-column align-items-center gap-3">
                                            <p class="title-color mb-0">{{translate('upload_cover_image')}}</p>

                                            <div class="upload-file">
                                                <input type="file" class="upload-file__input" name="cover_image">
                                                <div class="upload-file__img upload-file__img_banner">
                                                    <img
                                                        onerror="this.src='{{asset('public/assets/admin-module/img/media/banner-upload-file.png')}}'"
                                                        src="{{asset('storage/app/public/push-notification')}}/{{$pushNotification->cover_image}}"
                                                        alt="">
                                                </div>
                                                <span class="upload-file__edit">
                                                    <span class="material-icons">edit</span>
                                                </span>
                                            </div>

                                            <p class="opacity-75 max-w220 mx-auto">{{translate('Image format - jpg,
                                                png, jpeg, gif Image Size - maximum size 2 MB Image
                                                Ratio - 2:1')}}</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-20 mt-30">
                                            <button class="btn btn--secondary"
                                                    type="reset">{{translate('reset')}}</button>
                                            <button class="btn btn--primary demo_check"
                                                    type="submit">{{translate('update')}}</button>
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

        $(document).ready(function () {
            $('.js-select').select2({
                placeholder: "{{translate('select_items')}}",
            });
            $('.select-zone').select2({
                placeholder: "{{translate('select_zones')}}",
            });
            $('.select-user').select2({
                placeholder: "{{translate('select_users')}}",
            });
        });

    </script>
@endpush
