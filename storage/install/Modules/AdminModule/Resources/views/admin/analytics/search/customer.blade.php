@extends('adminmodule::layouts.master')

@section('title',translate('Customer_Search_Analytics'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-title-wrap mb-3">
                <h2 class="page-title">{{translate('Customer_Search_Analytics')}}</h2>
            </div>

            <div class="row gy-3">
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                                <div class="">
                                    <h4 class="mb-1">{{translate('Top 5 Customer')}}</h4>
                                    <p class="fs-12">{{translate('According to search volume')}}</p>
                                </div>
                                <div class="select-wrap d-flex flex-wrap gap-10">
                                    <select class="js-select min-w180 h-30 top-customers__select">
                                        <option value="" disabled selected>{{translate('Select_Date_Range')}}</option>
                                        <option value="all_time" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='all_time'?'selected':''}}>{{translate('All_Time')}}</option>
                                        <option value="this_week" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='this_week'?'selected':''}}>{{translate('This_Week')}}</option>
                                        <option value="last_week" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='last_week'?'selected':''}}>{{translate('Last_Week')}}</option>
                                        <option value="this_month" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='this_month'?'selected':''}}>{{translate('This_Month')}}</option>
                                        <option value="last_month" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='last_month'?'selected':''}}>{{translate('Last_Month')}}</option>
                                        <option value="last_15_days" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='last_15_days'?'selected':''}}>{{translate('Last_15_Days')}}</option>
                                        <option value="this_year" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='this_year'?'selected':''}}>{{translate('This_Year')}}</option>
                                        <option value="last_year" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='last_year'?'selected':''}}>{{translate('Last_Year')}}</option>
                                        <option value="last_6_month" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='last_6_month'?'selected':''}}>{{translate('Last_6_Month')}}</option>
                                        <option value="this_year_1st_quarter" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='this_year_1st_quarter'?'selected':''}}>{{translate('This_Year_1st_Quarter')}}</option>
                                        <option value="this_year_2nd_quarter" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='this_year_2nd_quarter'?'selected':''}}>{{translate('This_Year_2nd_Quarter')}}</option>
                                        <option value="this_year_3rd_quarter" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='this_year_3rd_quarter'?'selected':''}}>{{translate('This_Year_3rd_Quarter')}}</option>
                                        <option value="this_year_4th_quarter" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='this_year_4th_quarter'?'selected':''}}>{{translate('this_year_4th_quarter')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="">
                                @if(count($graph_data['search_volume']) < 1 && count($graph_data['top_customers']) < 1)
                                    <span>{{translate('No data available')}}</span>
                                @endif
                                <div id="apex_donut-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between gap-3">
                                <div class="">
                                    <h4 class="mb-1">{{translate('Top Services')}}</h4>
                                    <p class="fs-12">{{translate('According to search volume')}}</p>
                                </div>
                                <div class="select-wrap d-flex flex-wrap gap-10">
                                    <select class="js-select min-w180 h-30 top-services__select">
                                        <option value="" disabled selected>{{translate('Select_Date_Range')}}</option>
                                        <option value="all_time" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='all_time'?'selected':''}}>{{translate('All_Time')}}</option>
                                        <option value="this_week" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='this_week'?'selected':''}}>{{translate('This_Week')}}</option>
                                        <option value="last_week" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='last_week'?'selected':''}}>{{translate('Last_Week')}}</option>
                                        <option value="this_month" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='this_month'?'selected':''}}>{{translate('This_Month')}}</option>
                                        <option value="last_month" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='last_month'?'selected':''}}>{{translate('Last_Month')}}</option>
                                        <option value="last_15_days" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='last_15_days'?'selected':''}}>{{translate('Last_15_Days')}}</option>
                                        <option value="this_year" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='this_year'?'selected':''}}>{{translate('This_Year')}}</option>
                                        <option value="last_year" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='last_year'?'selected':''}}>{{translate('Last_Year')}}</option>
                                        <option value="last_6_month" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='last_6_month'?'selected':''}}>{{translate('Last_6_Month')}}</option>
                                        <option value="this_year_1st_quarter" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='this_year_1st_quarter'?'selected':''}}>{{translate('This_Year_1st_Quarter')}}</option>
                                        <option value="this_year_2nd_quarter" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='this_year_2nd_quarter'?'selected':''}}>{{translate('This_Year_2nd_Quarter')}}</option>
                                        <option value="this_year_3rd_quarter" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='this_year_3rd_quarter'?'selected':''}}>{{translate('This_Year_3rd_Quarter')}}</option>
                                        <option value="this_year_4th_quarter" {{array_key_exists('date_range_2', $query_params) && $query_params['date_range_2']=='this_year_4th_quarter'?'selected':''}}>{{translate('this_year_4th_quarter')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="">
                                    <ul class="common-list after-none gap-10 d-flex flex-column">
                                        @forelse($top_services as $item)
                                            @if($item->service)
                                                <li>
                                                    <div class="mb-2 d-flex align-items-center justify-content-between gap-10 flex-wrap">
                                                        <span class="zone-name">{{$item->service->name}}</span>
                                                        <span class="booking-count">{{with_decimal_point(($item['total_volume']*100)/$total)}}% {{translate('search volume')}}</span>
                                                    </div>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: {{with_decimal_point(($item['total_volume']*100)/$total)}}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </li>
                                            @endif
                                        @empty
                                            <li>
                                                <div class="mb-2 d-flex align-items-center justify-content-between gap-10 flex-wrap">
                                                    <span class="zone-name">{{translate('No data available')}}</span>
                                                </div>
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                        <form action="{{url()->current()}}" class="search-form search-form_style-two" method="GET">
                            <div class="input-group search-form__input_group">
                                <span class="search-form__icon">
                                    <span class="material-icons">search</span>
                                </span>
                                <input type="search" class="theme-input-style search-form__input"
                                       value="{{$search??''}}" name="search"
                                       placeholder="{{translate('search_by_Customer')}}">
                            </div>
                            <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="text-nowrap">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('Customer')}}</th>
                                <th>{{translate('Search')}} <br> {{translate('Volume')}}</th>
                                <th>{{translate('Related')}} <br> {{translate('Services')}}</th>
                                <th>{{translate('Times Service')}} <br> {{translate('Visited')}}</th>
                                <th>{{translate('Times Added')}} <br> {{translate('to Cart')}}</th>
                                <th>{{translate('Total')}} <br> {{translate('Booking')}}</th>
                                <th class="text-center">{{translate('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($customers as $key=>$customer)
                                <tr>
                                    <td>{{$customers->firstitem()+$key}}</td>
                                    <td>
                                        <div class="media align-items-center gap-3">
                                            <div class="avatar avatar-lg">
                                                <a href="{{route('admin.customer.detail',[$customer->id, 'web_page'=>'overview'])}}">
                                                    <img class="avatar-img radius-5"
                                                         src="{{asset('storage/app/public/user/profile_image')}}/{{$customer->profile_image}}"
                                                         onerror="this.src='{{asset('public/assets/admin-module')}}/img/placeholder.png'"
                                                         alt="">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <h5>
                                                    <a href="{{route('admin.customer.detail',[$customer->id, 'web_page'=>'overview'])}}">
                                                        {{$customer['first_name'].' '.$customer['last_name']}}
                                                    </a>
                                                </h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{$customer->total_volume??0}}</td>
                                    <td>{{$customer->total_response_data_count??0}}</td>
                                    <td>{{$customer->total_visited_service_count??0}}</td>
                                    <td>{{$customer->total_added_to_cart_count??0}}</td>
                                    <td>{{$customer->bookings_count??0}}</td>
                                    <td>
                                        <div class="table-actions d-flex justify-content-center">
                                            <a href="{{route('admin.customer.detail',[$customer->id, 'web_page'=>'overview'])}}" type="button" class="table-actions_view">
                                                <span class="material-icons">visibility</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center"><td colspan="8">{{translate('No_data_available')}}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {!! $customers->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/admin-module')}}/plugins/apex/apexcharts.min.js"></script>
    <script>
        var options = {
            series: @json($graph_data['search_volume']),
            chart: {
                type: 'donut',
                width: "100%",
                height: 400
            },
            labels: @json(count($graph_data['top_customers']) > 0 ? $graph_data['top_customers'] : ''),
            legend: {
                show: true,
                floating: false,
                fontSize: '14px',
                position: 'right',
                horizontalAlign: 'center',
                offsetY: -10,
                itemMargin: {
                    horizontal: 5,
                    vertical: 10
                },
            },
            responsive: [{
                breakpoint: 1400,
                options: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#apex_donut-chart"), options);
        chart.render();
    </script>

    <script>
        $(".top-customers__select").on('change', function () {
            if (this.value !== "") location.href = "{{route('admin.analytics.search.customer')}}" + '?date_range=' + this.value + '&date_range_2=' + '{{$query_params['date_range_2']??'all_time'}}';
        });
        $(".top-services__select").on('change', function () {
            if (this.value !== "") location.href = "{{route('admin.analytics.search.customer')}}" + '?date_range=' + '{{$query_params['date_range']??'all_time'}}' + '&date_range_2=' + this.value;
        });
    </script>
@endpush
