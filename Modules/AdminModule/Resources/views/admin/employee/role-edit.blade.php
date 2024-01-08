@extends('adminmodule::layouts.master')

@section('title',translate('role_settings'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.css"/>
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('role_settings')}}</h2>
                    </div>

                    <!-- Promotional Banner -->
                    <div class="card mb-30">
                        <div class="card-body p-30">
                            <form action="{{route('admin.role.update',[$role->id])}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-lg-12 mb-4 mb-lg-0">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="role_name"
                                                   placeholder="{{translate('role_name')}} *"
                                                   required="" value="{{$role->role_name}}">
                                            <label>{{translate('role_name')}} *</label>
                                        </div>

                                        <div class="mb-3 fw-bold">{{translate('modules')}}</div>
                                        <div class="grid-column-4 mb-30">
                                            @foreach(SYSTEM_MODULES as $key=>$module)
                                                <div class="custom-radio">
                                                    <input type="checkbox" id="{{$module['key']}}" name="modules[]"
                                                           value="{{$module['key']}}" {{in_array($module['key'],$role->modules)?'checked':''}}>
                                                    <label for="{{$module['key']}}">{{$module['value']}}</label>
                                                </div>
                                            @endforeach
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
@endpush
