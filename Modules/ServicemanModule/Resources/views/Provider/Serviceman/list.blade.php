@extends('providermanagement::layouts.master')

@section('title',translate('Serviceman_List'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Serviceman_List')}}</h2>
                    </div>

                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                        <ul class="nav nav--tabs">
                            <li class="nav-item">
                                <a class="nav-link {{$status=='all'?'active':''}}"
                                   href="{{url()->current()}}?status=all">{{translate('All')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$status=='active'?'active':''}}"
                                   href="{{url()->current()}}?status=active">{{translate('Active')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$status=='inactive'?'active':''}}"
                                   href="{{url()->current()}}?status=inactive">{{translate('Inactive')}}</a>
                            </li>
                        </ul>

                        <div class="d-flex gap-2 fw-medium">
                            <span class="opacity-75">{{translate('Total_Serviceman')}}:</span>
                            <span class="title-color">{{$servicemen->total()}}</span>
                        </div>
                    </div>


                    <div class="tab-content">
                        <div class="">
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
                                                    <span
                                                        class="material-icons">file_download</span> {{translate('download')}}
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                    <li>
                                                        <a class="dropdown-item" href="{{route('provider.serviceman.download',['search'=>$search])}}">
                                                            {{translate('excel')}}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="example" class="table align-middle">
                                            <thead>
                                            <tr>
                                                <th>{{translate('SL')}}</th>
                                                <th>{{translate('Name')}}</th>
                                                <th>{{translate('Contact_Info')}}</th>
                                                <th>{{translate('Status')}}</th>
                                                <th>{{translate('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($servicemen as $key=>$serviceman)
                                                <tr>
                                                    <td>{{$servicemen->firstitem()+$key}}</td>
                                                    <td>
                                                        <a href="{{route('provider.serviceman.show', [$serviceman->serviceman->id])}}">
                                                            {{Str::limit($serviceman->first_name, 25)}} {{Str::limit($serviceman->last_name, 15)}}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{$serviceman->email}} <br/>
                                                        {{$serviceman->phone}}
                                                    </td>
                                                    <td>
                                                        <label class="switcher">
                                                            <input class="switcher_input"
                                                                   onclick="route_alert('{{route('provider.serviceman.status-update',[$serviceman->id])}}','{{translate('want_to_update_status')}}')"
                                                                   type="checkbox" {{$serviceman->is_active?'checked':''}}>
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="table-actions">
                                                            <a href="{{route('provider.serviceman.edit', [$serviceman->serviceman->id])}}"
                                                               class="table-actions_edit">
                                                                <span class="material-icons">edit</span>
                                                            </a>
                                                            <a href="{{route('provider.serviceman.show', [$serviceman->serviceman->id])}}"
                                                               class="table-actions_view">
                                                                <span class="material-icons">visibility</span>
                                                            </a>
                                                            <button type="button"
                                                                    onclick="form_alert('delete-{{$serviceman->serviceman->id}}','{{translate('want_to_delete_this_serviceman')}}?')"
                                                                    class="table-actions_delete bg-transparent border-0 p-0">
                                                                <span class="material-icons">delete</span>
                                                            </button>
                                                            <form
                                                                action="{{route('provider.serviceman.delete', [$serviceman->serviceman->id])}}"
                                                                method="post"
                                                                id="delete-{{$serviceman->serviceman->id}}"
                                                                class="hidden">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {!! $servicemen->links() !!}
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
