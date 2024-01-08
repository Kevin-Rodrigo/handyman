@extends('adminmodule::layouts.master')

@section('title',translate('add_new_campaign'))

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('add_new_campaign')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body p-30">
                            <form action="{{route('admin.campaign.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="discount-type">

                                    <div class="row">
                                        <div class="col-lg-6 mb-30">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="campaign_name"
                                                       placeholder="{{translate('campaign_name')}} *"
                                                       required="">
                                                <label>{{translate('campaign_name')}} *</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-30">
                                                <div class="d-flex flex-wrap justify-content-between gap-3">
                                                    <div>
                                                        <p class="title-color">{{translate('upload_cover_image')}}</p>
                                                        <p class="opacity-75 max-w220">{{translate('Image format - jpg,
                                                            png, jpeg, gif Image Size - maximum size 2 MB Image
                                                            Ratio - 2:1')}}</p>
                                                    </div>
                                                    <div>
                                                        <div class="upload-file">
                                                            <input type="file" class="upload-file__input" name="cover_image" required>
                                                            <div class="upload-file__img upload-file__img_banner">
                                                                <img src="{{asset('public/assets/admin-module')}}/img/media/banner-upload-file.png"
                                                                     alt="">
                                                            </div>
                                                            <span class="upload-file__edit">
                                                                <span class="material-icons">edit</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-30">
                                                <div class="d-flex flex-wrap justify-content-between gap-3">
                                                    <div>
                                                        <p class="title-color">{{translate('upload_thumbnail')}}</p>
                                                        <p class="opacity-75 max-w220">{{translate('Image format - jpg, png,
                                                            jpeg, gif Image Size - maximum size 2 MB Image Ratio -
                                                            1:1')}}</p>
                                                    </div>
                                                    <div>
                                                        <div class="upload-file">
                                                            <input type="file" class="upload-file__input" name="thumbnail" required>
                                                            <div class="upload-file__img">
                                                                <img src="{{asset('public/assets/admin-module')}}/img/media/upload-file.png" alt="">
                                                            </div>
                                                            <span class="upload-file__edit">
                                                                <span class="material-icons">edit</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">{{translate('discount_type')}}</div>
                                    <div class="d-flex flex-wrap align-items-center gap-4 mb-30">
                                        <div class="custom-radio">
                                            <input type="radio" id="category" name="discount_type" value="category"
                                                   checked>
                                            <label for="category">{{translate('category_wise')}}</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input type="radio" id="service" name="discount_type" value="service">
                                            <label for="service">{{translate('service_wise')}}</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input type="radio" id="mixed" name="discount_type" value="mixed">
                                            <label for="mixed">{{translate('mixed')}}</label>
                                        </div>
                                    </div>

                                    <div class="mb-30">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="discount_title"
                                                   placeholder="{{translate('discount_title')}} *"
                                                   required="">
                                            <label>{{translate('discount_title')}} *</label>
                                        </div>
                                    </div>
                                    <div class="mb-30" id="category_selector">
                                        <select class="category-select theme-input-style w-100" name="category_ids[]"
                                                multiple="multiple" id="category_selector__select" required>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-30" id="service_selector" style="display: none">
                                        <select class="service-select theme-input-style w-100" name="service_ids[]"
                                                multiple="multiple" id="service_selector__select">
                                            @foreach($services as $service)
                                                <option value="{{$service->id}}">{{$service->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-30">
                                        <select class="zone-select theme-input-style w-100" name="zone_ids[]"
                                                multiple="multiple" required>
                                            @foreach($zones as $zone)
                                                <option value="{{$zone->id}}">{{$zone->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="discount-amount-type">
                                    <div class="mb-3">{{translate('discount_amount_type')}}</div>
                                    <div class="d-flex flex-wrap align-items-center gap-4 mb-30">
                                        <div class="custom-radio">
                                            <input type="radio" id="percentage" name="discount_amount_type"
                                                   value="percent" checked>
                                            <label for="percentage">{{translate('percentage')}}</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input type="radio" id="fixed_amount" name="discount_amount_type"
                                                   value="amount">
                                            <label for="fixed_amount">{{translate('fixed_amount')}}</label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-30">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" name="discount_amount"
                                                           placeholder="{{translate('amount')}}" id="discount_amount"
                                                           min="0" max="100" value="0" step="any">
                                                    <label id="discount_amount__label">{{translate('amount')}} (%)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-30">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control" name="start_date"
                                                           value="{{now()->format('Y-m-d')}}">
                                                    <label>{{translate('Start_Date')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-30">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control" name="end_date"
                                                           value="{{now()->addDays(2)->format('Y-m-d')}}">
                                                    <label>{{translate('End_Date')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-30">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" step="any"
                                                           name="min_purchase"
                                                           placeholder="{{translate('min_purchase')}} ({{currency_symbol()}}) *"
                                                           min="0" value="0">
                                                    <label>{{translate('min_purchase')}} ({{currency_symbol()}})
                                                        *</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4" id="max_discount_amount">
                                            <div class="mb-30">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" step="any"
                                                           name="max_discount_amount"
                                                           placeholder="{{translate('max_discount')}} ({{currency_symbol()}}) *"
                                                           min="0" value="0">
                                                    <label>{{translate('max_discount')}} ({{currency_symbol()}})
                                                        *</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-3">
                                    <button type="reset" class="btn btn--secondary">{{translate('reset')}}</button>
                                    <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
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
    <script>
        "use Strict"
        $('#category').on('click', function () {
            $('#category_selector').show();
            $('#service_selector').hide();

            $('#category_selector__select').prop('required',true);
            $('#service_selector__select').prop('required',false);
        });

        $('#service').on('click', function () {
            $('#category_selector').hide();
            $('#service_selector').show();

            $('#service_selector__select').prop('required',true);
            $('#category_selector__select').prop('required',false);
        });

        $('#mixed').on('click', function () {
            $('#category_selector').show();
            $('#service_selector').show();

            $('#service_selector__select').prop('required',true);
            $('#category_selector__select').prop('required',true);
        });

        $('#percentage').on('click', function () {
            $('#max_discount_amount').show();

            //Attribute Update
            $('#discount_amount').attr({"max" : 100});
            $('#discount_amount__label').html('{{translate('amount')}} (%)');
        });

        $('#fixed_amount').on('click', function () {
            $('#max_discount_amount').hide();

            //Attribute Update
            $('#discount_amount').removeAttr('max');
            $('#discount_amount__label').html('{{translate('amount')}} ({{currency_symbol()}})');
        });

        //Select 2
        $(".category-select").select2({
            placeholder: "Select Category",
        });
        $(".service-select").select2({
            placeholder: "Select Service",
        });
        $(".zone-select").select2({
            placeholder: "Select Zone",
        });
    </script>
@endpush
