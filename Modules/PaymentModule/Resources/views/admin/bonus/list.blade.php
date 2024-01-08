@extends('adminmodule::layouts.master')

@section('title',translate('bonus_list'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/select.dataTables.min.css"/>
@endpush

@section('content')
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div
                    class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                    <h2 class="page-title">{{translate('bonus_list')}}</h2>
                    <div class="d-flex gap-3 justify-content-end text-primary fw-bold">
                        {{translate('How_it_Works')}} <i class="material-icons" data-bs-toggle="tooltip" title="Info" id="hoverButton">info</i>
                    </div>
                </div>

                <div
                    class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                    <ul class="nav nav--tabs">
                        <li class="nav-item">
                            <a class="nav-link {{$status=='all'?'active':''}}"
                               href="{{url()->current()}}?status=all">
                                {{translate('all')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$status=='active'?'active':''}}"
                               href="{{url()->current()}}?status=active">
                                {{translate('active')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$status=='inactive'?'active':''}}"
                               href="{{url()->current()}}?status=inactive">
                                {{translate('inactive')}}
                            </a>
                        </li>
                    </ul>

                    <div class="d-flex gap-2 fw-medium">
                        <span class="opacity-75">{{translate('total_bonuses')}}:</span>
                        <span class="title-color">{{$bonuses->total()}}</span>
                    </div>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="all-tab-pane">
                        <div class="card">
                            <div class="card-body">
                                <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                    <form action="{{url()->current()}}?status={{$status}}"
                                          class="search-form search-form_style-two"
                                          method="POST">
                                        @csrf
                                        <div class="input-group search-form__input_group">
                                        <span class="search-form__icon">
                                            <span class="material-icons">search</span>
                                        </span>
                                            <input type="search" class="theme-input-style search-form__input"
                                                   value="{{$search}}" name="search"
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
                                                <span class="material-icons">file_download</span> download
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                <a class="dropdown-item" href="{{route('admin.bonus.download')}}?search={{$search}}">
                                                    {{translate('excel')}}
                                                </a>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="example" class="table align-middle">
                                        <thead class="text-nowrap">
                                            <tr>
                                                <th>{{translate('bonus_Title')}}</th>
                                                <th>{{translate('bonus_Info')}}</th>
                                                <th>{{translate('bonus_Amount')}}</th>
                                                <th>{{translate('started_On')}}</th>
                                                <th>{{translate('expires_On')}}</th>
                                                <th>{{translate('status')}}</th>
                                                <th>{{translate('action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bonuses as $bonus)
                                            <tr>
                                                <td>
                                                    {{$bonus?->bonus_title ?? ''}}
                                                </td>
                                                <td>
                                                    <p>{{translate('minimum_Add_Amount')}} - {{ $bonus?->minimum_add_amount ?? 0}}</p>
                                                    @if ($bonus->bonus_amount_type == 'percent')
                                                    <p>{{translate('minimum_Bonus')}} -  {{$bonus?->maximum_bonus_amount ?? 0}}</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$bonus->bonus_amount_type == 'percent' ? $bonus->bonus_amount . '%' : currency_symbol() . $bonus->bonus_amount}}
                                                </td>
                                                <td>
                                                    {{$bonus?->start_date ?? ''}}
                                                </td>
                                                <td>
                                                    {{$bonus?->end_date ?? ''}}
                                                </td>
                                                <td>
                                                    <label class="switcher mx-auto" data-bs-toggle="modal"
                                                           data-bs-target="#deactivateAlertModal">
                                                        <input class="switcher_input"
                                                               onclick="route_alert('{{route('admin.bonus.status-update',[$bonus->id])}}','{{translate('want_to_update_status')}}')"
                                                               type="checkbox" {{$bonus->is_active?'checked':''}}>
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="table-actions justify-content-center">
                                                        <a href="{{route('admin.bonus.edit',[$bonus->id])}}"
                                                           class="table-actions_edit">
                                                            <span class="material-icons">edit</span>
                                                        </a>
                                                        <button type="button"
                                                                onclick="form_alert('delete-{{$bonus->id}}','{{translate('want_to_delete_this_bonus')}}?')"
                                                                class="table-actions_delete bg-transparent border-0 p-0">
                                                            <span class="material-icons">delete</span>
                                                        </button>
                                                    </div>
                                                    <form
                                                        action="{{route('admin.bonus.delete',[$bonus->id])}}"
                                                        method="post" id="delete-{{$bonus->id}}"
                                                        class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    {!! $bonuses->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addFundModal" tabindex="-1" aria-labelledby="addFundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-xl-5 text-center">
                <img width="80" class="mb-4 pb-3" src="{{asset('public/assets/admin-module/img/add_fund.png')}}" alt="">
                <h4 class="mb-3">Wallet bonus is only applicable when a customer add fund to wallet via outside payment gateway !</h4>
                <p>Customer will get extra amount to his / her wallet additionally with the amount he / she added from other payment gateways. The bonus amount will consider as admin expense</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script src="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.js-select').select2();

            $('#hoverButton').hover(function() {
            $('#addFundModal').modal('show');
        });
        });
    </script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/dataTables.select.min.js"></script>
@endpush
