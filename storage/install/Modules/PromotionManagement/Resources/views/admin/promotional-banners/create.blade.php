@extends('adminmodule::layouts.master')

@section('title',translate('promotional_banners'))

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
                        <h2 class="page-title">{{translate('promotional_banners')}}</h2>
                    </div>

                    <!-- Promotional Banner -->
                    <div class="card mb-30">
                        <div class="card-body p-30">
                            <form action="{{route('admin.banner.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 mb-4 mb-lg-0">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="banner_title"
                                                   placeholder="{{translate('title')}} *"
                                                   required="">
                                            <label>{{translate('title')}} *</label>
                                        </div>

                                        <div class="mb-3">{{translate('resource_type')}}</div>
                                        <div class="d-flex flex-wrap align-items-center gap-4 mb-30">
                                            <div class="custom-radio">
                                                <input type="radio" id="category" name="resource_type" value="category"
                                                       checked>
                                                <label for="category">{{translate('category_wise')}}</label>
                                            </div>
                                            <div class="custom-radio">
                                                <input type="radio" id="service" name="resource_type" value="service">
                                                <label for="service">{{translate('service_wise')}}</label>
                                            </div>
                                            <div class="custom-radio">
                                                <input type="radio" id="redirect_link" name="resource_type"
                                                       value="link">
                                                <label for="redirect_link">{{translate('redirect_link')}}</label>
                                            </div>
                                        </div>

                                        <div class="mb-30" id="category_selector">
                                            <select class="js-select theme-input-style w-100" name="category_id">
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-30" id="service_selector" style="display: none">
                                            <select class="js-select theme-input-style w-100" name="service_id">
                                                @foreach($services as $service)
                                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-floating mb-30" style="display: none" id="link_selector">
                                            <input type="url" class="form-control" placeholder="{{translate('redirect_link')}}" name="redirect_link">
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
                                                            src="{{asset('public/assets/admin-module')}}/img/media/banner-upload-file.png"
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
                                                    type="submit">{{translate('submit')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End Promotional Banner -->

                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                        <ul class="nav nav--tabs">
                            <li class="nav-item">
                                <a class="nav-link {{$resource_type=='all'?'active':''}}"
                                   href="{{url()->current()}}?resource_type=all">
                                    {{translate('all')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$resource_type=='category'?'active':''}}"
                                   href="{{url()->current()}}?resource_type=category">
                                    {{translate('category_wise')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$resource_type=='service'?'active':''}}"
                                   href="{{url()->current()}}?resource_type=service">
                                    {{translate('service_wise')}}
                                </a>
                            </li>
                        </ul>

                        <div class="d-flex gap-2 fw-medium">
                            <span class="opacity-75">{{translate('Total_Banners')}}:</span>
                            <span class="title-color">{{$banners->total()}}</span>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="all-tab-pane">
                            <div class="card">
                                <div class="card-body">
                                    <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                        <form action="{{url()->current()}}?resource_type={{$resource_type}}"
                                              class="search-form search-form_style-two"
                                              method="POST">
                                            @csrf
                                            <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                                <input type="search" class="theme-input-style search-form__input"
                                                       value="{{$search}}" name="search"
                                                       placeholder="{{translate('search_here')}}">
                                            </div>
                                            <button type="submit"
                                                    class="btn btn--primary">{{translate('search')}}</button>
                                        </form>

                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <div class="dropdown">
                                                <button type="button"
                                                        class="btn btn--secondary text-capitalize dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                    <span
                                                        class="material-icons">file_download</span> {{translate('download')}}
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                    <a class="dropdown-item" href="{{route('admin.banner.download')}}?search={{$search}}">
                                                        {{translate('excel')}}
                                                    </a>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="example" class="table align-middle">
                                            <thead>
                                            <tr>
                                                <th>{{translate('title')}}</th>
                                                <th>{{translate('type')}}</th>
                                                <th>{{translate('status')}}</th>
                                                <th>{{translate('action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($banners as $item)
                                                <tr>
                                                    <td>{{$item->banner_title}}</td>
                                                    <td>{{$item->resource_type}}</td>
                                                    <td>
                                                        <label class="switcher">
                                                            <input class="switcher_input" onclick="route_alert('{{route('admin.banner.status-update',[$item->id])}}','{{translate('want_to_update_status')}}')"
                                                                   type="checkbox" {{$item->is_active?'checked':''}}>
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="table-actions">
                                                            <a href="{{route('admin.banner.edit',[$item->id])}}"
                                                               class="table-actions_edit">
                                                                <span class="material-icons">edit</span>
                                                            </a>
                                                            <button type="button"
                                                                    onclick="form_alert('delete-{{$item->id}}','{{translate('want_to_delete_this')}}?')"
                                                                    class="table-actions_delete bg-transparent border-0 p-0">
                                                                <span class="material-icons">delete</span>
                                                            </button>
                                                            <form action="{{route('admin.banner.delete',[$item->id])}}"
                                                                  method="post" id="delete-{{$item->id}}" class="hidden">
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
                                    <div class="d-flex justify-content-end">
                                        {!! $banners->links() !!}
                                    </div>
                                </div>
                            </div>
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
