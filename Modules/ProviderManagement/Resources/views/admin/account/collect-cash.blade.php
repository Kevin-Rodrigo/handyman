@extends('adminmodule::layouts.master')

@section('title',translate('Collect_Cash'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-title-wrap mb-3">
                <h2 class="page-title">{{translate('Provider_Details')}}</h2>
            </div>

            <!-- Nav Tabs -->
            <div class="mb-3">
                <ul class="nav nav--tabs nav--tabs__style2">
                    <li class="nav-item">
                        <a class="nav-link {{$web_page=='overview'?'active':''}}"
                           href="{{route('admin.provider.details',[$provider_id, 'web_page'=>'overview'])}}">{{translate('Overview')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$web_page=='subscribed_services'?'active':''}}"
                           href="{{route('admin.provider.details',[$provider_id, 'web_page'=>'subscribed_services'])}}">{{translate('Subscribed_Services')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$web_page=='bookings'?'active':''}}"
                           href="{{route('admin.provider.details',[$provider_id, 'web_page'=>'bookings'])}}">{{translate('Bookings')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$web_page=='serviceman_list'?'active':''}}"
                           href="{{route('admin.provider.details',[$provider_id, 'web_page'=>'serviceman_list'])}}">{{translate('Service_Man_List')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$web_page=='settings'?'active':''}}"
                           href="{{route('admin.provider.details',[$provider_id, 'web_page'=>'settings'])}}">{{translate('Settings')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$web_page=='bank_info'?'active':''}}"
                           href="{{route('admin.provider.details',[$provider_id, 'web_page'=>'bank_information'])}}">{{translate('Bank_Information')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$web_page=='reviews'?'active':''}}"
                           href="{{route('admin.provider.details',[$provider_id, 'web_page'=>'reviews'])}}">{{translate('Reviews')}}</a>
                    </li>
                </ul>
            </div>
            <!-- End Nav Tabs -->

            <!-- Tab Content -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="overview-tab-pane">
                    <div class="card mb-30">
                        <div class="card-body p-30">
                            <form action="{{route('admin.provider.collect_cash.store')}}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" placeholder="{{translate('Amount')}}"
                                                   name="amount" min="1" step="any" required>
                                            <input type="hidden" class="form-control" name="provider_id" value="{{$provider_id}}">
                                            <label>{{translate('Amount_*')}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-30">
                                    <button type="submit" class="btn btn--primary">{{translate('Submit')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                <form action="{{url()->current()}}"
                                      class="search-form search-form_style-two"
                                      method="GET">
                                    @csrf
                                    <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                        <input type="search" class="theme-input-style search-form__input"
                                               value="{{$search}}" name="search"
                                               placeholder="{{translate('search_here')}}">
                                    </div>
                                    <button type="submit" class="btn btn--primary">
                                        {{translate('search')}}
                                    </button>
                                </form>

                            </div>

                            <div class="table-responsive">
                                <table id="example" class="table align-middle">
                                    <thead>
                                    <tr>
                                        <th>{{translate('Transaction_Id')}}</th>
                                        <th>{{translate('Transaction_Date')}}</th>
                                        <th>{{translate('Transaction_From')}}</th>
                                        <th>{{translate('Transaction_To')}}</th>
                                        <th>{{translate('Debit')}}</th>
                                        <th>{{translate('Credit')}}</th>
                                        <th>{{translate('Balance')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{$transaction->id}}</td>
                                            <td>{{$transaction->created_at}}</td>
                                            <td>{{Str::limit($transaction->from_user?$transaction->from_user->first_name.' '.$transaction->from_user->last_name:'', 30)}}</td>
                                            <td>{{Str::limit($transaction->to_user?$transaction->to_user->first_name.' '.$transaction->to_user->last_name:'', 30)}}</td>
                                            <td>{{with_currency_symbol($transaction->debit)}}</td>
                                            <td>{{with_currency_symbol($transaction->credit)}}</td>
                                            <td>{{with_currency_symbol($transaction->balance)}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {!! $transactions->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Tab Content -->
        </div>
    </div>
    <!-- End Main Content -->
@endsection

@push('script')


@endpush
