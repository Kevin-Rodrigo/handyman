@extends('adminmodule::layouts.master')

@section('title',translate('promotional_banner_update'))

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
                        <h2 class="page-title">{{translate('promotional_banner_update')}}</h2>
                    </div>

                    <!-- Promotional Banner -->
                    <div class="card mb-30">
                        <div class="card-body p-30">
                            <form action="{{route('admin.banner.update',[$banner->id])}}" method="POST"
                                  enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="banner_title"
                                                   value="{{$banner->banner_title}}"
                                                   placeholder="{{translate('title')}} *"
                                                   required="">
                                            <label>{{translate('title')}} *</label>
                                        </div>

                                        <div class="mb-3">{{translate('resource_type')}}</div>
                                        <div class="d-flex flex-wrap align-items-center gap-4 mb-30">
                                            <div class="custom-radio">
                                                <input type="radio" id="category" name="resource_type" value="category"
                                                    {{$banner->resource_type=='category'?'checked':''}}>
                                                <label for="category">{{translate('category_wise')}}</label>
                                            </div>
                                            <div class="custom-radio">
                                                <input type="radio" id="service" name="resource_type" value="service"
                                                    {{$banner->resource_type=='service'?'checked':''}}>
                                                <label for="service">{{translate('service_wise')}}</label>
                                            </div>
                                            <div class="custom-radio">
                                                <input type="radio" id="redirect_link" name="resource_type"
                                                       value="link" {{$banner->resource_type=='link'?'checked':''}}>
                                                <label for="redirect_link">{{translate('redirect_link')}}</label>
                                            </div>
                                        </div>

                                        <div class="mb-30" id="category_selector" style="display: {{$banner->resource_type=='category'?'block':'none'}}">
                                            <select class="js-select theme-input-style w-100" name="category_id">
                                                <option value="" selected disabled>---{{translate('Select_Category')}}---</option>
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}" {{$category->id==$banner->resource_id?'selected':''}}>
                                                        {{$category->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-30" id="service_selector" style="display: {{$banner->resource_type=='service'?'block':'none'}}">
                                            <select class="js-select theme-input-style w-100" name="service_id">
                                                <option value="" selected disabled>---{{translate('Select_Service')}}---</option>
                                                @foreach($services as $service)
                                                    <option value="{{$service->id}}" {{$service->id==$banner->resource_id?'selected':''}}>
                                                        {{$service->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-floating mb-30" style="display: {{$banner->resource_type=='link'?'block':'none'}}" id="link_selector">
                                            <input type="url" class="form-control" placeholder="{{translate('redirect_link')}}" value="{{$banner->redirect_link}}">
                                            <label>{{translate('redirect_link')}}</label>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="d-flex flex-column align-items-center gap-3">
                                            <p class="title-color mb-0">{{translate('upload_cover_image')}}</p>
                                            <div>
                                                <div class="upload-file">
                                                    <input type="file" class="upload-file__input" name="banner_image">
                                                    <div class="upload-file__img upload-file__img_banner">
                                                        <img
                                                            onerror="this.src='{{asset('public/assets/admin-module/img/media/banner-upload-file.png')}}'"
                                                            src="{{asset('storage/app/public/banner')}}/{{$banner->banner_image}}"
                                                            alt="">
                                                    </div>
                                                    <span class="upload-file__edit">
                                                        <span class="material-icons">edit</span>
                                                    </span>
                                                </div>
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
                                            <button class="btn btn--primary"
                                                    type="submit">{{translate('update')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End Promotional Banner -->
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
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/dataTables.select.min.js"></script>
    <script>
        "use Strict"
        $('#category').on('click', function () {
            $('#category_selector').show();
            $('#service_selector').hide();
            $('#link_selector').hide();
        });

        $('#service').on('click', function () {
            $('#category_selector').hide();
            $('#service_selector').show();
            $('#link_selector').hide();
        });

        $('#redirect_link').on('click', function () {
            $('#category_selector').hide();
            $('#service_selector').hide();
            $('#link_selector').show();
        });
    </script>
@endpush
