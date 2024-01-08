@extends('adminmodule::layouts.master')

@section('title',translate('Booking_List'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Filter Aside -->
    <div class="filter-aside">
        <div class="filter-aside__header d-flex justify-content-between align-items-center">
            <h3 class="filter-aside__title">{{translate('Filter_your_Booking')}}</h3>
            <button type="button" class="btn-close p-2"></button>
        </div>
        <form action="{{route('admin.booking.list', ['booking_status'=>$query_param['booking_status']])}}" method="POST"
              enctype="multipart/form-data">
            @csrf
            <div class="filter-aside__body d-flex flex-column">
                <div class="filter-aside__date_range">
                    <h4 class="fw-normal mb-4">{{translate('Select_Date_Range')}}</h4>
                    <div class="mb-30">
                        <div class="form-floating">
                            <input type="date" class="form-control" placeholder="{{translate('start_date')}}" name="start_date"
                                   value="{{$query_param['start_date']}}">
                            <label for="floatingInput">{{translate('Start_Date')}}</label>
                        </div>
                    </div>
                    <div class="fw-normal mb-30">
                        <div class="form-floating">
                            <input type="date" class="form-control" placeholder="{{translate('end_date')}}" name="end_date"
                                   value="{{$query_param['end_date']}}">
                            <label for="floatingInput">{{translate('End_Date')}}</label>
                        </div>
                    </div>
                </div>

                <div class="filter-aside__category_select">
                    <h4 class="fw-normal mb-2">{{translate('Select_Categories')}}</h4>
                    <div class="mb-30">
                        <select class="category-select theme-input-style w-100" name="category_ids[]" multiple="multiple">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" {{in_array($category->id,$query_param['category_ids']??[])?'selected':''}}>
                                    {{$category->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="filter-aside__category_select">
                    <h4 class="fw-normal mb-2">{{translate('Select_Sub_Categories')}}</h4>
                    <div class="mb-30">
                        <select class="subcategory-select theme-input-style w-100" name="sub_category_ids[]" multiple="multiple">
                            @foreach($sub_categories as $sub_category)
                                <option value="{{$sub_category->id}}" {{in_array($sub_category->id,$query_param['sub_category_ids']??[])?'selected':''}}>
                                    {{$sub_category->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="filter-aside__zone_select">
                    <h4 class="mb-2 fw-normal">{{translate('Select_Zones')}}</h4>
                    <div class="mb-30">
                        <select class="zone-select theme-input-style w-100" name="zone_ids[]" multiple="multiple">
                            @foreach($zones as $zone)
                                <option value="{{$zone->id}}" {{in_array($zone->id,$query_param['zone_ids']??[])?'selected':''}}>
                                    {{$zone->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="filter-aside__bottom_btns p-20">
                <div class="d-flex justify-content-center gap-20">
                    <button class="btn btn--secondary text-capitalize" type="reset">{{translate('Clear_all_Filter')}}</button>
                    <button class="btn btn--primary text-capitalize" type="submit">{{translate('Filter')}}</button>
                </div>
            </div>
        </form>
    </div>
    <!-- End Filter Aside -->

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Booking_Request')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">

                                <form action="{{url()->current()}}?booking_status={{$query_param['booking_status']}}"
                                      class="search-form search-form_style-two"
                                      method="POST">
                                    @csrf
                                    <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                        <input type="search" class="theme-input-style search-form__input"
                                               value="{{$query_param['search']??''}}" name="search"
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
                                            <span class="material-icons">file_download</span> {{translate('download')}}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                            <li><a class="dropdown-item" href="{{route('admin.booking.download')}}?booking_status={{$query_param['booking_status']}}">{{translate('excel')}}</a></li>
                                        </ul>
                                    </div>

                                    <button type="button" class="btn text-capitalize filter-btn px-0">
                                        <span class="material-icons">filter_list</span> {{translate('Filter')}}
                                        <span class="count">{{$filter_counter??0}}</span>
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="example" class="table align-middle">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>{{translate('Booking_ID')}}</th>
                                            <th>{{translate('Customer_Info')}}</th>
                                            <th>{{translate('Total_Amount')}}</th>
                                            <th>{{translate('Payment_Status')}}</th>
                                            <th>{{translate('Schedule_Date')}}</th>
                                            <th>{{translate('Booking_Date')}}</th>
                                            <th>{{translate('View')}}</th>
                                            <th>{{translate('Status')}}</th>
                                            <th>{{translate('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bookings as $key=>$booking)
                                        <tr>
                                            <td>
                                                <a href="{{route('admin.booking.details', [$booking->id,'web_page'=>'details'])}}">
                                                    {{$booking->readable_id}}</a>
                                            </td>
                                            <td>
                                                <div>
                                                    @if($booking->customer)
                                                        <a href="{{route('admin.customer.detail',[$booking?->customer?->id, 'web_page'=>'overview'])}}">
                                                            @php
                                                                $fullName = ($booking?->customer?->first_name ?? '') . ' ' . ($booking?->customer?->last_name ?? '');
                                                                $limitedFullName = Str::limit($fullName, 30);
                                                            @endphp

                                                            {{ $limitedFullName }}
                                                        </a>
                                                    @else
                                                        <span>
                                                            {{Str::limit($booking?->service_address?->contact_person_name, 30)}}
                                                        </span>
                                                    @endif
                                                </div>
                                                {{$booking->customer ? $booking?->customer?->phone : $booking?->service_address?->contact_person_number}}
                                            </td>
                                            <td>{{$booking->total_booking_amount}}</td>
                                            <td>
                                                <span class="badge badge badge-{{$booking->is_paid?'success':'danger'}} radius-50">
                                                    <span class="dot"></span>
                                                    {{$booking->is_paid?translate('paid'):translate('unpaid')}}
                                                </span>
                                            </td>
                                            <td>{{date('d-M-Y h:ia',strtotime($booking->service_schedule))}}</td>
                                            <td>{{date('d-M-Y h:ia',strtotime($booking->created_at))}}</td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="{{route('admin.booking.details', [$booking->id,'web_page'=>'details'])}}"
                                                       type="button"
                                                       class="table-actions_view btn btn--light-primary fw-medium text-capitalize fz-14">
                                                        <span class="material-icons">visibility</span>
                                                        {{translate('View_Details')}}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($booking->is_verified == '0')
                                                    <label class="badge badge-info">{{translate('pending')}}</label>
                                                @elseif($booking->is_verified == '1')
                                                    <label class="badge badge-success">{{translate('verified')}}</label>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($booking->is_verified == '0')
                                                    <!-- Approve -->
                                                    <button type="button" class="btn btn--success" onclick="route_alert_reload('{{route('admin.booking.verification_status_update',[$booking->id])}}','{{translate('want_to_verified_this_booking')}}')">
                                                        <span class="material-icons">done_outline</span>{{translate('Verify')}}
                                                    </button>
                                                @elseif($booking->is_verified == '1')
                                                    <label class="badge badge-success">{{translate('verified')}}</label>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {!! $bookings->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";

            //Select 2
            $('.category-select').select2({
                placeholder: "Select Category"
            });
            $('.subcategory-select').select2({
                placeholder: "Select Subcategory"
            });
            $('.zone-select').select2({
                placeholder: "Select Zone"
            })

        })(jQuery);
    </script>
@endpush
