@extends('adminmodule::layouts.master')

@section('title',translate('edit_offline_payment'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-4">
                        <h2 class="page-title">{{translate('payment_gateway_configuration')}}</h2>
                    </div>

                    <!-- Tab Menu -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
                        <ul class="nav nav--tabs nav--tabs__style2" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{$type=='digital_payment'?'active':''}}"
                                href="{{route('admin.configuration.payment-get')}}??type=digital_payment">{{translate('Digital Payment Gateways')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$type=='offline_payment'?'active':''}}"
                                href="{{route('admin.configuration.offline-payment.list')}}?type=offline_payment">{{translate('Offline Payment')}}</a>
                            </li>
                        </ul>
                    </div>


                    <div class="">
                        <form action="{{route('admin.configuration.offline-payment.update')}}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" value="{{$withdrawal_method['id']}}" name="id">
                            <div class="d-flex justify-content-end mb-3 mt-3">
                                <div class="d-flex gap-2 justify-content-end text-primary fw-bold" id="bkashInfoModalButton">
                                    Section View <i class="material-icons" data-bs-toggle="tooltip" title="{{translate('Admin needs to add the payment information for any offline payment, which customers will use to pay.')}}">info</i>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header d-flex align-items-center flex-wrap justify-content-between gap-2 mb-2">
                                    <h5 class="page-title">{{translate('payment_information')}}</h5>
                                    <button class="btn btn-outline--primary" id="add-more-field-payment">
                                        <span class="material-icons">add</span> {{translate('Add_new_field')}}
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-3 align-items-center">
                                        <div class="col-md-6 col-12">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="method_name" id="method_name"
                                                placeholder="Select method name" value="{{$withdrawal_method['method_name']??''}}" required>
                                                <label>{{translate('payment_method_name')}} *</label>
                                            </div>
                                        </div>

                                        <div id="custom-field-section-payment">
                                            @foreach($withdrawal_method['payment_information'] as $key=>$field)
                                            @if($key>=0)
                                                <div class="card card-body mb-30 field-row-payment" id="field-row-payment--{{$key}}">
                                                    <div class="row gy-4 align-items-center">
                                                        <div class="col-md-4">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="title[]"
                                                                    placeholder="Select field name" value="{{ $field['title'] ? str_replace('_', ' ', $field['title']) : '' }}" required>
                                                                <label>{{translate('title')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="data[]"
                                                                    placeholder="Select data" value="{{$field['data']??''}}" required>
                                                                <label>{{translate('data')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex justify-content-end">
                                                                <span class="text-danger" onclick="remove_field_payment({{$key}})"  style="cursor: pointer;">
                                                                    <span class="material-icons">delete</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <!-- HERE CUSTOM FIELDS WILL BE ADDED -->
                                <div class="d-flex justify-content-end mb-3 mt-4">
                                    <div class="d-flex gap-2 justify-content-end text-primary fw-bold" id="paymentInfoModalButton">
                                        Section View <i class="material-icons" data-bs-toggle="tooltip" title="{{translate('Admin needs to set the required customer information, which needs to be provided to the customers before placing a booking through offline payment')}}">info</i>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header d-flex align-items-center flex-wrap justify-content-between gap-2 mb-2">
                                        <h5 class="page-title">{{translate('customer_information')}}</h5>
                                        <button class="btn btn-outline--primary" id="add-more-field-customer">
                                            <span class="material-icons">add</span> {{translate('Add_new_field')}}
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-6 col-12">
                                            <div class="form-floating">
                                                <textarea class="form-control" name="payment_note" id="payment_note"
                                                    placeholder="Select Payment Note" value="" disabled></textarea>
                                                <label for="payment_note">{{translate('payment_note')}} *</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <!-- HERE CUSTOM FIELDS WILL BE ADDED -->
                                    <div id="custom-field-section-customer">
                                        @foreach($withdrawal_method['customer_information'] as $key=>$field)
                                            @if($key>=0 && $field['field_name'] != 'payment_note')
                                                <div class="card card-body mb-30 field-row-customer" id="field-row-customer--{{$key}}">
                                                    <div class="row gy-4 align-items-center">
                                                        <div class="col-md-4">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="field_name[{{$key}}]"
                                                                placeholder="Select Field Name" value="{{ $field['field_name'] ? str_replace('_', ' ', $field['field_name']) : '' }}" required>

                                                                <label>{{translate('input_field_name')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-floating">
                                                                <input type="text" class="form-control" name="placeholder[{{$key}}]"
                                                                       placeholder="Select placeholder" value="{{$field['placeholder']??''}}" required>
                                                                <label>{{translate('placeholder')}} *</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="d-flex justify-content-between gap-2 align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" value="1"
                                                                        name="is_required[{{$key}}]" id="flexCheckDefault__{{$key}}"
                                                                            {{$field['is_required'] ? 'checked' : ''}}>
                                                                    <label class="form-check-label" for="flexCheckDefault__{{$key}}">
                                                                        {{translate('This_field_required')}}
                                                                    </label>
                                                                </div>
                                                                <span class="text-danger" onclick="remove_field({{$key}})"  style="cursor: pointer;">
                                                                    <span class="material-icons">delete</span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                    @endforeach
                                    </div>
                                </div>

                                <!-- BUTTON -->
                                <div class="d-flex justify-content-end mb-3 mt-3">
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

        <!-- Section View Modal -->
        <div class="modal fade" id="sectionViewModal" tabindex="-1" aria-labelledby="sectionViewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="d-flex align-items-center flex-column gap-3 text-center">
                    <h3>{{translate('Offline Payment')}}</h3>
                    <img width="100" src="{{asset('public/assets/admin-module/img/offline_payment.png')}}" alt="">
                    <p class="text-muted">{{translate('This view is from the user app.')}} <br class="d-none d-sm-block"> {{translate('This is how customer will see in the app')}}</p>
                </div>

                <div class="rounded p-4 mt-3" id="offline_payment_top_part">
                    <div class="d-flex justify-content-between gap-2 mb-3">
                        <h4 id="payment_modal_method_name"><span></span></h4>
                        <div class="text-primary d-flex align-items-center gap-2">
                            {{translate('Pay on this account')}}
                            <span class="material-icons">check_circle</span>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2" id="methodNameDisplay">

                    </div>
                    <div class="d-flex flex-column gap-2" id="displayDataDiv">

                    </div>
                </div>

                <div class="rounded p-4 mt-3 mt-4" id="offline_payment_bottom_part">
                    <h2 class="text-center mb-4">{{translate('Amount')}} : xxx</h2>

                    <h4 class="mb-3">{{translate('Payment Info')}}</h4>
                    <div class="d-flex flex-column gap-3 mb-3" id="customer-info-display-div">

                    </div>
                    <div class="d-flex flex-column gap-3">
                        <textarea name="payment_note" id="payment_note" class="form-control"
                            readonly rows="10" placeholder="Note"></textarea>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-3 mt-3">
                    <button type="button" class="btn btn--secondary">Close</button>
                    <button type="button" class="btn btn--primary">Submit</button>
                </div>
                </div>
            </div>
            </div>
        </div>


@endsection

@push('script')
    <script>

        //section view modal
                // Update the modal class based on the argument
                function openModal(contentArgument) {
            if (contentArgument === "bkashInfo") {
                $("#sectionViewModal #offline_payment_top_part").addClass("active");
                $("#sectionViewModal #offline_payment_bottom_part").removeClass("active");

                var methodName = $('#method_name').val();

                if (methodName !== '') {
                    $('#payment_modal_method_name').text(methodName + ' ' + 'Info');
                }

                function extractPaymentData() {
                var data = [];

                    $('.field-row-payment').each(function(index) {
                        console.log('modal')
                        var title = $(this).find('input[name="title[]"]').val();
                        var dataValue = $(this).find('input[name="data[]"]').val();
                        data.push({ title: title, data: dataValue });
                    });

                    return data;
                }

                var extractedData = extractPaymentData();


                function displayPaymentData() {
                    var displayDiv = $('#displayDataDiv');
                    var methodNameDisplay = $('#methodNameDisplay');
                    methodNameDisplay.empty();
                    displayDiv.empty();

                    var paymentElement = $('<span>').text('Payment Method');
                    var payementDataElement = $('<span>').html(methodName);

                    var dataRow = $('<div>').addClass('d-flex gap-3 align-items-center mb-2');
                    dataRow.append(paymentElement).append($('<span>').text(':')).append(payementDataElement);


                    methodNameDisplay.append(dataRow);

                    extractedData.forEach(function(item) {
                        var titleElement = $('<span>').text(item.title);
                        var dataElement = $('<span>').html(item.data);

                        var dataRow = $('<div>').addClass('d-flex gap-3 align-items-center');

                        if (item.title !== '') {
                            dataRow.append(titleElement).append($('<span>').text(':')).append(dataElement);
                            displayDiv.append(dataRow);
                        }

                    });
                }
                displayPaymentData();

                //customer info
                function extractCustomerData() {
                    var data = [];

                    $('.field-row-customer').each(function(index) {
                        var fieldName = $(this).find('input[name="field_name[' + index + ']"]').val();
                        var placeholder = $(this).find('input[name="placeholder[' + index + ']"]').val();
                        var isRequired = $(this).find('input[name="is_required[' + index + ']"]').prop('checked');
                        data.push({ fieldName: fieldName, placeholder: placeholder, isRequired: isRequired });
                    });

                    return data;
                }

                var extractedCustomerData = extractCustomerData();
                $('#customer-info-display-div').empty();

                // Loop through the extracted data and populate the display div
                $.each(extractedCustomerData, function(index, item) {
                    var isRequiredAttribute = item.isRequired ? 'required' : '';
                    var displayHtml = `
                        <input type="text" class="form-control" name="payment_by_${index}" id="payment_by_${index}"
                        readonly placeholder="${item.placeholder}"  ${isRequiredAttribute}>
                    `;
                    $('#customer-info-display-div').append(displayHtml);
                });

            } else {
                $("#sectionViewModal #offline_payment_top_part").removeClass("active");
                $("#sectionViewModal #offline_payment_bottom_part").addClass("active");

                var methodName = $('#method_name').val();

                if (methodName !== '') {
                    $('#payment_modal_method_name').text(methodName + ' ' + 'Info');
                }

                // $('.payment_modal_method_name').text(methodName);

                function extractPaymentData() {
                var data = [];

                    $('.field-row-payment').each(function(index) {
                        console.log('modal')
                        var title = $(this).find('input[name="title[]"]').val();
                        var dataValue = $(this).find('input[name="data[]"]').val();
                        data.push({ title: title, data: dataValue });
                    });

                    return data;
                }

                var extractedData = extractPaymentData();


                function displayPaymentData() {
                    var displayDiv = $('#displayDataDiv');
                    var methodNameDisplay = $('#methodNameDisplay');
                    methodNameDisplay.empty();
                    displayDiv.empty();

                    var paymentElement = $('<span>').text('Payment Method');
                    var payementDataElement = $('<span>').html(methodName);

                    var dataRow = $('<div>').addClass('d-flex gap-3 align-items-center mb-2');
                    dataRow.append(paymentElement).append($('<span>').text(':')).append(payementDataElement);


                    methodNameDisplay.append(dataRow);

                    extractedData.forEach(function(item) {
                        var titleElement = $('<span>').text(item.title);
                        var dataElement = $('<span>').html(item.data);

                        var dataRow = $('<div>').addClass('d-flex gap-3 align-items-center');

                        if (item.title !== '') {
                            dataRow.append(titleElement).append($('<span>').text(':')).append(dataElement);
                            displayDiv.append(dataRow);
                        }

                    });
                }
                displayPaymentData();

                //customer info
                function extractCustomerData() {
                    var data = [];

                    $('.field-row-customer').each(function(index) {
                        var fieldName = $(this).find('input[name="field_name[' + index + ']"]').val();
                        var placeholder = $(this).find('input[name="placeholder[' + index + ']"]').val();
                        var isRequired = $(this).find('input[name="is_required[' + index + ']"]').prop('checked');
                        data.push({ fieldName: fieldName, placeholder: placeholder, isRequired: isRequired });
                    });

                    return data;
                }

                var extractedCustomerData = extractCustomerData();
                $('#customer-info-display-div').empty();

                // Loop through the extracted data and populate the display div
                $.each(extractedCustomerData, function(index, item) {
                    var isRequiredAttribute = item.isRequired ? 'required' : '';
                    var displayHtml = `
                        <input type="text" class="form-control" name="payment_by_${index}" id="payment_by_${index}"
                        readonly placeholder="${item.placeholder}"  ${isRequiredAttribute}>
                    `;
                    $('#customer-info-display-div').append(displayHtml);
                });
            }

            // Open the modal
            $("#sectionViewModal").modal("show");
        }
        $(document).ready(function() {
            $("#bkashInfoModalButton").on('click', function() {
                console.log("something");
                var contentArgument = "bkashInfo";
                openModal(contentArgument);
            });
            $("#paymentInfoModalButton").on('click', function() {
                var contentArgument = "paymentInfo";
                openModal(contentArgument);
            });
        });


        function remove_field(fieldRowId) {
            $( `#field-row-customer--${fieldRowId}` ).remove();
            counter--;
        }

        function remove_field_payment(fieldRowId) {
            $( `#field-row-payment--${fieldRowId}` ).remove();
            counterPayment--;
        }

        jQuery(document).ready(function ($) {


            const parentContainerPayment = document.getElementById('custom-field-section-payment');
            const parentContainerCustomer = document.getElementById('custom-field-section-customer');
            const numberOfFieldRowsPayment = parentContainerPayment.querySelectorAll('.field-row-payment').length;
            const numberOfFieldRowsCustomer = parentContainerCustomer.querySelectorAll('.field-row-customer').length;

            counter = numberOfFieldRowsCustomer;
            counterPayment = numberOfFieldRowsPayment;

            $('#add-more-field-customer').on('click', function (event) {
                if(counter < 14) {
                    event.preventDefault();

                    $('#custom-field-section-customer').append(
                        `<div class="card card-body mt-3 field-row-customer" id="field-row-customer--${counter}">
                            <div class="row gy-4 align-items-center">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="field_name[${counter}]"
                                               placeholder="Select Field Name" value="" required>
                                        <label>{{translate('input_field_name')}} *</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="placeholder[${counter}]"
                                               placeholder="Select placeholder" value="" required>
                                        <label>{{translate('placeholder')}} *</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-between gap-2 align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" name="is_required[${counter}]" id="flexCheckDefault__${counter}" checked>
                                            <label class="form-check-label" for="flexCheckDefault__${counter}">
                                                {{translate('This_field_required')}}
                                            </label>
                                        </div>

                                        <span class="text-danger" onclick="remove_field(${counter})"  style="cursor: pointer;">
                                            <span class="material-icons">delete</span>
                                        </span>
                                    </div>
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

            $('#add-more-field-payment').on('click', function (event) {
                if(counterPayment < 14) {
                    event.preventDefault();

                    $('#custom-field-section-payment').append(
                        `<div class="card card-body field-row-payment" id="field-row-payment--${counterPayment}">
                            <div class="row gy-4 align-items-center">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="title[]"
                                               placeholder="Select field name" value="" required>
                                        <label>{{translate('title')}} *</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="data[]"
                                               placeholder="Select data" value="" required>
                                        <label>{{translate('data')}} *</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex justify-content-end">
                                        <span class="text-danger" onclick="remove_field_payment(${counterPayment})"  style="cursor: pointer;">
                                            <span class="material-icons">delete</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>`
                    );

                    $(".js-select").select2();

                    counterPayment++;
                } else {
                    Swal.fire({
                        title: '{{translate('Reached maximum')}}',
                        confirmButtonText: '{{translate('ok')}}',
                    });
                }
            })

            $('form').on('reset', function (event) {
                if(counter > 1) {
                    $('#custom-field-section-payment').html("");
                    $('#custom-field-section-customer').html("");
                    $('#method_name').val("");
                    $('#payment_note').val("");
                }

                counter = 1;
            })
        });
    </script>


@endpush
