@extends('adminmodule::layouts.master')

@section('title',translate('landing_page_setup'))

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
                        <h2 class="page-title">{{translate('landing_page_setup')}}</h2>
                    </div>

                    <!-- Nav Tabs -->
                    <div class="mb-3">
                        <ul class="nav nav--tabs nav--tabs__style2">
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=text_setup"
                                   class="nav-link {{$web_page=='text_setup'?'active':''}}">
                                    {{translate('text_setup')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=button_and_links"
                                   class="nav-link {{$web_page=='button_and_links'?'active':''}}">
                                    {{translate('button_&_links')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=speciality"
                                   class="nav-link {{$web_page=='speciality'?'active':''}}">
                                    {{translate('speciality')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=testimonial"
                                   class="nav-link {{$web_page=='testimonial'?'active':''}}">
                                    {{translate('testimonial')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=features"
                                   class="nav-link {{$web_page=='features'?'active':''}}">
                                    {{translate('features')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=images"
                                   class="nav-link {{$web_page=='images'?'active':''}}">
                                    {{translate('images')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=background"
                                   class="nav-link {{$web_page=='background'?'active':''}}">
                                    {{translate('background')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=social_media"
                                   class="nav-link {{$web_page=='social_media'?'active':''}}">
                                    {{translate('social_media')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=meta"
                                   class="nav-link {{$web_page=='meta'?'active':''}}">
                                    {{translate('meta')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=web_app"
                                   class="nav-link {{$web_page=='web_app'?'active':''}}">
                                    {{translate('Web_App')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url()->current()}}?web_page=web_app_image"
                                   class="nav-link {{$web_page=='web_app_image'?'active':''}}">
                                    {{translate('Web_App')}} <small class="opacity-75">({{translate('Images')}})</small>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- End Nav Tabs -->

                    <!-- Tab Content -->
                    @if($web_page=='text_setup')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='text_setup'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="javascript:void(0)" method="POST" id="landing-info-update-form">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="top_title"
                                                                       value="{{$data_values->where('key_name','top_title')->first()->live_values??''}}">
                                                                <label>{{translate('top_title')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="top_description"
                                                                       value="{{$data_values->where('key_name','top_description')->first()->live_values??''}}">
                                                                <label>{{translate('top_description')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="top_sub_title"
                                                                       value="{{$data_values->where('key_name','top_sub_title')->first()->live_values??''}}">
                                                                <label>{{translate('top_sub_title')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="mid_title"
                                                                       value="{{$data_values->where('key_name','mid_title')->first()->live_values??''}}">
                                                                <label>{{translate('mid_title')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="about_us_title"
                                                                       value="{{$data_values->where('key_name','about_us_title')->first()->live_values??''}}">
                                                                <label>{{translate('about_us_title')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="about_us_description"
                                                                       value="{{$data_values->where('key_name','about_us_description')->first()->live_values??''}}">
                                                                <label>{{translate('about_us_description')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="registration_title"
                                                                       value="{{$data_values->where('key_name','registration_title')->first()->live_values??''}}">
                                                                <label>{{translate('registration_title')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="registration_description"
                                                                       value="{{$data_values->where('key_name','registration_description')->first()->live_values??''}}">
                                                                <label>{{translate('registration_description')}}
                                                                    *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="bottom_title"
                                                                       value="{{$data_values->where('key_name','bottom_title')->first()->live_values??''}}">
                                                                <label>{{translate('bottom_title')}} *</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('update')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($web_page=='button_and_links')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='button_and_links'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="javascript:void(0)" method="POST" id="landing-info-update-form">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        @php($value=$data_values->where('key_name','app_url_playstore')->first()->is_active??0)
                                                        <label class="switcher mb-4">
                                                            <input class="switcher_input" type="checkbox"
                                                                   name="app_url_playstore_is_active"
                                                                   {{$value?'checked':''}}
                                                                   value="1">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="app_url_playstore"
                                                                       value="{{$data_values->where('key_name','app_url_playstore')->first()->live_values??''}}">
                                                                <label>
                                                                    {{translate('app_url_( playstore )')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        @php($value=$data_values->where('key_name','app_url_appstore')->first()->is_active??0)
                                                        <label class="switcher mb-4">
                                                            <input class="switcher_input" type="checkbox"
                                                                   name="app_url_appstore_is_active"
                                                                   {{$value?'checked':''}}
                                                                   value="1">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="app_url_appstore"
                                                                       value="{{$data_values->where('key_name','app_url_appstore')->first()->live_values??''}}">
                                                                <label>
                                                                    {{translate('app_url_( appstore )')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        @php($value=$data_values->where('key_name','web_url')->first()->is_active??0)
                                                        <label class="switcher mb-4">
                                                            <input class="switcher_input" type="checkbox"
                                                                   name="web_url_is_active"
                                                                   {{$value?'checked':''}}
                                                                   value="1">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="web_url"
                                                                       value="{{$data_values->where('key_name','web_url')->first()->live_values??''}}">
                                                                <label>{{translate('web_url')}} *</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('update')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($web_page=='speciality')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='speciality'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form
                                            action="{{route('admin.business-settings.set-landing-information')}}?web_page={{$web_page}}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="title">
                                                                <label>
                                                                    {{translate('speciality_title')}}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="description">
                                                                <label>
                                                                    {{translate('speciality_description')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-30 d-flex flex-column align-items-center gap-2">
                                                            <div class="upload-file mb-30 max-w-100">
                                                                <input type="file" class="upload-file__input"
                                                                       name="image">
                                                                <div class="upload-file__img">
                                                                    <img
                                                                        src='{{asset('public/assets/admin-module/img/media/upload-file.png')}}'
                                                                        alt="">
                                                                </div>
                                                                <span class="upload-file__edit">
                                                                    <span class="material-icons">edit</span>
                                                                </span>
                                                            </div>
                                                            <p class="opacity-75 max-w220">{{translate('Image format - jpg, png, jpeg, gif Image Size - maximum size 2 MB Image Ratio - 1:1')}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('add')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="card-body p-30">
                                        <div class="table-responsive">
                                            <table id="example" class="table align-middle">
                                                <thead>
                                                <tr>
                                                    <th>{{translate('title')}}</th>
                                                    <th>{{translate('description')}}</th>
                                                    <th>{{translate('image')}}</th>
                                                    <th>{{translate('action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($data_values[0]->live_values??[] as $key=>$item)
                                                    <tr>
                                                        <td>{{$item['title']}}</td>
                                                        <td>{{$item['description']}}</td>
                                                        <td>
                                                            <img style="height: 50px;width: 50px"
                                                                 src="{{asset('storage/app/public/landing-page')}}/{{$item['image']}}">
                                                        </td>
                                                        <td>
                                                            <div class="table-actions">
                                                                <button type="button"
                                                                        onclick="form_alert('delete-{{$item['id']}}','{{translate('want_to_delete_this')}}?')"
                                                                        class="table-actions_delete bg-transparent border-0 p-0">
                                                                    <span class="material-icons">delete</span>
                                                                </button>
                                                                <form
                                                                    action="{{route('admin.business-settings.delete-landing-information',[$web_page,$item['id']])}}"
                                                                    method="post" id="delete-{{$item['id']}}"
                                                                    class="hidden">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </div>
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
                    @endif

                    @if($web_page=='testimonial')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='testimonial'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form
                                            action="{{route('admin.business-settings.set-landing-information')}}?web_page={{$web_page}}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="name">
                                                                <label>
                                                                    {{translate('reviewer_name')}}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="designation">
                                                                <label>
                                                                    {{translate('reviewer_designation')}}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="review">
                                                                <label>
                                                                    {{translate('review')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-30 d-flex flex-column align-items-center gap-2">
                                                            <div class="upload-file mb-30 max-w-100">
                                                                <input type="file" class="upload-file__input"
                                                                       name="image">
                                                                <div class="upload-file__img">
                                                                    <img
                                                                        src='{{asset('public/assets/admin-module/img/media/upload-file.png')}}'
                                                                        alt="">
                                                                </div>
                                                                <span class="upload-file__edit">
                                                                    <span class="material-icons">edit</span>
                                                                </span>
                                                            </div>
                                                            <p class="opacity-75 max-w220">{{translate('Image format - jpg, png, jpeg, gif Image Size - maximum size 2 MB Image Ratio - 1:1')}}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('add')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="card-body p-30">
                                        <div class="table-responsive">
                                            <table id="example" class="table align-middle">
                                                <thead>
                                                <tr>
                                                    <th>{{translate('name')}}</th>
                                                    <th>{{translate('designation')}}</th>
                                                    <th>{{translate('review')}}</th>
                                                    <th>{{translate('image')}}</th>
                                                    <th>{{translate('action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($data_values[0]->live_values??[] as $key=>$item)
                                                    <tr>
                                                        <td>{{$item['name']}}</td>
                                                        <td>{{$item['designation']}}</td>
                                                        <td>{{$item['review']}}</td>
                                                        <td>
                                                            <img style="height: 50px;width: 50px"
                                                                 src="{{asset('storage/app/public/landing-page')}}/{{$item['image']}}">
                                                        </td>
                                                        <td>
                                                            <div class="table-actions">
                                                                <button type="button"
                                                                        onclick="form_alert('delete-{{$item['id']}}','{{translate('want_to_delete_this')}}?')"
                                                                        class="table-actions_delete bg-transparent border-0 p-0">
                                                                    <span class="material-icons">delete</span>
                                                                </button>
                                                                <form
                                                                    action="{{route('admin.business-settings.delete-landing-information',[$web_page,$item['id']])}}"
                                                                    method="post" id="delete-{{$item['id']}}"
                                                                    class="hidden">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </div>
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
                    @endif

                    @if($web_page=='features')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='features'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form
                                            action="{{route('admin.business-settings.set-landing-information')}}?web_page={{$web_page}}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="title">
                                                                <label>{{translate('feature_title')}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="sub_title">
                                                                <label>{{translate('feature_sub_title')}}</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="mb-30 d-flex flex-column align-items-center gap-2">
                                                            <div class="upload-file mb-30 max-w-100">
                                                                <input type="file" class="upload-file__input"
                                                                       name="image_1">
                                                                <div class="upload-file__img">
                                                                    <img
                                                                        src='{{asset('public/assets/admin-module/img/media/upload-file.png')}}'
                                                                        alt="">
                                                                </div>
                                                                <span class="upload-file__edit">
                                                                    <span class="material-icons">edit</span>
                                                                </span>
                                                            </div>
                                                            <p class="opacity-75 max-w220">{{translate('Image Size - 200x381')}}</p>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="mb-30 d-flex flex-column align-items-center gap-2">
                                                            <div class="upload-file mb-30 max-w-100">
                                                                <input type="file" class="upload-file__input"
                                                                       name="image_2">
                                                                <div class="upload-file__img">
                                                                    <img
                                                                        src='{{asset('public/assets/admin-module/img/media/upload-file.png')}}'
                                                                        alt="">
                                                                </div>
                                                                <span class="upload-file__edit">
                                                                    <span class="material-icons">edit</span>
                                                                </span>
                                                            </div>
                                                            <p class="opacity-75 max-w220">{{translate('Image Size - 200x381')}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('add')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="card-body p-30">
                                        <div class="table-responsive">
                                            <table id="example" class="table align-middle">
                                                <thead>
                                                <tr>
                                                    <th>{{translate('title')}}</th>
                                                    <th>{{translate('sub_title')}}</th>
                                                    <th>{{translate('images')}}</th>
                                                    <th>{{translate('action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($data_values[0]->live_values??[] as $key=>$item)
                                                    <tr>
                                                        <td>{{$item['title']}}</td>
                                                        <td>{{$item['sub_title']}}</td>
                                                        <td>
                                                            <img style="height: 50px;width: 50px"
                                                                 src="{{asset('storage/app/public/landing-page')}}/{{$item['image_1']}}">
                                                            <img style="height: 50px;width: 50px"
                                                                 src="{{asset('storage/app/public/landing-page')}}/{{$item['image_2']}}">
                                                        </td>
                                                        <td>
                                                            <div class="table-actions">
                                                                <button type="button"
                                                                        onclick="form_alert('delete-{{$item['id']}}','{{translate('want_to_delete_this')}}?')"
                                                                        class="table-actions_delete bg-transparent border-0 p-0">
                                                                    <span class="material-icons">delete</span>
                                                                </button>
                                                                <form
                                                                    action="{{route('admin.business-settings.delete-landing-information',[$web_page,$item['id']])}}"
                                                                    method="post" id="delete-{{$item['id']}}"
                                                                    class="hidden">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </div>
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
                    @endif

                    @if($web_page=='images')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='images'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <div class="discount-type">
                                            <div class="row">
                                                @php($keys = ['top_image_1', 'top_image_2', 'top_image_3', 'top_image_4', 'about_us_image', 'service_section_image', 'provider_section_image'])
                                                @php($ratios = ['370x200', '315x200', '200x200', '485x200', '684x440', '200x350', '238x228'])
                                                @foreach($keys as $index=>$key)
                                                    <div class="col-md-3 mb-30">
                                                        <form
                                                            action="{{route('admin.business-settings.set-landing-information')}}?web_page={{$web_page}}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div
                                                                class="mb-1 d-flex flex-column align-items-center gap-2">
                                                                <p class="title-color text-center" style="width: 160px">
                                                                    {{translate($key)}}, {{translate('size')}}
                                                                    :{{$ratios[$index]}}
                                                                </p>
                                                                <div class="upload-file max-w-100">
                                                                    <input type="file" class="upload-file__input"
                                                                           name="{{$key}}" id="image-{{$key}}">
                                                                    <div class="upload-file__img">
                                                                        <img
                                                                            onerror="this.src='{{asset('public/assets/admin-module/img/media/upload-file.png')}}'"
                                                                            src='{{asset('storage/app/public/landing-page')}}/{{$data_values->where('key_name',$key)->first()->live_values??''}}'
                                                                            alt="">
                                                                    </div>
                                                                    <span class="upload-file__edit"
                                                                            onclick="$('#image-{{$key}}').click()">
                                                                        <span class="material-icons">edit</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex gap-2 justify-content-center">
                                                                <button type="submit"
                                                                        class="btn btn--primary btn-block">
                                                                    {{translate('upload')}}
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($web_page=='background')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='background'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="javascript:void(0)" method="POST" id="landing-info-update-form">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="color" class="form-control"
                                                                       name="header_background"
                                                                       value="{{$data_values->where('key_name','header_background')->first()->live_values??"#E3F2FC"}}">
                                                                <label>
                                                                    {{translate('header_background')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="color" class="form-control"
                                                                       name="body_background"
                                                                       value="{{$data_values->where('key_name','body_background')->first()->live_values??'white'}}">
                                                                <label>
                                                                    {{translate('body_background')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="color" class="form-control"
                                                                       name="footer_background"
                                                                       value="{{$data_values->where('key_name','footer_background')->first()->live_values??'#E3F2FC'}}">
                                                                <label>
                                                                    {{translate('footer_background')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('update')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($web_page=='social_media')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='social_media'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form
                                            action="{{route('admin.business-settings.set-landing-information')}}?web_page={{$web_page}}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-30">
                                                            <select class="js-select theme-input-style w-100" name="media" required>
                                                                <option value="" selected disabled>---{{translate('Select_media')}}---</option>
                                                                <option value="facebook">{{translate('Facebook')}}</option>
                                                                <option value="instagram">{{translate('Instagram')}}</option>
                                                                <option value="linkedin">{{translate('LinkedIn')}}</option>
                                                                <option value="twitter">{{translate('Twitter')}}</option>
                                                                <option value="youtube">{{translate('Youtube')}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="link"
                                                                    placeholder="{{translate('link')}}" required>
                                                                <label>{{translate('link')}}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('add')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="card-body p-30">
                                        <div class="table-responsive">
                                            <table id="example" class="table align-middle">
                                                <thead>
                                                <tr>
                                                    <th>{{translate('media')}}</th>
                                                    <th>{{translate('link')}}</th>
                                                    <th>{{translate('action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($data_values[0]->live_values??[] as $key=>$item)
                                                    <tr>
                                                        <td>{{$item['media']}}</td>
                                                        <td><a href="{{$item['link']}}">{{$item['link']}}</a></td>
                                                        <td>
                                                            <div class="table-actions">
                                                                <button type="button"
                                                                        onclick="form_alert('delete-{{$item['id']}}','{{translate('want_to_delete_this')}}?')"
                                                                        class="table-actions_delete bg-transparent border-0 p-0">
                                                                    <span class="material-icons">delete</span>
                                                                </button>
                                                                <form
                                                                    action="{{route('admin.business-settings.delete-landing-information',[$web_page,$item['id']])}}"
                                                                    method="post" id="delete-{{$item['id']}}"
                                                                    class="hidden">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </div>
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
                    @endif

                    @if($web_page=='meta')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='meta'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="javascript:void(0)" method="POST" id="landing-info-update-form">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       placeholder="{{translate('meta_title')}} *"
                                                                       name="meta_title"
                                                                       value="{{$data_values->where('key_name','meta_title')->first()->live_values??''}}"
                                                                       required>
                                                                <label>
                                                                    {{translate('meta_title')}}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       placeholder="{{translate('meta_description')}} *"
                                                                       name="meta_description"
                                                                       value="{{$data_values->where('key_name','meta_description')->first()->live_values??''}}"
                                                                       required>
                                                                <label>
                                                                    {{translate('meta_description')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-30 d-flex flex-column align-items-center gap-2">
                                                            <div class="upload-file mb-30 max-w-100">
                                                                <input type="file" class="upload-file__input"
                                                                       name="meta_image">
                                                                <div class="upload-file__img">
                                                                    <img
                                                                        src="{{asset('storage/app/public/landing-page/meta')}}/{{$data_values->where('key_name','meta_image')->first()->live_values??''}}"
                                                                        onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                                                        alt="">
                                                                </div>
                                                                <span class="upload-file__edit">
                                                                    <span class="material-icons">edit</span>
                                                                </span>
                                                            </div>
                                                            <p class="opacity-75 max-w220">{{translate('Image format - jpg, png, jpeg, gif Image Size - maximum size 2 MB Image Ratio - 1:1')}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('add')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($web_page=='web_app')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='web_app'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <form action="javascript:void(0)" method="POST" id="landing-info-update-form">
                                            @csrf
                                            @method('PUT')
                                            <div class="discount-type">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="web_top_title"
                                                                       placeholder="{{translate('top_title')}}"
                                                                       value="{{$data_values->where('key_name','web_top_title')->first()->live_values??''}}"
                                                                       required>
                                                                <label>{{translate('top_title')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control"
                                                                       name="web_top_description"
                                                                       placeholder="{{translate('top_description')}}"
                                                                       value="{{$data_values->where('key_name','web_top_description')->first()->live_values??''}}"
                                                                       required>
                                                                <label>{{translate('top_description')}} *</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="web_mid_title"
                                                                       placeholder="{{translate('mid_title')}}"
                                                                       value="{{$data_values->where('key_name','web_mid_title')->first()->live_values??''}}"
                                                                       required>
                                                                <label>{{translate('mid_title')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="mid_sub_title_1"
                                                                       value="{{$data_values->where('key_name','mid_sub_title_1')->first()->live_values??''}}"
                                                                       placeholder="{{translate('mid_sub_title_1')}}" required>
                                                                <label>{{translate('mid_sub_title_1')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="mid_sub_description_1"
                                                                       value="{{$data_values->where('key_name','mid_sub_description_1')->first()->live_values??''}}"
                                                                       placeholder="{{translate('mid_sub_description_1')}}" required>
                                                                <label>{{translate('mid_sub_description_1')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="mid_sub_title_2"
                                                                       value="{{$data_values->where('key_name','mid_sub_title_2')->first()->live_values??''}}"
                                                                       placeholder="{{translate('mid_sub_title_2')}}" required>
                                                                <label>{{translate('mid_sub_title_2')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="mid_sub_description_2"
                                                                       value="{{$data_values->where('key_name','mid_sub_description_2')->first()->live_values??''}}"
                                                                       placeholder="{{translate('mid_sub_description_2')}}" required>
                                                                <label>{{translate('mid_sub_description_2')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="mid_sub_title_3"
                                                                       value="{{$data_values->where('key_name','mid_sub_title_3')->first()->live_values??''}}"
                                                                       placeholder="{{translate('mid_sub_title_3')}}" required>
                                                                <label>{{translate('mid_sub_title_3')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="mid_sub_description_3"
                                                                       value="{{$data_values->where('key_name','mid_sub_description_3')->first()->live_values??''}}"
                                                                       placeholder="{{translate('mid_sub_description_3')}}" required>
                                                                <label>{{translate('mid_sub_description_3')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="download_section_title"
                                                                       value="{{$data_values->where('key_name','download_section_title')->first()->live_values??''}}"
                                                                       placeholder="{{translate('download_section_title')}}" required>
                                                                <label>{{translate('download_section_title')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="download_section_description"
                                                                       value="{{$data_values->where('key_name','download_section_description')->first()->live_values??''}}"
                                                                       placeholder="{{translate('download_section_description')}}" required>
                                                                <label>{{translate('download_section_description')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="web_bottom_title"
                                                                       value="{{$data_values->where('key_name','web_bottom_title')->first()->live_values??''}}"
                                                                       placeholder="{{translate('bottom_title')}}" required>
                                                                <label>{{translate('bottom_title')}} *</label>
                                                            </div>
                                                        </div>

                                                        <div class="mb-30">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="testimonial_title"
                                                                       value="{{$data_values->where('key_name','testimonial_title')->first()->live_values??''}}"
                                                                       placeholder="{{translate('testimonial_title')}}" required>
                                                                <label>{{translate('testimonial_title')}} *</label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="reset" class="btn btn-secondary">
                                                    {{translate('reset')}}
                                                </button>
                                                <button type="submit" class="btn btn--primary">
                                                    {{translate('update')}}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($web_page=='web_app_image')
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page=='web_app_image'?'active show':''}}">
                                <div class="card">
                                    <div class="card-body p-30">
                                        <div class="discount-type">
                                            <div class="row">
                                                @php($keys = ['support_section_image', 'download_section_image', 'feature_section_image'])
                                                @php($ratios = ['200x242', '500x500', '500x500'])
                                                @foreach($keys as $index=>$key)
                                                    <div class="col-md-4 mb-30">
                                                        <form
                                                            action="{{route('admin.business-settings.set-landing-information')}}?web_page={{$web_page}}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div
                                                                class="mb-1 d-flex flex-column align-items-center gap-2">
                                                                <p class="title-color text-center" style="width: 160px">
                                                                    {{translate($key)}}, <small class="opacity-75">{{translate('size')}}: {{$ratios[$index]}}</small>
                                                                </p>
                                                                <div class="upload-file max-w-100">
                                                                    <input type="file" class="upload-file__input"
                                                                           name="{{$key}}" id="image-{{$key}}">
                                                                    <div class="upload-file__img">
                                                                        <img
                                                                            onerror="this.src='{{asset('public/assets/admin-module/img/media/upload-file.png')}}'"
                                                                            src='{{asset('storage/app/public/landing-page/web')}}/{{$data_values->where('key_name',$key)->first()->live_values??''}}'
                                                                            alt="">
                                                                    </div>
                                                                    <span class="upload-file__edit"
                                                                          onclick="$('#image-{{$key}}').click()">
                                                                        <span class="material-icons">edit</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex gap-2 justify-content-center">
                                                                <button type="submit"
                                                                        class="btn btn--primary btn-block">
                                                                    {{translate('upload')}}
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
        $('#landing-info-update-form').on('submit', function (event) {
            event.preventDefault();

            var form = $('#landing-info-update-form')[0];
            var formData = new FormData(form);
            // Set header if need any otherwise remove setup part
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.set-landing-information')}}?web_page={{$web_page}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (response) {
                    console.log(response)
                    if (response.errors.length > 0) {
                        response.errors.forEach((value, key) => {
                            toastr.error(value.message);
                        });
                    } else {
                        toastr.success('{{translate('successfully_updated')}}');
                    }
                },
                error: function (jqXHR, exception) {
                    toastr.error(jqXHR.responseJSON.message);
                }
            });
        });
    </script>
@endpush
