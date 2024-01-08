@extends('adminmodule::layouts.master')

@section('title',translate('service_update'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/select.dataTables.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/wysiwyg-editor/froala_editor.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/css/tags-input.min.css"/>
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('update_service')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body p-30">
                            <form action="{{route('admin.service.update',[$service->id])}}" method="post"
                                  enctype="multipart/form-data"
                                  id="service-add-form">
                                @csrf
                                @method('PUT')
                                <div id="form-wizard">
                                    <h3>{{translate('service_information')}}</h3>
                                    <section>
                                        <div class="row">
                                            <div class="col-lg-5 mb-5 mb-lg-0">
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="name"
                                                               placeholder="{{translate('service_name')}} *"
                                                               required="" value="{{$service->name}}">
                                                        <label>{{translate('service_name')}} *</label>
                                                    </div>
                                                </div>
                                                <div class="mb-30">
                                                    <select class="js-select theme-input-style w-100" name="category_id"
                                                            onchange="ajax_switch_category('{{url('/')}}/admin/category/ajax-childes/'+this.value)">
                                                        <option value="0" selected disabled>{{translate('choose_category')}}</option>
                                                        @foreach($categories as $category)
                                                            <option
                                                                value="{{$category->id}}" {{$category->id==$service->category_id?'selected':''}}>
                                                                {{$category->name}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-30" id="sub-category-selector">
                                                    <select class="js-select theme-input-style w-100"
                                                            name="sub_category_id"></select>
                                                </div>

                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="tax" min="0"
                                                               max="100" step="0.01"
                                                               placeholder="{{translate('add_tax_percentage')}} *"
                                                               required="" value="{{$service->tax}}">
                                                        <label>{{translate('add_tax_percentage')}} *</label>
                                                    </div>
                                                </div>

                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="number" class="form-control" name="min_bidding_price" min="0"
                                                               max="100" step="any"
                                                               placeholder="{{translate('min_bidding_price')}} *"
                                                               required="" value="{{$service->min_bidding_price}}">
                                                        <label>{{translate('min_bidding_price')}} *</label>
                                                    </div>
                                                </div>

                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="tags" placeholder="{{translate('Enter tags')}}" value="{{implode(",",$tag_names)}}" data-role="tagsinput">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-5 mb-5 mb-sm-0">
                                                <div class="d-flex flex-column align-items-center gap-3">
                                                    <p class="mb-0">{{translate('thumbnail_image')}}</p>
                                                    <div>
                                                        <div class="upload-file">
                                                            <input type="file" class="upload-file__input"
                                                                   name="thumbnail">
                                                            <div class="upload-file__img">
                                                                <img
                                                                    onerror="this.src='{{asset('public/assets/admin-module')}}/img/media/upload-file.png'"
                                                                    src="{{asset('storage/app/public/service')}}/{{$service->thumbnail}}"
                                                                    alt="">
                                                            </div>
                                                            <span class="upload-file__edit">
                                                                <span class="material-icons">edit</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p class="opacity-75 max-w220 mx-auto">{{translate('Image format - jpg, png,
                                                        jpeg,
                                                        gif Image
                                                        Size -
                                                        maximum size 2 MB Image Ratio - 1:1')}}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-7">
                                                <div class="d-flex flex-column align-items-center gap-3">
                                                    <p class="mb-0">{{translate('cover_image')}}</p>
                                                    <div>
                                                        <div class="upload-file">
                                                            <input type="file" class="upload-file__input"
                                                                   name="cover_image">
                                                            <div class="upload-file__img upload-file__img_banner">
                                                                <img
                                                                    onerror="this.src='{{asset('public/assets/admin-module')}}/img/media/banner-upload-file.png'"
                                                                    src="{{asset('storage/app/public/service')}}/{{$service->cover_image}}"
                                                                    alt="">
                                                            </div>
                                                            <span class="upload-file__edit">
                                                                <span class="material-icons">edit</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p class="opacity-75 max-w220 mx-auto">{{translate('Image format - jpg, png,
                                                        jpeg, gif Image Size - maximum size 2 MB Image Ratio - 3:1')}}</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mt-5">
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <textarea type="text" class="form-control"
                                                                  name="short_description">{{$service->short_description}}</textarea>
                                                        <label>{{translate('short_description')}} *</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-4 mt-md-5">
                                                <label for="editor" class="mb-2">{{translate('long_Description')}} <span class="text-danger">*</span></label>
                                                <section id="editor" class="dark-support">
                                                    <textarea class="ckeditor"
                                                              name="description">{{$service->description}}</textarea>
                                                </section>
                                            </div>
                                        </div>
                                    </section>

                                    <h3>{{translate('price_variation')}}</h3>
                                    <section>
                                        <div class="d-flex flex-wrap gap-20 mb-3">
                                            <div class="form-floating flex-grow-1">
                                                <input type="text" class="form-control" name="variant_name"
                                                       id="variant-name"
                                                       placeholder="{{translate('add_variant')}} *" required="">
                                                <label>{{translate('add_variant')}} *</label>
                                            </div>
                                            <div class="form-floating flex-grow-1">
                                                <input type="number" class="form-control" name="variant_price"
                                                       id="variant-price"
                                                       placeholder="{{translate('price')}} *" required="" value="0">
                                                <label>{{translate('price')}} *</label>
                                            </div>
                                            <button type="button" class="btn btn--primary"
                                                    onclick="ajax_variation('{{route('admin.service.ajax-add-variant')}}','variation-table')">
                                                <span class="material-icons">add</span>
                                                {{translate('add')}}
                                            </button>
                                        </div>

                                        <div class="table-responsive p-01">
                                            <table class="table align-middle table-variation">
                                                <thead id="category-wise-zone" class="text-nowrap">
                                                    <tr>
                                                        <th scope="col">{{translate('variations')}}</th>
                                                        <th scope="col">{{translate('default_price')}}</th>
                                                        @foreach($zones as $zone)
                                                            <th scope="col">{{$zone->name}}</th>
                                                        @endforeach
                                                        <th scope="col">{{translate('action')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="variation-update-table">
                                                    @include('servicemanagement::admin.partials._update-variant-data',['variants'=>$service->variations,'zones'=>$zones])
                                                </tbody>
                                            </table>

                                            <div id="new-variations-table"
                                                 class="{{session()->has('variations') && count(session('variations'))>0?'':'hide-div'}}">
                                                <label
                                                    class="badge badge-primary mb-10">{{translate('new_variations')}}</label>
                                                <table class="table align-middle table-variation">
                                                    <tbody id="variation-table">
                                                        @include('servicemanagement::admin.partials._variant-data',['zones'=>$zones])
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </section>
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
    <script src="{{asset('public/assets/admin-module')}}/js//tags-input.min.js"></script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.js-select').select2();
        });
    </script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/jquery-steps/jquery.steps.min.js"></script>
    <script>
        $("#form-wizard").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            autoFocus: true,
            onFinished: function (event, currentIndex) {
                $("#service-add-form")[0].submit();
            }
        });
    </script>

    <script>
        ajax_get('{{url('/')}}/admin/category/ajax-childes-only/{{$service->category_id}}?sub_category_id={{$service->sub_category_id}}', 'sub-category-selector')

        function ajax_variation(route, id) {

            let name = $('#variant-name').val();
            let price = $('#variant-price').val();

            if (name.length > 0 && price >= 0) {
                $.get({
                    url: route,
                    dataType: 'json',
                    data: {
                        name: $('#variant-name').val(),
                        price: $('#variant-price').val(),
                    },
                    beforeSend: function () {
                        /*$('#loading').show();*/
                    },
                    success: function (response) {
                        console.log(response.template)
                        if (response.flag == 0) {
                            toastr.info('Already added');
                        } else {
                            $('#new-variations-table').show();
                            $('#' + id).html(response.template);
                            $('#variant-name').val("");
                            $('#variant-price').val(0);
                        }
                    },
                    complete: function () {
                        /*$('#loading').hide();*/
                    },
                });
            } else {
                toastr.warning('{{translate('fields_are_required')}}');
            }
        }

        function ajax_remove_variant(route, id) {
            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: "{{translate('want_to_remove_this_variation')}}",
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
                    $.get({
                        url: route,
                        dataType: 'json',
                        data: {},
                        beforeSend: function () {
                            /*$('#loading').show();*/
                        },
                        success: function (response) {
                            console.log(response.template)
                            $('#' + id).html(response.template);
                        },
                        complete: function () {
                            /*$('#loading').hide();*/
                        },
                    });
                }
            })
        }

        function ajax_switch_category(route) {
            $.get({
                url: route+'?service_id={{$service->id}}',
                dataType: 'json',
                data: {},
                beforeSend: function () {
                    /*$('#loading').show();*/
                },
                success: function (response) {
                    console.log(response);
                    $('#sub-category-selector').html(response.template);
                    $('#category-wise-zone').html(response.template_for_zone);
                    $('#variation-table').html(response.template_for_variant);
                    $('#variation-update-table').html(response.template_for_update_variant);
                },
                complete: function () {
                    /*$('#loading').hide();*/
                },
            });
        }
    </script>

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
