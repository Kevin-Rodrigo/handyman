@extends('adminmodule::layouts.master')

@section('title',translate('withdrawal_method_list'))

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
                        <h2 class="page-title">{{translate('Withdrawal_method_List')}}</h2>
                        <a href="{{route('admin.withdraw.method.create')}}" class="btn btn--primary">+ {{translate('Add_method')}}</a>
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
                                    </div>

                                    <div class="table-responsive">
                                        <table id="example" class="table align-middle">
                                            <thead class="text-nowrap">
                                            <tr>
                                                <th>{{translate('SL')}}</th>
                                                <th>{{translate('Method_name')}}</th>
                                                <th>{{translate('Method_Fields')}}</th>
                                                <th>{{translate('Active_Status')}}</th>
                                                <th>{{translate('Default_Method')}}</th>
                                                <th>{{translate('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($withdrawal_methods as $key=>$withdrawal_method)
                                            <tr>
                                                <td>{{$withdrawal_methods->firstitem()+$key}}</td>
                                                <td>{{$withdrawal_method['method_name']}}</td>
                                                <td>
                                                    @foreach($withdrawal_method['method_fields'] as $key=>$method_field)
                                                        <span class="badge badge-success opacity-75 fz-12 border border-white">
                                                            <b>{{translate('Name')}}:</b> {{translate($method_field['input_name'])}} |
                                                            <b>{{translate('Type')}}:</b> {{ $method_field['input_type'] }} |
                                                            <b>{{translate('Placeholder')}}:</b> {{ $method_field['placeholder'] }} |
                                                            <b>{{translate('Is Required')}}:</b> {{ $method_field['is_required'] ? translate('yes') : translate('no') }}
                                                        </span><br/>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <label class="switcher">
                                                        <input class="switcher_input"
                                                               onclick="route_alert_reload('{{route('admin.withdraw.method.status-update',[$withdrawal_method->id])}}','{{translate('want_to_update_status')}}')"
                                                               type="checkbox" {{$withdrawal_method->is_active?'checked':''}}>
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="switcher">
                                                        <input class="switcher_input"
                                                               onclick="route_alert_reload('{{route('admin.withdraw.method.default-status-update',[$withdrawal_method->id])}}','{{translate('want_to_make_default_method')}}')"
                                                               type="checkbox" {{$withdrawal_method->is_default?'checked':''}}>
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <div class="table-actions">
                                                        <a href="{{route('admin.withdraw.method.edit',[$withdrawal_method->id])}}"
                                                           class="table-actions_edit demo_check">
                                                            <span class="material-icons">edit</span>
                                                        </a>

                                                        @if(!$withdrawal_method->is_default)
                                                            <button type="button"
                                                                    class="table-actions_delete bg-transparent border-0 p-0 demo_check"
                                                                    data-bs-toggle="modal" data-bs-target="#deleteAlertModal"
                                                                    @if(env('APP_ENV')!='demo')
                                                                    onclick="form_alert('delete-{{$withdrawal_method->id}}','{{translate('want_to_delete_this_method')}}?')"
                                                                    @endif
                                                                >
                                                                <span class="material-icons">delete</span>
                                                            </button>
                                                            <form action="{{route('admin.withdraw.method.delete',[$withdrawal_method->id])}}"
                                                                  method="post" id="delete-{{$withdrawal_method->id}}" class="hidden">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {!! $withdrawal_methods->links() !!}
                                    </div>
                                </div>
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

@endpush
