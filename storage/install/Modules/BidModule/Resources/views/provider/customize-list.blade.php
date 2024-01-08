@extends('providermanagement::layouts.master')

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
                               href="{{url()->current()}}?type=new_booking_request">{{translate('New Offer Requests')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$type=='placed_offer'?'active':''}}"
                               href="{{url()->current()}}?type=placed_offer">{{translate('My Bid requests')}}</a>
                        </li>
                    </ul>

                    <div class="d-flex gap-2 fw-medium">
                        <span class="opacity-75">{{translate('Total Customized Booking')}} : </span>
                        <span class="title-color">{{$posts->count()}}</span>
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
                                               href="{{route('provider.booking.post.export', ['type'=>$type, 'search' => $search??''])}}">{{translate('Excel')}}</a>
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
                                    <button class="btn btn--danger" id="multi-ignore">{{translate('Ignore')}}</button>
                                </div>
                            </div>
                            <div class="table-responsive position-relative">
                                <table class="table align-middle  multi-select-table" style="min-width: 800px; min-height: 220px">
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
                                        @if($bid_offers_visibility_for_providers)
                                            <th>{{translate('Other Provider Offering')}}</th>
                                        @endif
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
                                                        <a href="{{route('provider.booking.details', [$post?->booking->id,'web_page'=>'details'])}}">{{$post?->booking->readable_id}}</a>
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
                                                    <div><small class="disabled">
                                                            {{translate('Category not available')}}</small></div>
                                                @endif
                                            </td>
                                            @if($bid_offers_visibility_for_providers)
                                                @php($bids = $post->bids->where('provider_id', '!=', auth()->user()->provider->id))
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
                                            @endif
                                            <td>
                                                <div class="table-actions d-flex justify-content-center">
                                                    <button type="button"
                                                            class="table-actions_view action-btn"
                                                            data-bs-toggle="dropdown">
                                                        <span class="material-icons">more_horiz</span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                        <!-- View details -->
                                                        <li><a class="dropdown-item"
                                                            href="{{route('provider.booking.post.details', [$post->id])}}">{{translate('View details')}}</a>
                                                        </li>
                                                    @if($post?->bids->contains('provider_id', auth()->user()->provider->id))
                                                            <!-- See My Offer -->
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#offerDetailsModal--{{$post['id']}}">{{translate('See My Offer')}}</a>
                                                            </li>
                                                            <!-- Withdraw -->
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#withdrawRequestModal--{{$post['id']}}">{{translate('Withdraw the Offer')}}</a>
                                                            </li>


                                                    @endif

                                                    @if(!$post->is_booked && !$post?->bids->contains('provider_id', auth()->user()->provider->id))
                                                            <!-- Placed Offer -->
                                                            <li>
                                                                <button class="dropdown-item" href="#"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#newBookingModal--{{$post['id']}}">{{translate('Placed Offer')}}</button>
                                                            </li>
                                                            <!-- Ignore/Reject -->
                                                            <li>
                                                                <button class="dropdown-item" data-bs-toggle="modal"
                                                                        data-bs-target="#ignoreRequestModal--{{$post['id']}}">{{translate('Ignore/Reject')}}</button>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>


                                        <!-- Offer Details Modal -->
                                        <div class="modal fade" id="offerDetailsModal--{{$post['id']}}" tabindex="-1"
                                            aria-labelledby="offerDetailsModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog" style="--bs-modal-width: 430px">
                                                <div class="modal-content">
                                                    <div class="modal-header px-sm-4">
                                                        <h4 class="modal-title text-primary"
                                                            id="offerDetailsModalLabel">{{translate('My Offer Details')}}</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body pb-4 px-lg-4">
                                                        <div class="">

                                                            <div class="d-flex gap-4 mb-4">
                                                                <div class="media gap-2 ">
                                                                    <img width="30"
                                                                        src="{{asset('storage/app/public/category')}}/{{$post?->sub_category?->image}}"
                                                                        onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                                                        alt="">
                                                                    <div class="media-body">
                                                                        <h5>{{$post?->service?->name}}</h5>
                                                                        <div
                                                                            class="text-muted fs-12">{{$post?->sub_category?->name}}</div>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <div class=" border-start ps-4">
                                                                        <div
                                                                            class="d-flex gap-2 flex-wrap align-items-center">
                                                                            <span
                                                                                class="text-danger fs-12">{{translate('price offered')}}</span>
                                                                            <h4 class="text-primary">{{$post?->bids->where('provider_id', auth()->user()->provider->id)->first()?->offered_price ?? 0}}</h4>
                                                                        </div>
                                                                        <span
                                                                            class="text-muted fs-12">{{$post->updated_at->diffForHumans()}}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <h3 class="text-muted mb-2">{{translate('Description')}}:</h3>
                                                            <p>{{$post?->bids?->where('provider_id', auth()->user()->provider->id)?->first()?->provider_note}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- New Booking Request Modal -->
                                        <div class="modal fade" id="newBookingModal--{{$post['id']}}" tabindex="-1"
                                            aria-labelledby="newBookingModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form
                                                        action="{{route('provider.booking.post.update_status', [$post->id])}}"
                                                        method="GET">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="newBookingModalLabel">{{translate('New Booking Request Form')}}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="card border">
                                                                <div class="card-body">
                                                                    <div class="d-flex gap-4 mb-4">
                                                                        <div class="media gap-2">
                                                                            <div class="avatar avatar-lg rounded">
                                                                                <img
                                                                                    src="{{asset('storage/app/public/user/profile_image')}}/{{$post?->customer?->profile_image}}"
                                                                                    onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                                                                    alt="">
                                                                            </div>
                                                                            <div class="media-body">
                                                                                <h5 class="text-primary">{{$post?->customer?->first_name.' '.$post?->customer?->last_name}}</h5>
                                                                                <div class="text-muted fs-12">
                                                                                    @if($post->distance)
                                                                                        {{$post->distance . ' ' . translate('away from you')}}
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <div class="media gap-2 border-start ps-4">
                                                                                <img width="30"
                                                                                    src="{{asset('storage/app/public/category')}}/{{$post?->sub_category?->image}}"
                                                                                    onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                                                                    alt="">
                                                                                <div class="media-body">
                                                                                    <h5>{{$post?->service?->name}}</h5>
                                                                                    <div
                                                                                        class="text-muted fs-12">{{$post?->sub_category?->name}}</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                                        <img width="18"
                                                                            src="{{asset('public/assets/provider-module')}}/img/media/edit-info.png"
                                                                            alt="">
                                                                        <h4>{{translate('Service Requirement')}}</h4>
                                                                    </div>

                                                                    @if($post->service_description)
                                                                        <p class="fs-12">{{$post->service_description}}</p>
                                                                    @else
                                                                        <span
                                                                            class="small">{{translate('Not Available')}}</span>
                                                                    @endif

                                                                </div>
                                                            </div>

                                                            <div class="card border mt-3">
                                                                <div class="card-body">
                                                                    <div class="mb-30">
                                                                        <div class="form-floating">
                                                                            <input type="number" class="form-control"
                                                                                name="offered_price" min="{{$post?->service?->min_bidding_price??0}}" step="any"
                                                                                placeholder="{{translate('Offer Price')}}"
                                                                                id="offer-price" data-bs-toggle="tooltip"
                                                                                data-bs-placement="top"
                                                                                title="{{translate('Minimum Offer price')}} {{with_currency_symbol($post?->service?->min_bidding_price??0)}}">
                                                                            <label
                                                                                for="offer-price">{{translate('Offer Price')}}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-floating">
                                                                    <textarea class="form-control"
                                                                            placeholder="{{translate('Add Your Note')}}"
                                                                            name="provider_note"
                                                                            id="add-your-note"></textarea>
                                                                        <label for="add-your-note"
                                                                            class="d-flex align-items-center gap-1">
                                                                            {{translate('Add Your Note')}}
                                                                        </label>
                                                                        <input type="hidden" name="status" value="accept">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer d-flex justify-content-end border-0 pt-0">
                                                            <button type="submit"
                                                                    class="btn btn--primary">{{translate('Send Your Offer')}}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ignore Request Modal -->
                                        <div class="modal fade" id="ignoreRequestModal--{{$post['id']}}" tabindex="-1"
                                            aria-labelledby="ignoreRequestModalLabel"
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
                                                                src="{{asset('public/assets/provider-module')}}/img/media/ignore-request.png"
                                                                alt="">
                                                            <h3>{{translate('Are you sure you want to ignore this request')}}
                                                                ?</h3>
                                                            <div
                                                                class="text-muted fs-12">{{translate('You will lose the customer booking request')}}</div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="modal-footer d-flex justify-content-center gap-3 border-0 pt-0 pb-4">
                                                        <button type="button" class="btn btn--secondary"
                                                                data-bs-dismiss="modal"
                                                                aria-label="Close">{{translate('Cancel')}}</button>
                                                        <a href="{{route('provider.booking.post.update_status', [$post->id, 'status' => 'ignore'])}}"
                                                        type="button"
                                                        class="btn btn--primary">{{translate('Ignore')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Withdraw Request Modal -->
                                        <div class="modal fade" id="withdrawRequestModal--{{$post['id']}}" tabindex="-1"
                                            aria-labelledby="withdrawRequestModalLabel"
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
                                                                src="{{asset('public/assets/provider-module')}}/img/media/withdraw.png"
                                                                alt="">
                                                            <h3>{{translate('Are you sure you want to withdraw this offer?')}}
                                                                ?</h3>
                                                            <div
                                                                class="text-muted fs-12">{{translate('You offer will be removed for the post')}}</div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="modal-footer d-flex justify-content-center gap-3 border-0 pt-0 pb-4">
                                                        <button type="button" class="btn btn--secondary"
                                                                data-bs-dismiss="modal"
                                                                aria-label="Close">{{translate('Cancel')}}</button>
                                                        <a href="{{route('provider.booking.post.withdraw', [$post->id])}}"
                                                        type="button"
                                                        class="btn btn--primary">{{translate('Withdraw Offer')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($bid_offers_visibility_for_providers)
                                            @php($bids = $post->bids->where('provider_id', '!=', auth()->user()->provider->id))
                                            @if($bids->count() > 0)
                                                @foreach($bids as $bid)
                                                    <!-- Provider Info Modal -->
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
                                            @endif
                                        @endif

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
        $('#multi-ignore').on('click', function() {
            var request_ids = [];
            $('input:checkbox.multi-check').each(function () {
                if(this.checked) {
                    request_ids.push( $(this).val() );
                }
            });

            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: "{{translate('Do you really want to ignore the selected requests')}}?",
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
                        url: "{{route('provider.booking.post.multi-ignore')}}",
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
