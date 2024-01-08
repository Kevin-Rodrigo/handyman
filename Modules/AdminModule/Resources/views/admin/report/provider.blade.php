@extends('adminmodule::layouts.master')

@section('title',translate('Provider_Report'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Provider_Reports')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 fz-16">{{translate('Search_Data')}}</div>

                            <form action="{{url()->current()}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 mb-30">
                                        <select class="zone-select" name="zone_ids[]" multiple>
                                            @foreach($zones as $zone)
                                                <option value="{{$zone['id']}}" {{array_key_exists('zone_ids', $query_params) && in_array($zone['id'], $query_params['zone_ids']) ? 'selected' : '' }}>{{$zone['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 mb-30">
                                        <select class="provider-select" name="provider_ids[]" multiple>
                                            @foreach($providers as $provider)
                                                <option value="{{$provider['id']}}" {{array_key_exists('provider_ids', $query_params) && in_array($provider['id'], $query_params['provider_ids']) ? 'selected' : '' }}>{{$provider['company_name']}} ({{$provider['company_phone']}})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 mb-30">
                                        <select class="sub-category-select" name="sub_category_ids[]" multiple>
                                            @foreach($sub_categories as $sub_category)
                                                <option value="{{$sub_category['id']}}" {{array_key_exists('sub_category_ids', $query_params) && in_array($sub_category['id'], $query_params['sub_category_ids']) ? 'selected' : '' }}>{{$sub_category['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 mb-30">
                                        <select class="js-select" id="date-range" name="date_range">
                                            <option value="0" disabled selected>{{translate('Date_Range')}}</option>
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
                                            <option value="custom_date" {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='custom_date'?'selected':''}}>{{translate('Custom_Date')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='custom_date'?'':'d-none'}}" id="from-filter__div">
                                        <div class="form-floating mb-30">
                                            <input type="date" class="form-control" id="from" name="from" value="{{array_key_exists('from', $query_params)?$query_params['from']:''}}">
                                            <label for="from">{{translate('From')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 {{array_key_exists('date_range', $query_params) && $query_params['date_range']=='custom_date'?'':'d-none'}}" id="to-filter__div">
                                        <div class="form-floating mb-30">
                                            <input type="date" class="form-control" id="to" name="to" value="{{array_key_exists('to', $query_params)?$query_params['to']:''}}">
                                            <label for="to">{{translate('To')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn--primary btn-sm">{{translate('Submit')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                <form action="{{url()->current()}}"
                                      class="search-form search-form_style-two"
                                      method="GET">
                                    <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                        <input type="search" class="theme-input-style search-form__input"
                                               value="{{$search??''}}" name="search"
                                               placeholder="{{translate('search by provider info')}}">
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
                                            <li><a class="dropdown-item" href="{{route('admin.report.provider.download').'?'.http_build_query($query_params)}}">{{translate('Excel')}}</a></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>{{translate('SL')}}</th>
                                            <th>{{translate('Provider_Info')}}</th>
                                            <th>{{translate('Subscribed_Sub_Categories')}}</th>
                                            <th>{{translate('Service Men')}}</th>
                                            <th>{{translate('Total_Bookings')}}</th>
                                            <th>{{translate('Total_Earnings')}}</th>
                                            <th>{{translate('Commission_Given')}}</th>
                                            <th>{{translate('Completion_Rate')}}</th>
                                            <th>{{translate('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($filtered_providers as $key=>$provider)
                                        <tr>
                                            <td>{{$filtered_providers->firstitem()+$key}}</td>
                                            <td>
                                                <h5 class="fw-medium mb-1">
                                                    <a href="{{route('admin.provider.details',[$provider->id, 'web_page'=>'overview'])}}">
                                                        {{$provider['company_name']}}
                                                    </a>
                                                </h5>
                                                <span class="common-list_rating d-flex align-items-center gap-1">
                                                    <span class="material-icons">star</span>
                                                    {{$provider['avg_rating']}}
                                                </span>
                                            </td>
                                            <td>{{$provider->subscribed_services_count}}</td>
                                            <td>{{$provider->servicemen_count}}</td>
                                            <td>{{$provider->bookings_count}}</td>
                                            <td>{{with_currency_symbol($provider->owner->account->received_balance +  + $provider->owner->account->total_withdrawn)}}</td>
                                            <td>
                                                @php($commissions = [])
                                                @foreach($provider->owner->transactions_for_from_user as $transaction)
                                                    @php($commissions[] = $transaction['debit']+$transaction['credit'])
                                                @endforeach
                                                <br/>
                                                {{ with_currency_symbol(array_sum($commissions)) }}
                                            </td>
                                            <td>
                                                @if($provider->bookings_count == 0)
                                                    0%
                                                @elseif($provider->incomplete_bookings_count == 0)
                                                    100%
                                                @else
                                                    @php($completion_rate = 100 - ($provider->incomplete_bookings_count*100)/$provider->bookings_count )
                                                    {{ number_format($completion_rate, 2) }}%
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('admin.provider.details',[$provider->id, 'web_page'=>'overview'])}}"
                                                   class="btn btn--light-primary px-3"><span class="material-icons m-0">visibility</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td class="text-center" colspan="9">{{translate('Data_not_available')}}</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {!! $filtered_providers->links() !!}
                            </div>
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
        $('.zone-select').select2({
            placeholder: "{{translate('Select_zone')}}",
        });
        $('.provider-select').select2({
            placeholder: "{{translate('Select_provider')}}",
        });
        $('.sub-category-select').select2({
            placeholder: "{{translate('Select_sub_category')}}",
        });
    });

    $(document).ready(function () {
        $('#date-range').on('change', function() {
            //show 'from' & 'to' div
            if(this.value === 'custom_date') {
                $('#from-filter__div').removeClass('d-none');
                $('#to-filter__div').removeClass('d-none');
            }

            //hide 'from' & 'to' div
            if(this.value !== 'custom_date') {
                $('#from-filter__div').addClass('d-none');
                $('#to-filter__div').addClass('d-none');
            }
        });
    });
</script>
@endpush
