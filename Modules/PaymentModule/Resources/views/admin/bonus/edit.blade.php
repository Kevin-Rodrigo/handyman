@extends('adminmodule::layouts.master')

@section('title',translate('Update Wallet Bonus'))

@push('css_or_js')

@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-wrap mb-3">
                    <h2 class="page-title">{{translate('Update Wallet Bonus')}}</h2>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.bonus.update',[$bonus->id])}}" method="post">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-floating mb-30">
                                        <input type="text" class="form-control" name="bonus_title"
                                                placeholder="{{translate('bonus_title')}}"
                                                value="{{$bonus->bonus_title}}" required>
                                        <label>{{translate('bonus_title')}}</label>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-floating mb-30">
                                        <input type="text" class="form-control" name="short_description"
                                                placeholder="{{translate('short_description')}}"
                                                value="{{$bonus->short_description}}" required>
                                        <label>{{translate('short_description')}}</label>
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-30">
                                    <select class="select-amount theme-input-style" id="amount_type" name="bonus_amount_type" required>
                                        <option value="percent" {{$bonus->bonus_amount_type == 'percent' ? 'selected' : ''}}>{{translate('percentage')}}</option>
                                        <option value="amount" {{$bonus->bonus_amount_type == 'amount' ? 'selected' : ''}}>{{translate('fixed_amount')}}</option>
                                    </select>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-floating mb-30">
                                        <input class="form-control" name="bonus_amount" id="amount"
                                                placeholder="Ex: 50%" step="any" min="0"
                                                value="{{$bonus->bonus_amount}}" type="number" required>
                                                <label id="amount_label">{{$bonus->bonus_amount_type == 'percent' ? 'Bonus ('. '%)' : "Bonus". ' ('. (currency_symbol(). ')')}}</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-floating mb-30">
                                        <input class="form-control" name="minimum_add_amount"
                                                placeholder="{{translate('minimum_add_amount')}}"
                                                value="{{$bonus->minimum_add_amount}}" type="number" step="any" required>
                                        <label>{{translate('minimum_add_amount')}}</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-floating mb-30">
                                        <input class="form-control" name="maximum_bonus_amount"
                                                placeholder="{{translate('maximum_bonus_amount')}}"
                                                value="{{$bonus->maximum_bonus_amount}}" min="0" type="number" step="any"
                                                id="max_amount" required {{$bonus->bonus_amount_type == 'amount' ? 'disabled' : ''}}>
                                        <label>{{translate('maximum_bonus_amount')}}</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-30">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" name="start_date"
                                                   value="{{$bonus->start_date}}" id="start_date">
                                            <label>{{translate('Start_Date')}}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-30">
                                        <div class="form-floating">
                                            <input type="date" class="form-control" name="end_date"
                                                   value="{{$bonus->end_date}}"
                                                   id="end_date">
                                            <label>{{translate('End_Date')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-4 flex-wrap justify-content-end">
                                <button type="reset" class="btn btn--secondary">{{translate('Reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('Update')}}</button>
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
    $(document).ready(function () {
        $('.select-amount').select2({

        });

        const amountType = $('#amount_type');
        amountType.on('change', function() {
            if(amountType.val() == 'amount') {
                $("#amount_label").text("Bonus ({{currency_symbol()}})");
                $('#max_amount').prop("disabled", true);
                $('#max_amount').val(0);
            }
            else {
                $("#amount_label").text("Bonus (%)")
                $('#max_amount').removeAttr("disabled");
            }
        });

        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const today = new Date();
        const formattedToday = today.toISOString().split('T')[0];

        // Set the min attribute of the input field to today's date
        startInput.setAttribute('min', formattedToday);
        endInput.setAttribute('min', formattedToday);


    });
</script>
@endpush
