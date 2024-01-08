@extends('adminmodule::layouts.master')

@section('title',translate('Request_Details'))

@push('css_or_js')

@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Page Title -->
                <div class="page-title-wrap mb-3 d-flex justify-content-between">
                    <h2 class="page-title">{{translate('Request Details')}}</h2>

                    <div><i class="material-icons" data-bs-toggle="modal" data-bs-target="#alertModal">info</i></div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="card bg-primary-light shadow-none">
                                    <div class="card-body pb-5">
                                        <div class="media flex-wrap gap-3">
                                            <img width="140" class="radius-10"
                                                 src="{{asset('storage/app/public/user/profile_image')}}/{{$post?->customer?->profile_image}}"
                                                 onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                                 alt="">
                                            <div class="media-body">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <span class="material-icons text-primary">person</span>
                                                    <h4>{{translate('Customer Information')}}</h4>
                                                </div>
                                                <h5 class="text-primary mb-1">{{$post?->customer?->first_name.' '.$post?->customer?->last_name}}</h5>
                                                <p class="text-muted fs-12">
                                                    @if($distance)
                                                        {{$distance}} {{translate('away from you')}}
                                                    @endif
                                                </p>
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <span class="material-icons">phone_iphone</span>
                                                    <a href="tel:{{$post?->customer?->phone}}">{{$post?->customer?->phone}}</a>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="material-icons">map</span>
                                                    <p>{{Str::limit($post?->service_address?->address??translate('not_available'), 100)}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card bg-primary-light shadow-none">
                                    <div class="card-body pb-5">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <img width="18"
                                                 src="{{asset('public/assets/provider-module')}}/img/media/more-info.png"
                                                 alt="">
                                            <h4>{{translate('Service Information')}}</h4>
                                        </div>
                                        <div class="media gap-2 mb-4">
                                            <img width="30"
                                                 src="{{asset('storage/app/public/category')}}/{{$post?->sub_category?->image}}"
                                                 onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                                 alt="">
                                            <div class="media-body">
                                                <h5>{{$post?->service?->name}}</h5>
                                                <div class="text-muted fs-12">{{$post?->sub_category?->name}}</div>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-column gap-2">
                                            <div class="fw-medium">{{translate('Booking Request Time')}} : <span
                                                    class="fw-bold">{{$post->created_at->format('d/m/Y h:ia')}}</span>
                                            </div>
                                            <div class="fw-medium">{{translate('Service Time')}} : <span
                                                    class="fw-bold">{{date('d/m/Y h:ia',strtotime($post->booking_schedule))}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div
                                        class="card-header d-flex align-items-center gap-2 bg-primary-light shadow-none">
                                        <img width="18"
                                             src="{{asset('public/assets/provider-module')}}/img/icons/instruction.png"
                                             alt="">
                                        <h5 class="text-uppercase">{{translate('Additional Instruction')}}</h5>
                                    </div>
                                    <div class="card-body pb-4">
                                        <ul class="d-flex flex-column gap-3 px-3" style="max-width: 340px">
                                            @forelse($post?->addition_instructions as $item)
                                                <li>{{$item->details}}</li>
                                            @empty
                                                <span>{{translate('No_Addition_Instructions')}}</span>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div
                                        class="card-header d-flex align-items-center gap-2 bg-primary-light shadow-none">
                                        <img width="18"
                                             src="{{asset('public/assets/provider-module')}}/img/icons/edit-info.png"
                                             alt="">
                                        <h5 class="text-uppercase">{{translate('Service Description')}}</h5>
                                    </div>
                                    <div class="card-body pb-4">
                                        <p>{{$post->service_description}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div
                                        class="card-header d-flex align-items-center gap-2 bg-primary-light shadow-none">
                                        <img width="18"
                                             src="{{asset('public/assets/provider-module')}}/img/icons/provider.png"
                                             alt="">
                                        <h5 class="text-uppercase">{{translate('PROVIDER OFFERING')}}</h5>
                                    </div>
                                    <div class="card-body pb-4">
                                        @forelse($post->bids as $item)
                                            <div class="d-flex justify-content-between gap-3 mb-4">
                                                <div class="media gap-3">
                                                    <div class="avatar avatar-lg">
                                                        <img
                                                            src="{{asset('storage/app/public/provider/logo')}}/{{$item?->provider?->logo}}"
                                                            onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                                            class="rounded" alt="">
                                                    </div>
                                                    <div class="media-body">
                                                        <h5>{{$item?->provider->company_name}}</h5>
                                                        <div
                                                            class="fs-12 d-flex flex-wrap align-items-center gap-2 mt-1">
                                                        <span class="common-list_rating d-flex gap-1">
                                                            <span class="material-icons text-primary fs-12">star</span>
                                                            {{$item?->provider?->avg_rating??0}}
                                                        </span>
                                                            <span>{{$item?->provider?->rating_count??0}} {{translate('Reviews')}}</span>
                                                        </div>
                                                        <div
                                                            class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                    <span
                                                                        class="text-danger">{{translate('price offered')}}</span>
                                                            <h4 class="text-primary">{{with_currency_symbol($item->offered_price??0)}}</h4>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <button class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#providerInformationModal--{{$item->provider->id}}">
                                                        <img width="24"
                                                             src="{{asset('public/assets/provider-module')}}/img/icons/chat.png"
                                                             alt="">
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="d-flex justify-content-between gap-3 mb-4">
                                                <span>{{translate('No provider offering for the post')}}</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-0 border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body pb-sm-5 px-sm-5">
                    <div class="d-flex flex-column align-items-center gap-2 text-center">
                        <img src="{{asset('public/assets/provider-module')}}/img/icons/alert.png" alt="">
                        <h3>{{translate('Alert')}}!</h3>
                        <p class="fw-medium">
                            {{translate('This request is with customized instructions. Please read the customer description and instructions thoroughly and place your pricing according to this')}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Provider Information Modal -->
    @foreach($post->bids as $item)
        <div class="modal fade" id="providerInformationModal--{{$item->provider->id}}" tabindex="-1" aria-labelledby="alertModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header pb-0 border-0">
                        <h3>{{translate('Provider Information')}}</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body pb-sm-5 px-sm-5">
                        <div class="d-flex justify-content-between gap-3 mb-4">
                            <div class="media gap-3">
                                <div class="avatar avatar-lg">
                                    <img
                                        src="{{asset('storage/app/public/provider/logo')}}/{{$item?->provider?->logo}}"
                                        onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                                        class="rounded" alt="">
                                </div>
                                <div class="media-body">
                                    <div class="d-flex justify-content-between">
                                        <h5>{{$item?->provider->company_name}}</h5>
                                        <div>{{$item->created_at->format('Y-m-d h:ia')}}</div>
                                    </div>
                                    <div class="fs-12 d-flex flex-wrap align-items-center gap-2 mt-1">
                                            <span class="common-list_rating d-flex gap-1">
                                                <span class="material-icons text-primary fs-12">star</span>
                                                {{$item?->provider?->avg_rating??0}}
                                            </span>
                                        <span>{{$item?->provider?->rating_count??0}} {{translate('Reviews')}}</span>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                        <span class="text-danger">{{translate('price offered')}}</span>
                                        <h4 class="text-primary">{{with_currency_symbol($item->offered_price??0)}}</h4>
                                    </div>

                                    <div>
                                        <span>{{translate('Description')}}:</span>
                                        <p>{{$item->provider_note}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('script')

@endpush
