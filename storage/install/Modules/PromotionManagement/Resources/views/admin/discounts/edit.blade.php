@extends('adminmodule::layouts.master')

@section('title',translate('update_discount'))

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('update_discount')}}</h2>
                    </div>

                    <div class="card mb-30">
                        <div class="card-body p-30">
                            <form action="{{route('admin.discount.update',[$discount->id])}}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="discount-type">
                                    <div class="mb-3">{{translate('discount_type')}}</div>
                                    <div class="d-flex flex-wrap align-items-center gap-4 mb-30">
                                        <div class="custom-radio">
                                            <input type="radio" id="category" name="discount_type"
                                                   value="category" {{$discount->discount_type=='category'?'checked':''}}>
                                            <label for="category">{{translate('category_wise')}}</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input type="radio" id="service" name="discount_type" value="service"
                                                {{$discount->discount_type=='service'?'checked':''}}>
                                            <label for="service">{{translate('service_wise')}}</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input type="radio" id="mixed" name="discount_type"
                                                   value="mixed" {{$discount->discount_type=='mixed'?'checked':''}}>
                                            <label for="mixed">{{translate('mixed')}}</label>
                                        </div>
                                    </div>

                                    <div class="mb-30">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" name="discount_title"
                                                   placeholder="{{translate('discount_title')}} *" value="{{$discount->discount_title}}"
                                                   required="">
                                            <label>{{translate('discount_title')}} *</label>
                                        </div>
                                    </div>
                                    <div class="mb-30" id="category_selector"
                                         style="display: {{($discount->discount_type=='category' || $discount->discount_type=='mixed')?'block':'none'}}">
                                        <select class="category-select theme-input-style w-100" name="category_ids[]"
                                                multiple="multiple" id="category_selector__select" {{($discount->discount_type=='category' || $discount->discount_type=='mixed')?'required':''}}>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}" {{in_array($category->id,$discount->category_types->pluck('type_wise_id')->toArray())?'selected':''}}>
                                                    {{$category->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-30" id="service_selector"
                                         style="display: {{($discount->discount_type=='service' || $discount->discount_type=='mixed')?'block':'none'}}">
                                        <select class="service-select theme-input-style w-100" name="service_ids[]"
                                                multiple="multiple" id="service_selector__select">
                                            @foreach($services as $service)
                                                <option value="{{$service->id}}" {{in_array($service->id,$discount->service_types->pluck('type_wise_id')->toArray())?'selected':''}}>
                                                    {{$service->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-30">
                                        <select class="zone-select theme-input-style w-100" name="zone_ids[]"
                                                multiple="multiple" required>
                                            @foreach($zones as $zone)
                                                <option value="{{$zone->id}}" {{in_array($zone->id,$discount->zone_types->pluck('type_wise_id')->toArray())?'selected':''}}>
                                                    {{$zone->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="discount-amount-type">
                                    <div class="mb-3 text-capitalize">{{translate('discount_amount_type')}}</div>
                                    <div class="d-flex align-items-center gap-4 mb-30">
                                        <div class="custom-radio">
                                            <input type="radio" id="percentage" name="discount_amount_type" value="percent" {{$discount->discount_amount_type=='percent'?'checked':''}}>
                                            <label for="percentage">{{translate('percentage')}}</label>
                                        </div>
                                        <div class="custom-radio">
                                            <input type="radio" id="fixed_amount" name="discount_amount_type" value="amount" {{$discount->discount_amount_type=='amount'?'checked':''}}>
                                            <label for="fixed_amount">{{translate('fixed_amount')}}</label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-30">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" name="discount_amount" id="discount_amount"
                                                           value="{{$discount->discount_amount}}"
                                                           placeholder="{{translate('amount')}}" step="any"
                                                           min="0" {{$discount->discount_amount_type == 'percent'? 'max=100' : ''}}>
                                                    <label id="discount_amount__label">{{translate('amount')}} ({{$discount->discount_amount_type == 'amount' ? currency_symbol() : '%'}})</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-30">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control" name="start_date" value="{{$discount->start_date}}">
                                                    <label>{{translate('start_date')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-30">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control" name="end_date" value="{{$discount->end_date}}">
                                                    <label>{{translate('end_date')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-30">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" step="any"
                                                           name="min_purchase"
                                                           placeholder="{{translate('min_purchase')}} ({{currency_symbol()}}) *"
                                                           value="{{$discount->min_purchase}}"
                                                           min="0">
                                                    <label>{{translate('min_purchase_amount')}} ({{currency_symbol()}}) *</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4" id="max_discount_amount"
                                             style="display: {{$discount->discount_amount_type=='amount'?'none':'block'}}">
                                            <div class="mb-30">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" step="any"
                                                           name="max_discount_amount"
                                                           placeholder="{{translate('max_discount')}} ({{currency_symbol()}}) *"
                                                           value="{{$discount->max_discount_amount}}"
                                                           min="0">
                                                    <label>{{translate('max_discount')}} ({{currency_symbol()}}) *</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
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
