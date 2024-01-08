@extends('providermanagement::layouts.master')

@section('title',translate('Transaction_Report'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Transaction_Reports')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 fz-16">{{translate('Search_Data')}}</div>

                            <form action="{{route('provider.report.transaction', ['transaction_type'=>$query_params['transaction_type']])}}" method="POST">
                                @csrf
                                <div class="row">
{{--                                    <div class="col-lg-4 col-sm-6 mb-30">--}}
{{--                                        <select class="js-select zone__select" name="zone_ids[]" multiple>--}}
{{--                                            @foreach($zones as $zone)--}}
{{--                                                <option value="{{$zone['id']}}" {{array_key_exists('zone_ids', $query_params) && in_array($zone['id'], $query_params['zone_ids']) ? 'selected' : '' }}>{{$zone['name']}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
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
                                        <button type="submit" class="btn btn--primary btn-sm">{{translate('Filter')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-3 mb-4">
                                <!-- Business Summary -->
                                <div class="statistics-card statistics-card__total-orders border flex-grow-1">`
                                    <h2>{{with_currency_symbol($account_info->received_balance + $account_info->total_withdrawn)}}</h2>
                                    <h3>{{translate('provider_Balance')}}</h3>
                                    <div class="absolute-img"  data-bs-toggle="tooltip" data-bs-title="{{translate('provider balance means total Earning of booking')}}">
                                        <img src="{{asset('public/assets/provider-module')}}/img/icons/info.svg" class="svg" alt="">
                                    </div>
                                </div>
                                <!-- End Business Summary -->

                                <!-- Business Summary -->
                                <div class="statistics-card statistics-card__ongoing border flex-grow-1">
                                    <h2>{{with_currency_symbol(($account_info->balance_pending??0))}}</h2>
                                    <h3>{{translate('Pending_Balance')}}</h3>
                                    <div class="absolute-img"  data-bs-toggle="tooltip" data-bs-title="{{translate('Pending balance means the amount requested for withdraw to admin')}}">
                                        <img src="{{asset('public/assets/provider-module')}}/img/icons/info.svg" class="svg" alt="">
                                    </div>
                                </div>
                                <!-- End Business Summary -->

                                <!-- Business Summary -->
                                <div class="statistics-card statistics-card__canceled border flex-grow-1">
                                    <h2>{{with_currency_symbol($account_info->total_withdrawn)}}</h2>
                                    <h3>{{translate('Already_withdrawn')}}</h3>
                                    <div class="absolute-img"  data-bs-toggle="tooltip" data-bs-title="{{translate('Total withdrawn means the amount provider has already withdrawn from admin which was got from digitally paid booking')}}">
                                        <img src="{{asset('public/assets/provider-module')}}/img/icons/info.svg" class="svg" alt="">
                                    </div>
                                </div>
                                <!-- End Business Summary -->

                                <!-- Business Summary -->
                                <div class="statistics-card statistics-card__subscribed-providers border flex-grow-1">
                                    <h2>{{with_currency_symbol($account_info->account_payable??0)}}</h2>
                                    <h3>{{translate('Account_Payable')}}</h3>
                                    <div class="absolute-img"  data-bs-toggle="tooltip" data-bs-title="{{translate('Account payable means the admin commission for CAS bookings that is yet to pay')}}">
                                        <img src="{{asset('public/assets/provider-module')}}/img/icons/info.svg" class="svg" alt="">
                                    </div>
                                </div>
                                <!-- End Business Summary -->

                                <!-- Business Summary -->
                                <div class="statistics-card statistics-card__ongoing border flex-grow-1">
                                    <h2>{{with_currency_symbol($account_info->account_receivable??0)}}</h2>
                                    <h3>{{translate('Account_Receivable')}}</h3>
                                    <div class="absolute-img"  data-bs-toggle="tooltip" data-bs-title="{{translate('Account receivable means booking earning by digitally paid bookings that is yet to collect from admin')}}">
                                        <img src="{{asset('public/assets/provider-module')}}/img/icons/info.svg" class="svg" alt="">
                                    </div>
                                </div>
                                <!-- End Business Summary -->
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                                <ul class="nav nav--tabs">
                                    <li class="nav-item">
                                        <a class="nav-link {{!isset($transaction_type) || $transaction_type=='all'?'active':''}}"
                                           href="{{url()->current()}}?transaction_type=all">{{translate('All')}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{isset($transaction_type) && $transaction_type=='debit'?'active':''}}"
                                           href="{{url()->current()}}?transaction_type=debit">{{translate('Debit')}}</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{isset($transaction_type) && $transaction_type=='credit'?'active':''}}"
                                           href="{{url()->current()}}?transaction_type=credit">{{translate('Credit')}}</a>
                                    </li>
                                </ul>

                                <div class="d-flex gap-2 fw-medium">
                                    <span class="opacity-75">{{translate('Total_Transactions')}}: </span>
                                    <span class="title-color">{{$filtered_transactions->total()}}</span>
                                </div>
                            </div>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="all-tab-pane">
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
                                                       placeholder="{{translate('search by transaction ID')}}">
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
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{route('provider.report.transaction.download').'?'.http_build_query($query_params)}}">
                                                            {{translate('Excel')}}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead class="text-nowrap">
                                            <tr>
                                                <th>{{translate('SL')}}</th>
                                                <th>{{translate('Transaction_ID')}}</th>
                                                <th>{{translate('Transaction_Date')}}</th>
                                                <th>{{translate('Transaction_To')}}</th>
                                                <th>{{translate('Debit')}}</th>
                                                <th>{{translate('Credit')}}</th>
                                                <th>{{translate('Balance')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($filtered_transactions as $key=>$transaction)
                                                <tr>
                                                    <td>{{$filtered_transactions->firstitem()+$key}}</td>
                                                    <td>{{$transaction->id}}</td>
                                                    <td>{{date('d-M-Y h:ia',strtotime($transaction->created_at))}}</td>
                                                    <td>
                                                        @if(isset($transaction->to_user))
                                                            {{$transaction->to_user->first_name.' '.$transaction->to_user->last_name}}
                                                            <div class="d-flex fz-10">{{translate($transaction->trx_type)}}</div>
                                                        @else
                                                            {{translate('User_available')}}
                                                        @endif
                                                    </td>
                                                    <td> -
                                                        @if($transaction->debit > 0)
                                                            <span>{{with_currency_symbol($transaction->debit)}}</span>
                                                        @else
                                                            <span class="disabled">{{with_currency_symbol($transaction->debit)}}</span>
                                                        @endif</td>
                                                    <td>+
                                                        @if($transaction->credit > 0)
                                                            <span>{{with_currency_symbol($transaction->credit)}}</span>
                                                        @else
                                                            <span class="disabled">{{with_currency_symbol($transaction->credit)}}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($transaction->balance > 0)
                                                            <span>{{with_currency_symbol($transaction->balance)}}</span>
                                                        @else
                                                            <span class="disabled">{{with_currency_symbol($transaction->balance)}}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td class="text-center" colspan="7">{{translate('Data_not_available')}}</td></tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {!! $filtered_transactions->links() !!}
                                    </div>
                                </div>
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
            $('.zone__select').select2({
                placeholder: "{{translate('Select_zone')}}",
            });
            $('.provider__select').select2({
                placeholder: "{{translate('Select_provider')}}",
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

