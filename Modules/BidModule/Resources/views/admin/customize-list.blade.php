@extends('adminmodule::layouts.master')

@section('title', translate('Request_List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Page Title -->
                <div class="page-title-wrap mb-3">
                    <h2 class="page-title">{{translate('Customized Booking Requests')}}</h2>
                </div>

                <!-- Tab Menu -->
                <div
                    class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                    <ul class="nav nav--tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{$type=='all'?'active':''}}"
                               href="{{url()->current()}}?type=all">{{translate('All')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$type=='new_booking_request'?'active':''}}"
                               href="{{url()->current()}}?type=new_booking_request">{{translate('No-Bid Request Yet')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$type=='placed_offer'?'active':''}}"
                               href="{{url()->current()}}?type=placed_offer">{{translate('Already Bid Requested')}}</a>
                        </li>
                    </ul>

                    <div class="d-flex gap-2 fw-medium">
                        <span class="opacity-75">{{translate('Total Customized Booking')}} : </span>
                        <span class="title-color">{{$posts->total()}}</span>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="card">
                    <div class="card-body">
                        <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                            <form action="{{url()->current()}}?type={{$type}}" method="POST"
                                  class="search-form search-form_style-two">
                                @csrf
                                <div class="input-group search-form__input_group">
                                        <span class="search-form__icon">
                                            <span class="material-icons">search</span>
                                        </span>
                                    <input type="search" class="theme-input-style search-form__input fz-10" name="search"
                                           value="{{$search??''}}" placeholder="{{translate('Search by customer info')}}">
                                </div>
                                <button type="submit" class="btn btn--primary text-capitalize">
                                    {{translate('Search')}}</button>
                            </form>

                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <div class="dropdown">
                                    <button type="button"
                                            class="btn btn--secondary text-capitalize dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                        <span class="material-icons">file_download</span> {{translate('download')}}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                        <li><a class="dropdown-item"
                                               href="{{route('admin.booking.post.export', ['type'=>$type, 'search' => $search??''])}}">{{translate('Excel')}}</a>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>

                        <div class="select-table-wrap">
                            <div class="multiple-select-actions gap-3 flex-wrap align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-wrap gap-2 gap-lg-4">
                                    <div class="ms-sm-1">
                                        <input type="checkbox" class="multi-checker">
                                    </div>
                                    <p><span class="checked-count">2</span> {{translate('Item_Selected')}}</p>
                                </div>

                                <div class="d-flex align-items-center flex-wrap gap-3">
                                    <button class="btn btn--danger" id="multi-remove">{{translate('Delete')}}</button>
                                </div>
                            </div>
                            <div class="table-responsive position-relative">
                                <table class="table align-middle multi-select-table" style="min-width: 800px">
                                    <thead>
                                    <tr>
                                        @if($type == 'new_booking_request')
                                            <th></th>
                                        @endif
                                        @if($type != 'new_booking_request')
                                            <th>{{translate('Booking ID')}}</th>
                                        @endif
                                        <th>{{translate('Customer Info')}}</th>
                                        <th>{{translate('Booking Request Time')}}</th>
                                        <th>{{translate('Service Time')}}</th>
                                        <th>{{translate('Category')}}</th>
                                        <th>{{translate('Provider Offering')}}</th>
                                        <th class="text-center">{{translate('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($posts as $key=>$post)
                                        <tr>
                                            @if($type == 'new_booking_request')
                                                <td><input type="checkbox" class="multi-check" value="{{$post->id}}"></td>
                                            @endif
                                            @if($type != 'new_booking_request')
                                                @if($post->booking)
                                                    <td>
                                                        <a href="{{route('admin.booking.details', [$post?->booking->id,'web_page'=>'details'])}}">{{$post?->booking->readable_id}}</a>
                                                    </td>
                                                @else
                                                    <td><small
                                                            class="badge-pill badge-primary">{{translate('Not Booked Yet')}}</small>
                                                    </td>
                                                @endif
                                            @endif
                                            <td>
                                                @if($post->customer)
                                                    <div>
                                                        <div class="customer-name fw-medium">
                                                            {{$post->customer?->first_name.' '.$post->customer?->last_name}}
                                                        </div>
                                                        <a href="tel:{{$post->customer?->phone}}"
                                                        class="fs-12">{{$post->customer?->phone}}</a>
                                                    </div>
                                                @else
                                                    <div><small
                                                            class="disabled">{{translate('Customer not available')}}</small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <div>{{$post->created_at->format('Y-m-d')}}</div>
                                                    <div>{{$post->created_at->format('h:ia')}}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <div>{{date('d-m-Y',strtotime($post->booking_schedule))}}</div>
                                                    <div>{{date('h:ia',strtotime($post->booking_schedule))}}</div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($post->category)
                                                    {{$post->category?->name}}
                                                @else
                                                    <div><small
                                                            class="disabled">{{translate('Category not available')}}</small>
                                                    </div>
                                                @endif
                                            </td>
                                            @php($bids = $post->bids)
                                            <td>
                                                <div class="dropdown-hover">
                                                    <div class="dropdown-hover-toggle"
                                                        data-bs-toggle="dropdown">
                                                        {{$bids->count() ?? 0}} {{translate('Providers')}}
                                                    </div>

                                                    @if($bids->count() > 0)
                                                        <ul class="dropdown-hover-menu">
                                                            @foreach($bids as $bid)
                                                                <li>
                                                                    <div class="media gap-3">
                                                                        <div class="avatar border rounded"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#providerInfoModal--{{$bid->id}}">
                                                                            <img
                                                                                src="{{asset('storage/app/public/provider/logo')}}/{{ $bid->provider?->logo }}"
                                                                                onerror="this.src='{{asset('public/assets/admin-module')}}/img/placeholder.png'"
                                                                                class="rounded" alt="">
                                                                        </div>
                                                                        <div class="media-body">
                                                                            @if($bid->provider)
                                                                                <h5 data-bs-toggle="modal"
                                                                                    data-bs-target="#providerInfoModal--{{$bid->id}}">{{$bid->provider->company_name}}</h5>
                                                                            @else
                                                                                <small>{{translate('Provider not available')}}</small>
                                                                            @endif
                                                                            <div
                                                                                class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                                            <span
                                                                                                class="text-danger">{{translate('price offered')}}</span>
                                                                                <h5 class="text-primary">{{with_currency_symbol($bid->offered_price)}}</h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="d-flex justify-content-center">
                                                <a href="{{route('admin.booking.post.details', [$post->id])}}"
                                                type="button"
                                                class="p-2 text-primary">
                                                    <span class="material-icons">visibility</span>
                                                </a>

                                                @if(!$post->booking)
                                                    <a type="button" class="p-2 text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteRequestModal--{{$post['id']}}">
                                                        <span class="material-icons">delete</span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Delete Request Modal -->
                                        <div class="modal fade" id="deleteRequestModal--{{$post['id']}}" tabindex="-1"
                                            aria-labelledby="deleteRequestModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0 pb-0">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="d-flex flex-column gap-2 align-items-center">
                                                            <img width="75" class="mb-2"
                                                                src="{{asset('public/assets/provider-module')}}/img/media/delete.png"
                                                                alt="">
                                                            <h3>{{translate('Are you sure you want to delete this request')}}
                                                                ?</h3>
                                                            <div
                                                                class="text-muted fs-12">{{translate('Providers will lose the customer booking request')}}</div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="modal-footer d-flex justify-content-center gap-3 border-0 pt-0 pb-4">
                                                        <button type="button" class="btn btn--secondary"
                                                                data-bs-dismiss="modal"
                                                                aria-label="Close">{{translate('Cancel')}}</button>
                                                        <a href="{{route('admin.booking.post.delete', [$post->id])}}"
                                                        type="button"
                                                        class="btn btn-danger">{{translate('Delete')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Provider Info Modal -->
                                            @foreach($bids as $bid)
                                                <div class="modal fade"
                                                    id="providerInfoModal--{{$bid->id}}" tabindex="-1"
                                                    aria-labelledby="providerInfoModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg"
                                                        style="--bs-modal-width: 630px">
                                                        <div class="modal-content">
                                                            <div class="modal-header px-sm-4">
                                                                <h4 class="modal-title text-primary"
                                                                    id="providerInfoModalLabel">{{translate('Provider Information')}}</h4>
                                                                <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body pb-4 px-lg-4">
                                                                <div
                                                                    class="media flex-column flex-sm-row flex-wrap gap-3">
                                                                    <img width="173" class="radius-10"
                                                                        src="{{asset('storage/app/public/provider/logo')}}/{{$bid?->provider?->logo}}"
                                                                        onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                                                        alt="">
                                                                    <div class="media-body">
                                                                        <h5 class="fw-medium mb-1">{{$bid->provider?->company_name}}</h5>
                                                                        <div
                                                                            class="fs-12 d-flex flex-wrap align-items-center gap-2 mt-1">
                                                                            <span
                                                                                class="common-list_rating d-flex gap-1">
                                                                                <span
                                                                                    class="material-icons text-primary fs-12">star</span>
                                                                                {{$bid->provider?->avg_rating}}
                                                                            </span>
                                                                            <span>{{$bid->provider?->rating_count}} {{translate('Reviews')}}</span>
                                                                        </div>

                                                                        <div
                                                                            class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1 mb-3">
                                                                            <span
                                                                                class="text-danger">{{translate('Price Offered')}}</span>
                                                                            <h4 class="text-primary">{{with_currency_symbol($bid->offered_price)}}</h4>
                                                                        </div>

                                                                        <h3 class="text-muted mb-2">{{translate('Description')}}
                                                                            :</h3>
                                                                        <p>{{$bid->service_description}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                    @empty
                                        <tr class="text-center">
                                            <td colspan="7">{{translate('No data available')}}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            {!! $posts->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#multi-remove').on('click', function() {
            var request_ids = [];
            $('input:checkbox.multi-check').each(function () {
                if(this.checked) {
                    request_ids.push( $(this).val() );
                }
            });

            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: "{{translate('Do you really want to remove the selected requests')}}?",
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonColor: 'var(--c2)',
                confirmButtonColor: 'var(--c1)',
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes',
                reverseButtons: true

            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.booking.post.multi-remove')}}",
                        data: {
                            post_ids: request_ids,
                        },
                        type: 'post',
                        success: function (response) {
                            toastr.success(response.message)
                            setTimeout(location.reload.bind(location), 1000);
                        },
                        error: function () {

                        }
                    });
                }
            })

        });
    </script>
@endpush
