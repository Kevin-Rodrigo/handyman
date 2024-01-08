@extends('adminmodule::layouts.master')

@section('title',translate('Service_Request_List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3 d-flex justify-content-between">
                        <h2 class="page-title">{{translate('Service_Request_List')}}</h2>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                        <div class="d-flex gap-2 fw-medium mb-1">
                            <span class="opacity-75">{{translate('Total Service Requests')}}:</span>
                            <span class="title-color">{{$requests->total()}}</span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body pb-5">
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
                                               placeholder="{{translate('search_by_Category')}}">
                                    </div>
                                    <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>{{translate('SL')}}</th>
                                            <th>{{translate('Category')}}</th>
                                            <th>{{translate('User')}}</th>
                                            <th>{{translate('Service Name')}}</th>
                                            <th>{{translate('Service Description')}}</th>
                                            <th>{{translate('Given feedback')}}</th>
                                            <th style="width: 10%">{{translate('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($requests as $key=>$item)
                                            <tr>
                                                <td>{{$requests->firstitem()+$key}}</td>
                                                <td>
                                                    @if($item->category)
                                                        <span>{{translate($item->category->name)}}</span>
                                                    @else
                                                        <span>{{translate('Not available')}}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->user && !$item->user->provider)
                                                        <a href="{{route('admin.customer.detail',[$item->user->id, 'web_page'=>'overview'])}}">
                                                            {{$item->user->first_name}} {{$item->user->last_name}} <span class="badge-pill badge-info p-1 rounded">{{translate('Customer')}}</span>
                                                        </a>
                                                    @endif

                                                    @if($item->user && $item->user->provider)
                                                        <a href="{{route('admin.provider.details',[$item->user->provider->id, 'web_page'=>'overview'])}}">
                                                            {{$item->user->provider->company_name}} <span class="badge-pill badge-info p-1 rounded">{{translate('Provider')}}</span>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>{{$item->service_name}}</td>
                                                <td>
                                                    <div class="max-w320 min-w180 text-justify">
                                                        {{$item->service_description}}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="max-w320 min-w180 text-justify">
                                                        {{$item->admin_feedback}}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-actions">
                                                        @if($item->status == 'approved')
                                                            <span class="badge badge-pill badge-success">{{translate('Feedback Sent')}}</span>
                                                        @elseif($item->status == 'denied')
                                                                <span class="badge badge-pill badge-danger">{{translate('Feedback Sent')}}</span>
                                                        @elseif($item->status == 'pending')
                                                            <!-- Button trigger modal -->
                                                            <button type="button" class="btn btn--primary" data-bs-toggle="modal" data-bs-target="#review-modal--{{$key}}">
                                                                {{translate('Give Feedback')}}
                                                            </button>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="review-modal--{{$key}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header align-items-start">
                                                                            <div class="d-flex flex-column gap-1">
                                                                                <h4 class="modal-title">{{translate('Admin Feedback')}}</h4>
                                                                                <div class="d-flex gap-1">
                                                                                    <h5 class="text-muted">{{translate('Category Name')}} : </h5>
                                                                                    <div class="fs-12">{{translate($item->category->name??'')}} </div>
                                                                                </div>
                                                                                <div class="d-flex gap-1">
                                                                                    <h5 class="text-muted">{{translate('Service Name')}} : </h5>
                                                                                    <div class="fs-12">{{$item->service_name}} </div>
                                                                                </div>
                                                                            </div>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>

                                                                        <form action="{{route('admin.service.request.update', [$item->id])}}" class="mt-4" method="POST">
                                                                            @csrf
                                                                            <div class="modal-body">
                                                                                <div class="form-floating mb-30">
                                                                                    <textarea class="form-control" placeholder="{{translate('Give feedback')}}" name="admin_feedback"></textarea>
                                                                                    <label for="floatingTextarea">{{translate('Give feedback')}} <small>{{translate('(optional)')}}</small></label>
                                                                                </div>

                                                                                <div class="d-flex justify-content-start">
                                                                                    <div class="form-check p-0">
                                                                                        <input class="form-check-input" type="radio" name="review_status" id="flexRadioDefault1" value="1" checked required>
                                                                                        <label class="form-check-label" for="flexRadioDefault1">{{translate('Review')}}</label>
                                                                                    </div>
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input" type="radio" name="review_status" id="flexRadioDefault2" value="0" required>
                                                                                        <label class="form-check-label" for="flexRadioDefault2">{{translate('Reject')}}</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">{{translate('Close')}}</button>
                                                                                <button type="submit" class="btn btn--primary">{{translate('Submit')}}</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center"><td colspan="6">{{translate('No data available')}}</td></tr>
                                        @endforelse
                                     </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {!! $requests->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
