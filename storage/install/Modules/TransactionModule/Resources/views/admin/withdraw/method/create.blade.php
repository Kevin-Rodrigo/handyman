@extends('adminmodule::layouts.master')

@section('title',translate('withdrawal_method'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div
                        class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                        <h2 class="page-title">{{translate('Withdrawal_Methods')}}</h2>
                        <button class="btn btn--primary" id="add-more-field">
                            <span class="material-icons">add</span> {{translate('Add_fields')}}
                        </button>
                    </div>

                    <div class="">
                        <form action="{{route('admin.withdraw.method.store')}}" method="POST">
                            @csrf
                            <div class="card card-body">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="method_name" id="method_name"
                                           placeholder="Select method name" value="" required>
                                    <label>{{translate('method_name')}} *</label>
                                </div>
                            </div>

                            <div class="mt-3">
                                <!-- HERE CUSTOM FIELDS WILL BE ADDED -->
                                <div id="custom-field-section">
                                    <div class="card card-body">
                                        <div class="row gy-4 align-items-center">
                                            <div class="col-md-6 col-12">
                                                <select class="form-control js-select" name="field_type[]" required>
                                                    <option value="" selected disabled>{{translate('Input Field Type')}} *</option>
                                                    <option value="string">{{translate('String')}}</option>
                                                    <option value="number">{{translate('Number')}}</option>
                                                    <option value="date">{{translate('Date')}}</option>
                                                    <option value="password">{{translate('Password')}}</option>
                                                    <option value="email">{{translate('Email')}}</option>
                                                    <option value="phone">{{translate('Phone')}}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="field_name[]"
                                                        placeholder="Select field name" value="" required>
                                                    <label>{{translate('field_name')}} *</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="placeholder_text[]"
                                                        placeholder="Select placeholder text" value="" required>
                                                    <label>{{translate('placeholder_text')}} *</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1" name="is_required[0]" id="flexCheckDefault__0" checked>
                                                    <label class="form-check-label" for="flexCheckDefault__0">
                                                        {{translate('This_field_required')}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex my-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="is_default" id="flexCheckDefaultMethod">
                                        <label class="form-check-label" for="flexCheckDefaultMethod">
                                            {{translate('default_method')}}
                                        </label>
                                    </div>
                                </div>

                                <!-- BUTTON -->
                                <div class="d-flex justify-content-end">
                                    <button type="reset" class="btn btn--secondary mx-2">{{translate('Reset')}}</button>
                                    <button type="submit" class="btn btn--primary demo_check">{{translate('Submit')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->


@endsection

@push('script')
    <script>
        function remove_field(fieldRowId) {
            $( `#field-row--${fieldRowId}` ).remove();
            counter--;
        }

        jQuery(document).ready(function ($) {
            counter = 1;

            $('#add-more-field').on('click', function (event) {
                if(counter < 15) {
                    event.preventDefault();

                    $('#custom-field-section').append(
                        `<div class="card card-body mt-3" id="field-row--${counter}">
                            <div class="row gy-4 align-items-center">
                                <div class="col-md-6 col-12">
                                    <select class="form-control js-select" name="field_type[]" required>
                                        <option value="" selected disabled>{{translate('Input Field Type')}} *</option>
                                        <option value="string">{{translate('String')}}</option>
                                        <option value="number">{{translate('Number')}}</option>
                                        <option value="date">{{translate('Date')}}</option>
                                        <option value="password">{{translate('Password')}}</option>
                                        <option value="email">{{translate('Email')}}</option>
                                        <option value="phone">{{translate('Phone')}}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="field_name[${counter}]"
                                               placeholder="Select field name" value="" required>
                                        <label>{{translate('field_name')}} *</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="placeholder_text[${counter}]"
                                               placeholder="Select placeholder text" value="" required>
                                        <label>{{translate('placeholder_text')}} *</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="is_required[${counter}]" id="flexCheckDefault__${counter}" checked>
                                        <label class="form-check-label" for="flexCheckDefault__${counter}">
                                            {{translate('This_field_required')}}
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <span class="btn btn--danger" onclick="remove_field(${counter})">
                                        <span class="material-icons">delete</span>
                                            {{translate('Remove')}}
                                    </span>
                                </div>
                            </div>
                        </div>`
                    );

                    $(".js-select").select2();

                    counter++;
                } else {
                    Swal.fire({
                        title: '{{translate('Reached maximum')}}',
                        confirmButtonText: '{{translate('ok')}}',
                    });
                }
            })

            $('form').on('reset', function (event) {
                if(counter > 1) {
                    $('#custom-field-section').html("");
                    $('#method_name').val("");
                }

                counter = 1;
            })
        });
    </script>


@endpush
