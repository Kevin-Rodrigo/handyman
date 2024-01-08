@extends('adminmodule::layouts.master')

@section('title',translate('Booking_Details'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-title-wrap mb-3">
                <h2 class="page-title">{{translate('Booking_Details')}} </h2>
            </div>

            <ul class="nav nav--tabs nav--tabs__style2 mb-4">
                <li class="nav-item">
                    <a class="nav-link {{$web_page=='details'?'active':''}}"
                       href="{{url()->current()}}?web_page=details">{{translate('details')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$web_page=='status'?'active':''}}"
                       href="{{url()->current()}}?web_page=status">{{translate('status')}}</a>
                </li>
            </ul>

            <div class="row gy-3">
                <div class="col-lg-8">
                    <div class="card mb-3">
                        <div class="card-body pb-5">
                            <div
                                class="border-bottom pb-3 d-flex justify-content-between align-items-center gap-3 flex-wrap">
                                <div>
                                    <h3 class="c1 mb-2">{{translate('Booking')}}
                                        # {{$booking['readable_id']}}</h3>
                                    <p class="opacity-75 fz-12">{{translate('Booking_Placed')}} : {{date('d-M-Y h:ia',strtotime($booking->created_at))}}</p>
                                </div>
                                <div class="d-flex flex-wrap flex-xxl-nowrap gap-3">
                                    <div class="d-flex flex-wrap gap-3">
                                        @if($booking['payment_method'] == 'offline_payment' && !$booking['is_paid'])
                                            <span onclick="route_alert_reload('{{route('admin.booking.offline-payment.verify',['booking_id' => $booking->id])}}', '{{translate('Want to verify the payment')}}?')"
                                               class="btn btn-secondary">
                                                <span class="material-icons">done</span>{{translate('Verify Offline Payment')}}
                                            </span>
                                        @endif
                                        @if(in_array($booking['booking_status'], ['pending', 'accepted', 'ongoing']) && $booking->booking_partial_payments->isEmpty())
                                            <button class="btn btn--primary" data-bs-toggle="modal"
                                                data-bs-target="#serviceUpdateModal--{{$booking['id']}}"
                                                data-toggle="tooltip" title="{{translate('Add or remove services')}}">
                                                <span class="material-symbols-outlined">edit</span>{{translate('Edit Services')}}
                                            </button>
                                        @endif
                                        <a href="{{route('admin.booking.invoice',[$booking->id])}}"
                                            class="btn btn-primary" target="_blank">
                                             <span class="material-icons">description</span>{{translate('Invoice')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="border-bottom py-3 d-flex justify-content-between align-items-center gap-3 flex-wrap mb-3">
                                <div>
                                    <h4 class="mb-2">{{translate('Payment_Method')}}</h4>
                                    <h5 class="c1 mb-2">{{ translate($booking->payment_method) }}</h5>
                                    <p>
                                        <span>{{translate('Amount')}} : </span> {{with_currency_symbol($booking->total_booking_amount)}}
                                    </p>

                                    @foreach($booking?->booking_offline_payments?->first()?->customer_information??[] as $key=>$item)
                                        <span>{{translate($key)}}</span>: <span>{{translate($item)}}</span> <br>
                                    @endforeach
                                </div>
                                <div>
                                    <p class="mb-2"><span>{{translate('Booking_Status')}} :</span> <span
                                            class="c1 text-capitalize"
                                            id="booking_status__span">{{$booking->booking_status}}</span></p>
                                    <p class="mb-2"><span>{{translate('Payment_Status')}} : </span> <span
                                            class="text-{{$booking->is_paid ? 'success' : 'danger'}}"
                                            id="payment_status__span">{{$booking->is_paid ? translate('Paid') : translate('Unpaid')}}</span>

                                        <!-- partially paid badge -->
                                        @if(!$booking->is_paid && $booking->booking_partial_payments->isNotEmpty())
                                            <span class="small badge badge-info text-success p-1 fz-10">{{translate('Partially paid')}}</span>
                                        @endif
                                    </p>
                                    <p class="mb-2"><span>{{translate('Booking_Otp')}} :</span> <span
                                        class="c1 text-capitalize"
                                        >{{$booking?->booking_otp ?? ''}}</span></p>
                                    <h5>{{translate('Service_Schedule_Date')}} : <span
                                            id="service_schedule__span">{{date('d/m/Y h:ia',strtotime($booking->service_schedule))}}</span>
                                    </h5>
                                </div>
                            </div>

                            <!-- Booking summary -->
                            <div class="d-flex justify-content-start gap-2">
                                <h3 class="mb-3">{{translate('Booking_Summary')}}</h3>
                                {{-- @if($booking['booking_status'] == 'pending' || $booking['booking_status'] == 'accepted' || $booking['booking_status'] == 'ongoing')
                                    <i class="material-icons" data-bs-toggle="modal"
                                       data-bs-target="#serviceUpdateModal--{{$booking['id']}}"
                                       data-toggle="tooltip" title="{{translate('Add or remove services')}}">edit</i>
                                @endif --}}
                            </div>

                            <div class="table-responsive border-bottom">
                                <table class="table text-nowrap align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th class="ps-lg-3">{{translate('Service')}}</th>
                                        <th>{{translate('Price')}}</th>
                                        <th>{{translate('Qty')}}</th>
                                        <th>{{translate('Discount')}}</th>
                                        <th>{{translate('Vat')}}</th>
                                        <th class="text-end">{{translate('Total')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($sub_total=0)
                                    @foreach($booking->detail as $detail)
                                        <tr>
                                            <td class="text-wrap ps-lg-3">
                                                @if(isset($detail->service))
                                                    <div class="d-flex flex-column">
                                                        <a href="{{route('admin.service.detail',[$detail->service->id])}}"
                                                           class="fw-bold">{{Str::limit($detail->service->name, 30)}}</a>
                                                        <div>{{Str::limit($detail ? $detail->variant_key : '', 50)}}</div>
                                                    </div>
                                                @else
                                                    <span
                                                        class="badge badge-pill badge-danger">{{translate('Service_unavailable')}}</span>
                                                @endif
                                            </td>
                                            <td>{{with_currency_symbol($detail->service_cost)}}</td>
                                            <td>
                                                <span>{{$detail->quantity}}</span>
                                            </td>
                                            <td>{{with_currency_symbol($detail->discount_amount)}}</td>
                                            <td>{{with_currency_symbol($detail->tax_amount)}}</td>
                                            <td class="text-end">{{with_currency_symbol($detail->total_cost)}}</td>
                                        </tr>
                                        @php($sub_total+=$detail->service_cost*$detail->quantity)
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row justify-content-end mt-3">
                                <div class="col-sm-10 col-md-6 col-xl-5">
                                    <div class="table-responsive">
                                        <table class="table-sm title-color align-right w-100">
                                            <tbody>
                                            <tr>
                                                <td>{{translate('Sub_Total_(Vat _Excluded)')}}</td>
                                                <td>{{with_currency_symbol($sub_total)}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{translate('Discount')}}</td>
                                                <td>{{with_currency_symbol($booking->total_discount_amount)}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{translate('Coupon_Discount')}}</td>
                                                <td>{{with_currency_symbol($booking->total_coupon_discount_amount)}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{translate('Campaign_Discount')}}</td>
                                                <td>{{with_currency_symbol($booking->total_campaign_discount_amount)}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{translate('Vat')}}</td>
                                                <td>{{with_currency_symbol($booking->total_tax_amount)}}</td>
                                            </tr>
                                            @if ($booking->extra_fee > 0)
                                                @php($additional_charge_label_name = business_config('additional_charge_label_name', 'booking_setup')->live_values??'Fee')
                                                <tr>
                                                    <td>{{$additional_charge_label_name}}</td>
                                                    <td>{{with_currency_symbol($booking->extra_fee)}}</td>
                                                </tr>
                                            @endif

                                            <!-- grand total -->
                                            <tr>
                                                <td><strong>{{translate('Grand_Total')}}</strong></td>
                                                <td>
                                                    <strong>{{with_currency_symbol($booking->total_booking_amount)}}</strong>
                                                </td>
                                            </tr>

                                            <!-- partial -->
                                            @if ($booking->booking_partial_payments->isNotEmpty())
                                                @foreach($booking->booking_partial_payments as $partial)
                                                    <tr>
                                                        <td>{{translate('Paid_by')}} {{str_replace('_', ' ',$partial->paid_with)}}</td>
                                                        <td>{{with_currency_symbol($partial->paid_amount)}}</td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            <!-- due -->
                                            <?php
                                            $due_amount = 0;

                                            if (!$booking->is_paid && $booking?->booking_partial_payments?->count() == 1) {
                                                $due_amount = $booking->booking_partial_payments->first()?->due_amount;
                                            }

                                            if (in_array($booking->booking_status, ['pending', 'accepted', 'ongoing']) && $booking->payment_method != 'cash_after_service' && $booking->additional_charge > 0) {
                                                $due_amount += $booking->additional_charge;
                                            }
                                            ?>

                                            <tr>
                                                <td>{{ translate('Due_Amount') }}</td>
                                                <td>{{ with_currency_symbol($due_amount) }}</td>
                                            </tr>

                                            <!-- refund -->
                                            @if($booking->payment_method != 'cash_after_service' && $booking->additional_charge < 0)
                                                <tr>
                                                    <td>{{translate('Refund')}}</td>
                                                    <td>{{with_currency_symbol(abs($booking->additional_charge))}}</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="c1">{{translate('Booking Setup')}}</h3>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center gap-10 form-control">
                                <span class="title-color">{{translate('Payment Status')}}</span>

                                <label class="switcher payment-status-text">
                                    <input class="switcher_input" id="payment_status" onclick="payment_status_change($(this).is(':checked')===true?1:0)" type="checkbox" value="{{$booking['is_paid'] ? '1' : '0'}}" {{$booking['is_paid'] ? 'checked' : ''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>

                            <div class="mt-3">
                                <select class="js-select" id="serviceman_assign">
                                    <option value="no_serviceman">{{translate('Select Serviceman')}}</option>
                                    @foreach($servicemen as $serviceman)
                                        <option value="{{$serviceman->id}}" {{$booking->serviceman_id == $serviceman->id ? 'selected' : ''}} >
                                            {{$serviceman->user ? Str::limit($serviceman->user->first_name.' '.$serviceman->user->last_name, 30):''}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mt-3">
                                @if($booking->booking_status != 'pending')
                                    <select class="js-select" id="booking_status">
                                        <option value="0">{{translate('Select Booking Status')}}</option>
                                        <option
                                            value="ongoing" {{$booking['booking_status'] == 'ongoing' ? 'selected' : ''}}>{{translate('Ongoing')}}</option>
                                        <option
                                            value="completed" {{$booking['booking_status'] == 'completed' ? 'selected' : ''}}>{{translate('Completed')}}</option>
                                        <option
                                            value="canceled" {{$booking['booking_status'] == 'canceled' ? 'selected' : ''}}>{{translate('Canceled')}}</option>
                                    </select>
                                @endif
                            </div>

                            <div class="mt-3">
                                @if(!in_array($booking->booking_status,['ongoing','completed']))
                                    <input type="datetime-local" class="form-control" name="service_schedule" value="{{$booking->service_schedule}}" id="service_schedule"  onchange="service_schedule_update()">
                                @endif
                            </div>


                            <div class="py-3 d-flex flex-column gap-3 mb-2">
                                <!-- Customer Info -->
                                <div class="c1-light-bg radius-10 py-3 px-4">
                                    <div class="d-flex justify-content-start gap-2">
                                        <h4 class="mb-2">{{translate('uploaded_Images')}}</h4>
                                    </div>

                                    <div>
                                        <div class="d-flex flex-wrap gap-3 justify-content-lg-start">
                                            @foreach ($booking->evidence_photos ?? [] as $key => $img)
                                                <img src="{{asset('storage/app/public/booking/evidence').'/'.$img}}" width="100"  class="max-height-100"
                                                     onerror="this.src='{{asset('public/assets/provider-module')}}/img/media/info-details.png'">
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <!-- End Customer Info -->
                                <!-- Customer Info -->
                                <div class="c1-light-bg radius-10 py-3 px-4">
                                    <div class="d-flex justify-content-start gap-2">
                                        <h4 class="mb-2">{{translate('Customer_Information')}}</h4>
                                        @if($booking['booking_status'] == 'pending' || $booking['booking_status'] == 'accepted' || $booking['booking_status'] == 'ongoing')
                                            <i class="material-icons" data-bs-toggle="modal"
                                               data-bs-target="#serviceAddressModal--{{$booking['id']}}"
                                               data-toggle="tooltip" data-placement="top"
                                               title="{{translate('Update service address')}}">edit</i>
                                        @endif
                                    </div>

                                    @php($customer_name = $booking?->service_address?->contact_person_name)
                                    @php($customer_phone = $booking?->service_address?->contact_person_number)
                                    <h5 class="c1 mb-3">
                                        @if(!$booking?->is_guest)
                                            <a href="{{route('admin.customer.detail',[$booking?->customer?->id, 'web_page'=>'overview'])}}"
                                               class="c1">{{Str::limit($customer_name, 30)}}</a>
                                        @else
                                            <span>{{Str::limit($customer_name??'', 30)}}</span>
                                        @endif
                                    </h5>
                                    <ul class="list-info">
                                        @if ($customer_phone)
                                            <li>
                                                <span class="material-icons">phone_iphone</span>
                                                <a href="tel:{{$customer_phone}}">{{$customer_phone}}</a>
                                            </li>
                                        @endif
                                        <li>
                                            <span class="material-icons">map</span>
                                            <p>{{Str::limit($booking?->service_address?->address??translate('not_available'), 100)}}</p>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Customer Info -->

                                <!-- Provider Info -->
                                <div class="c1-light-bg radius-10 py-3 px-4">
                                    <h4 class="mb-2">{{translate('Provider Information')}}</h4>
                                    @if(isset($booking->provider))
                                        <h5 class="c1 mb-3">{{Str::limit($booking->provider->company_name??'', 30)}}</h5>
                                        <ul class="list-info">
                                            <li>
                                                <span class="material-icons">phone_iphone</span>
                                                <a href="tel:88013756987564">{{$booking->provider->contact_person_phone??''}}</a>
                                            </li>
                                            <li>
                                                <span class="material-icons">map</span>
                                                <p>{{Str::limit($booking->provider->company_address??'', 100)}}</p>
                                            </li>
                                        </ul>
                                    @else
                                        <p class="text-muted text-center mt-30 fz-12">{{translate('No Provider Information')}}</p>
                                    @endif
                                </div>
                                <!-- End Provider Info -->

                                <!-- Lead Service Info -->
                                <div class="c1-light-bg radius-10 py-3 px-4">
                                    <h4 class="mb-2">{{translate('Lead_Service_Information')}}</h4>
                                    @if(isset($booking->serviceman))
                                        <h5 class="c1 mb-3">{{Str::limit($booking->serviceman && $booking->serviceman->user ? $booking->serviceman->user->first_name.' '.$booking->serviceman->user->last_name:'', 30)}}</h5>
                                        <ul class="list-info">
                                            <li>
                                                <span class="material-icons">phone_iphone</span>
                                                <a href="tel:{{$booking->serviceman && $booking->serviceman->user ? $booking->serviceman->user->phone:''}}">
                                                    {{$booking->serviceman && $booking->serviceman->user ? $booking->serviceman->user->phone:''}}
                                                </a>
                                            </li>
                                        </ul>
                                    @else
                                        <p class="text-muted text-center mt-30 fz-12">{{translate('No Serviceman Information')}}</p>
                                    @endif
                                </div>
                                <!-- End Lead Service Info -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->

    <!-- Service Address Update Modal -->
    @include('bookingmodule::admin.booking.partials.details._service-address-modal')

    <!-- Service Update Modal -->
    @include('bookingmodule::admin.booking.partials.details._service-modal')
@endsection

@push('script')
    <script>
        @if($booking->booking_status == 'pending')
        $(document).ready(function () {
            selectElementVisibility('serviceman_assign', false);
            selectElementVisibility('payment_status', false);
        });
        @endif

        //booking_status update
        $("#booking_status").change(function () {
            var booking_status = $("#booking_status option:selected").val();
            if (parseInt(booking_status) !== 0) {
                var route = '{{route('admin.booking.status_update',[$booking->id])}}' + '?booking_status=' + booking_status;
                update_booking_details(route, '{{translate('want_to_update_status')}}', 'booking_status', booking_status);
            } else {
                toastr.error('{{translate('choose_proper_status')}}');
            }
        });

        //serviceman assign/update
        $("#serviceman_assign").change(function () {
            var serviceman_id = $("#serviceman_assign option:selected").val();
            if (serviceman_id !== 'no_serviceman') {
                var route = '{{route('admin.booking.serviceman_update',[$booking->id])}}' + '?serviceman_id=' + serviceman_id;

                update_booking_details(route, '{{translate('want_to_assign_the_serviceman')}}?', 'serviceman_assign', serviceman_id);
            } else {
                toastr.error('{{translate('choose_proper_serviceman')}}');
            }
        });

        //payment_status update
        function payment_status_change(payment_status) {
            var route = '{{route('admin.booking.payment_update',[$booking->id])}}' + '?payment_status=' + payment_status;
            update_booking_details(route, '{{translate('want_to_update_status')}}', 'payment_status', payment_status);
        }

        //booking_schedule update
        function service_schedule_update() {
            var service_schedule = $("#service_schedule").val();
            var route = '{{route('admin.booking.schedule_update',[$booking->id])}}' + '?service_schedule=' + service_schedule;

            update_booking_details(route, '{{translate('want_to_update_the_booking_schedule')}}', 'service_schedule', service_schedule);
        }

        //update ajax function
        function update_booking_details(route, message, componentId, updatedValue) {
            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'var(--c2)',
                confirmButtonColor: 'var(--c1)',
                cancelButtonText: '{{translate('Cancel')}}',
                confirmButtonText: '{{translate('Yes')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: route,
                        dataType: 'json',
                        data: {},
                        beforeSend: function () {
                            /*$('.preloader').show();*/
                        },
                        success: function (data) {
                            // console.log('tt');return false;
                            update_component(componentId, updatedValue);
                            toastr.success(data.message, {
                                CloseButton: true,
                                ProgressBar: true
                            });

                            if (componentId === 'booking_status' || componentId === 'payment_status' || componentId === 'service_schedule') {
                                location.reload();
                            }
                        },
                        complete: function () {
                            /*$('.preloader').hide();*/
                        },
                    });
                }
            })
        }

        //component update
        function update_component(componentId, updatedValue) {

            if (componentId === 'booking_status') {
                $("#booking_status__span").html(updatedValue);

                selectElementVisibility('serviceman_assign', true);
                selectElementVisibility('payment_status', true);

            } else if (componentId === 'payment_status') {
                $("#payment_status__span").html(updatedValue);
                if (updatedValue === 'paid') {
                    $("#payment_status__span").addClass('text-success').removeClass('text-danger');
                } else if (updatedValue === 'unpaid') {
                    $("#payment_status__span").addClass('text-danger').removeClass('text-success');
                }

            }
        }

        //component update
        function selectElementVisibility(componentId, visibility) {
            if (visibility === true) {
                $('#' + componentId).next(".select2-container").show();
            } else if (visibility === false) {
                $('#' + componentId).next(".select2-container").hide();
            } else {
            }
        }
    </script>

    <!-- Booking Edit -->
    <script>
        //placeholder
        $(document).ready(function () {
            $('#category_selector__select').select2({dropdownParent: "#serviceUpdateModal--{{$booking['id']}}"});
            $('#sub_category_selector__select').select2({dropdownParent: "#serviceUpdateModal--{{$booking['id']}}"});
            $('#service_selector__select').select2({dropdownParent: "#serviceUpdateModal--{{$booking['id']}}"});
            $('#service_variation_selector__select').select2({dropdownParent: "#serviceUpdateModal--{{$booking['id']}}"});
        });

        //service select event
        $("#service_selector__select").on('change', function () {
            //reset
            $("#service_variation_selector__select").html('<option value="" selected disabled>{{translate('Select Service Variant')}}</option>');

            const serviceId = this.value;
            const route = '{{route('admin.booking.service.ajax-get-variant')}}' + '?service_id=' + serviceId + '&zone_id=' + "{{$booking->zone_id}}";

            $.get({
                url: route,
                dataType: 'json',
                data: {},
                beforeSend: function () {
                    $('.preloader').show();
                },
                success: function (response) {
                    var selectString = '<option value="" selected disabled>{{translate('Select Service Variant')}}</option>';
                    response.content.forEach((item) => {
                        selectString += `<option value="${item.variant_key}">${item.variant}</option>`;
                    });
                    $("#service_variation_selector__select").html(selectString)
                },
                complete: function () {
                    $('.preloader').hide();
                },
                error: function () {
                    toastr.error('{{translate('Failed to load')}}')
                }
            });
        })

        //reset [while close modal]
        $("#serviceUpdateModal--{{$booking['id']}}").on('hidden.bs.modal', function () {
            $('#service_selector__select').prop('selectedIndex',0);
            $("#service_variation_selector__select").html('<option value="" selected disabled>{{translate('Select Service Variant')}}</option>');
            $("#service_quantity").val('');
        });

        //add service
        $("#add-service").on('click', function () {
            const service_id = $("[name='service_id']").val();
            const variant_key = $("[name='variant_key']").val();
            const quantity = parseInt($("[name='service_quantity']").val());
            const zone_id = '{{$booking->zone_id}}';

            //let form_data = $('#booking-edit-table').serializeArray();

            //validation
            if(service_id === '' || service_id === null) {
                toastr.error('{{translate('Select a service')}}', { CloseButton: true, ProgressBar: true });
                return;
            } else if(variant_key === '' || variant_key === null) {
                toastr.error('{{translate('Select a variation')}}', { CloseButton: true, ProgressBar: true });
                return;
            } else if(quantity < 1) {
                toastr.error('{{translate('Quantity must not be empty')}}', { CloseButton: true, ProgressBar: true });
                return;
            }

            //if variant key already exists
            let variant_key_array = [];
            $('input[name="variant_keys[]"]').each(function() {
                variant_key_array.push($(this).val());
            });

            if (variant_key_array.includes(variant_key)) {
                const decimal_point = parseInt('{{(business_config('currency_decimal_point', 'business_information'))->live_values ?? 2}}');

                const old_qty = parseInt($(`#qty-${variant_key}`).val());
                const updated_qty = old_qty + quantity;

                const old_total_cost = parseFloat($(`#total-cost-${variant_key}`).text());
                const updated_total_cost = ((old_total_cost * updated_qty)/old_qty).toFixed(decimal_point);

                const old_discount_amount = parseFloat($(`#discount-amount-${variant_key}`).text());
                const updated_discount_amount = ((old_discount_amount * updated_qty)/old_qty).toFixed(decimal_point);


                $(`#qty-${variant_key}`).val(updated_qty);
                $(`#total-cost-${variant_key}`).text(updated_total_cost);
                $(`#discount-amount-${variant_key}`).text(updated_discount_amount);

                toastr.success('{{translate('Added successfully')}}', { CloseButton: true, ProgressBar: true });
                return;
            }

            let query_string = 'service_id=' + service_id + '&variant_key=' + variant_key + '&quantity=' + quantity + '&zone_id=' + zone_id;
            $.ajax({
                type: 'GET',
                url: "{{route('admin.booking.service.ajax-get-service-info')}}" + '?' + query_string,
                data: {},
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('.preloader').show();
                },
                success: function(response) {
                    $("#service-edit-tbody").append(response.view);
                    toastr.success('{{translate('Added successfully')}}', { CloseButton: true, ProgressBar: true });
                },
                complete: function () {
                    $('.preloader').hide();
                },
            });
        })

        //remove service
        function removeServiceRow(row) {
            const row_count = $('#service-edit-tbody tr').length;
            if (row_count <= 1) {
                toastr.error('{{translate('Can not remove the only service')}}');
                return;
            }

            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: '{{translate('want to remove the service from the booking')}}',
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
                    $(`#${row}`).remove();
                }
            })
        }
    </script>
    <!-- end -->

    <!-- Map scripts (customer address) -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{business_config('google_map', 'third_party')?->live_values['map_api_key_client']}}&libraries=places&v=3.45.8"></script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });


        $( document ).ready(function() {
            function initAutocomplete() {
                var myLatLng = {

                    lat: 23.811842872190343,
                    lng: 90.356331
                };
                const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                    center: {
                        lat: 23.811842872190343,
                        lng: 90.356331
                    },
                    zoom: 13,
                    mapTypeId: "roadmap",
                });

                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                });

                marker.setMap(map);
                var geocoder = geocoder = new google.maps.Geocoder();
                google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
                    var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                    var coordinates = JSON.parse(coordinates);
                    var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                    marker.setPosition(latlng);
                    map.panTo(latlng);

                    document.getElementById('latitude').value = coordinates['lat'];
                    document.getElementById('longitude').value = coordinates['lng'];


                    geocoder.geocode({
                        'latLng': latlng
                    }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[1]) {
                                document.getElementById('address').innerHtml = results[1].formatted_address;
                            }
                        }
                    });
                });
                // Create the search box and link it to the UI element.
                const input = document.getElementById("pac-input");
                const searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
                // Bias the SearchBox results towards current map's viewport.
                map.addListener("bounds_changed", () => {
                    searchBox.setBounds(map.getBounds());
                });
                let markers = [];
                // Listen for the event fired when the user selects a prediction and retrieve
                // more details for that place.
                searchBox.addListener("places_changed", () => {
                    const places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }
                    // Clear out the old markers.
                    markers.forEach((marker) => {
                        marker.setMap(null);
                    });
                    markers = [];
                    // For each place, get the icon, name and location.
                    const bounds = new google.maps.LatLngBounds();
                    places.forEach((place) => {
                        if (!place.geometry || !place.geometry.location) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        var mrkr = new google.maps.Marker({
                            map,
                            title: place.name,
                            position: place.geometry.location,
                        });
                        google.maps.event.addListener(mrkr, "click", function(event) {
                            document.getElementById('latitude').value = this.position.lat();
                            document.getElementById('longitude').value = this.position.lng();
                        });

                        markers.push(mrkr);

                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });
            };
            initAutocomplete();
        });


        $('.__right-eye').on('click', function(){
            if($(this).hasClass('active')) {
                $(this).removeClass('active')
                $(this).find('i').removeClass('tio-invisible')
                $(this).find('i').addClass('tio-hidden-outlined')
                $(this).siblings('input').attr('type', 'password')
            }else {
                $(this).addClass('active')
                $(this).siblings('input').attr('type', 'text')


                $(this).find('i').addClass('tio-invisible')
                $(this).find('i').removeClass('tio-hidden-outlined')
            }
        })
    </script>
    <!-- End -->
@endpush
