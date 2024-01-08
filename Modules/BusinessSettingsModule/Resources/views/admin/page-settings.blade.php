@extends('adminmodule::layouts.master')

@section('title',translate('page_setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('page_setup')}}</h2>
                    </div>

                    <!-- Nav Tabs -->
                    <div class="mb-3">
                        <ul class="nav nav--tabs nav--tabs__style2">
                            @foreach($data_values as $page_data)
                                <li class="nav-item">
                                    <a href="{{url()->current()}}?web_page={{$page_data->key_name}}"
                                       class="nav-link {{$web_page==$page_data->key_name?'active':''}}">
                                        {{translate($page_data->key_name)}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- End Nav Tabs -->

                    <!-- Tab Content -->
                    @foreach($data_values as $page_data)
                        <div class="tab-content">
                            <div class="tab-pane fade {{$web_page==$page_data->key_name?'active show':''}}">
                                <div class="card">
                                    <form action="{{route('admin.business-settings.set-pages-setup')}}" method="POST">
                                        @csrf
                                        <div class="card-header" style="display: flex; justify-content: space-between">
                                            <h4 class="page-title">{{translate($page_data->key_name)}}</h4>
                                            @if(!in_array($page_data->key_name,['about_us','privacy_policy', 'terms_and_conditions']))
                                                <label class="switcher">
                                                    <input class="switcher_input"
                                                           onclick="$(this).submit()"
                                                           type="checkbox"
                                                           name="is_active"
                                                           {{$page_data->is_active?'checked':''}} value="1">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            @else
                                                <input name="is_active" value="1" class="hide-div">
                                            @endif
                                        </div>
                                        <div class="card-body p-30">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-30 dark-support">
                                                        <input name="page_name" value="{{$page_data->key_name}}"
                                                               class="hide-div">
                                                        <textarea class="ckeditor"
                                                                  name="page_content">{!! $page_data->live_values !!}</textarea>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn--primary demo_check">
                                                        {{translate('update')}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                @endforeach
                <!-- End Tab Content -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    {{--<script src="{{asset('public/assets/ckeditor/ckeditor.js')}}"></script>--}}
    <script src="{{asset('public/assets/ckeditor/jquery.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('textarea.ckeditor').each(function () {
                CKEDITOR.replace($(this).attr('id'));
            });
        });
    </script>
@endpush
