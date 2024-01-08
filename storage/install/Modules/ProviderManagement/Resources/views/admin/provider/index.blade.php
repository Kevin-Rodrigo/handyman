@extends('adminmodule::layouts.master')

@section('title',translate('provider_list'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-title-wrap mb-30">
                <h2 class="page-title">{{translate('Provider_List')}}</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="row mb-4 g-4">
                        <div class="col-lg-3 col-sm-6">
                            <!-- Statistics Card -->
                            <div class="statistics-card statistics-card__total_provider">
                                <h2>{{$top_cards['total_providers']}}</h2>
                                <h3>{{translate('Total_Providers')}}</h3>
                                <img src="{{asset('public/assets/admin-module')}}/img/icons/subscribed-providers.png" class="absolute-img" alt="">
                            </div>
                            <!-- End Statistics Card -->
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <!-- Statistics Card -->
                            <div class="statistics-card statistics-card__ongoing">
                                <h2>{{$top_cards['total_onboarding_requests']}}</h2>
                                <h3>{{translate('Onboarding_Request')}}</h3>
                                <img src="{{asset('public/assets/admin-module')}}/img/icons/onboarding-request.png" class="absolute-img" alt="">
                            </div>
                            <!-- End Statistics Card -->
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <!-- Statistics Card -->
                            <div class="statistics-card statistics-card__newly_joined">
                                <h2>{{$top_cards['total_active_providers']}}</h2>
                                <h3>{{translate('Active_Providers')}}</h3>
                                <img src="{{asset('public/assets/admin-module')}}/img/icons/newly-joined.png" class="absolute-img" alt="">
                            </div>
                            <!-- End Statistics Card -->
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <!-- Statistics Card -->
                            <div class="statistics-card statistics-card__not_served">
                                <h2>{{$top_cards['total_inactive_providers']}}</h2>
                                <h3>{{translate('Inactive_Providers')}}</h3>
                                <img src="{{asset('public/assets/admin-module')}}/img/icons/not-served.png" class="absolute-img" alt="">
                            </div>
                            <!-- End Statistics Card -->
                        </div>
                    </div>
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
                    <span class="opacity-75">{{translate('Total_Providers')}}:</span>
                    <span class="title-color">{{$providers->total()}}</span>
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
                                            <span class="material-icons">file_download</span> {{translate('download')}}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                            <a class="dropdown-item" href="{{route('admin.provider.download')}}?search={{$search}}">
                                                {{translate('excel')}}
                                            </a>
                                        </ul>
                                    </div>

                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="example" class="table align-middle">
                                    <thead class="align-middle">
                                    <tr>
                                        <th>{{translate('Provider')}}</th>
                                        <th>{{translate('Contact_Info')}}</th>
                                        <th>{{translate('Total_Subscribed_Sub_Categories')}}</th>
                                        <th>{{translate('Total_Booking_Served')}}</th>
                                        <th>{{translate('Status')}}</th>
                                        <th>{{translate('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($providers as $provider)
                                        <tr>
                                            <td>
                                                <div class="media align-items-center gap-3">
                                                    <div class="avatar avatar-lg">
                                                        <a href="{{route('admin.provider.details',[$provider->id, 'web_page'=>'overview'])}}">
                                                            <img class="avatar-img radius-5"
                                                                src="{{asset('storage/app/public/provider/logo')}}/{{$provider->logo}}"
                                                                onerror="this.src='{{asset('public/assets/admin-module')}}/img/placeholder.png'"
                                                                alt="">
                                                        </a>
                                                    </div>
                                                    <div class="media-body">
                                                        <h5 class="mb-1">
                                                            <a href="{{route('admin.provider.details',[$provider->id, 'web_page'=>'overview'])}}">
                                                                {{$provider->company_name}}
                                                            </a>
                                                        </h5>
                                                        <span class="common-list_rating d-flex align-items-center gap-1">
                                                            <span class="material-icons">star</span>
                                                            {{$provider->avg_rating}}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <h5 class="mb-1">{{Str::limit($provider->contact_person_name, 30)}}</h5>
                                                    <a class="fz-12" href="mobileto:{{$provider->contact_person_phone}}">{{$provider->contact_person_phone}}</a>
                                                    <a class="fz-12" href="mobileto:{{$provider->contact_person_email}}">{{$provider->contact_person_email}}</a>
                                                </div>
                                            </td>
                                            <td>
                                                <p>{{$provider->subscribed_services_count}}</p>
                                            </td>
                                            <td>{{$provider->bookings_count}}</td>
                                            <td>
                                                <label class="switcher" data-bs-toggle="modal"
                                                       data-bs-target="#deactivateAlertModal">
                                                    <input class="switcher_input" onclick="route_alert('{{route('admin.provider.status_update', [$provider->id])}}','{{translate('want_to_update_status')}}')"
                                                           type="checkbox" {{$provider->is_active?'checked':''}}>
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="{{route('admin.provider.edit',[$provider->id])}}"
                                                       class="table-actions_edit">
                                                        <span class="material-icons">edit</span>
                                                    </a>
                                                    <button type="button"
                                                            onclick="form_alert('delete-{{$provider->id}}','{{translate('want_to_delete_this')}}?')"
                                                            class="table-actions_delete bg-transparent border-0 p-0">
                                                        <span class="material-icons">delete</span>
                                                    </button>
                                                    <form action="{{route('admin.provider.delete',[$provider->id])}}"
                                                          method="post" id="delete-{{$provider->id}}" class="hidden">
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
                                {!! $providers->links() !!}
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
