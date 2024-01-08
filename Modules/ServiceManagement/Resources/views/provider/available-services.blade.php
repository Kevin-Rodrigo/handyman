@extends('providermanagement::layouts.master')

@section('title',translate('available_services'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('All_Service')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @if(count($categories) > 0)
                            <div class="service-list-wrap">
                                <ul class="services-tab-menu">
                                    <li class="{{$active_category=='all'?'active':''}}">
                                        <a href="{{url()->current()}}?active_category=all">{{translate('all')}}</a>
                                    </li>

                                    @foreach($categories as $category)
                                        <li id="list-{{$category->id}}"
                                            class="{{$active_category==$category->id?'active':''}}">
                                            <a href="{{url()->current()}}?active_category={{$category->id}}">
                                                {{$category->name}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                @if(count($sub_categories) > 0)
                                <div class="service-list">
                                    <!-- Service List Item -->
                                    @foreach($sub_categories as $sub)
                                        <div class="service-list-item">
                                            <div class="service-img">
                                                <a href="">
                                                    <img
                                                        onerror="this.src='{{asset('public/assets/admin-module/img/media/service-details.png')}}'"
                                                        src="{{asset('storage/app/public/category')}}/{{$sub->image}}"
                                                        alt="">
                                                </a>
                                            </div>
                                            <div class="service-content">
                                                <a href="" class="service-title" data-bs-toggle="modal"
                                                   data-bs-target="#showServiceModal">
                                                    {{$sub->name}}
                                                </a>
                                                <div class="service-actions">
                                                    <button type="button" class="btn btn-link text-capitalize"
                                                        @if($sub->services_count > 0)
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modal-{{$sub->id}}"
                                                        @endif
                                                    >
                                                        <strong>{{$sub->services_count}}</strong>
                                                        {{translate('services')}}
                                                    </button>

                                                    <form action="javascript:void(0)" method="post" class="hide-div"
                                                          id="form-{{$sub->id}}">
                                                        @csrf
                                                        @method('put')
                                                        <input name="sub_category_id" value="{{$sub->id}}">
                                                    </form>

                                                    @if(in_array($sub->id,$subscribed_ids))
                                                        <button type="button" class="btn btn--danger"
                                                                id="button-{{$sub->id}}"
                                                                onclick="update_subscription('{{$sub->id}}')">
                                                            {{translate('unsubscribe')}}
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn--primary"
                                                                id="button-{{$sub->id}}"
                                                                onclick="update_subscription('{{$sub->id}}')">
                                                            {{translate('subscribe')}}
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <!-- End Service List Item -->
                                </div>
                                @else
                                    <div class="d-flex justify-content-center align-items-center h-100">
                                        <span class="text-muted">{{translate('No Sub Category Available')}}</span>
                                    </div>
                                @endif
                            </div>
                            @else
                                <span>{{translate('No_available_services')}}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($sub_categories as $sub)
        <div class="modal fade" id="modal-{{$sub->id}}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body p-30">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                        <div class="text-center">
                            <div class="img-wrap-circle mx-auto mb-20">
                                <img
                                    onerror="this.src='{{asset('public/assets/admin-module/img/media/service-details.png')}}'"
                                    src="{{asset('storage/app/public/category')}}/{{$sub->image}}"
                                    alt="">
                            </div>
                            <h4 class="mb-20">
                                <strong>( {{$sub->services_count}} )</strong>
                                {{translate('services')}}
                            </h4>
                        </div>

                        <ul class="list-unstyled d-flex flex-wrap gap-3 justify-content-center">
                            @foreach($sub->services as $service)
                                <li class="d-flex flex-column gap-2 justify-content-center"
                                    onclick="location.href='{{route('provider.service.detail',[$service->id])}}'"
                                    style="cursor: pointer">
                                    <img
                                        onerror="this.src='{{asset('public/assets/admin-module/img/media/service-details.png')}}'"
                                        src="{{asset('storage/app/public/service')}}/{{$service->thumbnail}}"
                                        class="mx-auto img-square-90" width="90" alt="">
                                    <div class="fw-medium">
                                        {{\Illuminate\Support\Str::limit($service->name,15)}}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@push('script')
    <script>
        "use strict";

        function update_subscription(id) {

            var form = $('#form-' + id)[0];
            var formData = new FormData(form);

            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: "{{translate('want_to_update_subscription')}}",
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonColor: 'var(--c2)',
                confirmButtonColor: 'var(--c1)',
                cancelButtonText: '{{translate('cancel')}}',
                confirmButtonText: '{{translate('yes')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    send_request(formData)

                    const subscribe_button = document.querySelector('#button-' + id);
                    if (subscribe_button.classList.contains('btn--danger')) {
                        subscribe_button.classList.remove('btn--danger');
                        subscribe_button.classList.add('btn--primary');
                        $('#button-' + id).text('{{translate('subscribe')}}')
                    } else {
                        subscribe_button.classList.remove('btn--primary');
                        subscribe_button.classList.add('btn--danger');
                        $('#button-' + id).text('{{translate('unsubscribe')}}')
                    }
                }
            })
        }

        function send_request(formData) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('provider.service.update-subscription')}}",
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                beforeSend: function () {
                    $('.preloader').show()
                },
                success: function (response) {
                    console.log(response)
                    toastr.success('{{translate('successfully_updated')}}')
                },
                error: function () {

                },
                complete: function () {
                    $('.preloader').hide()
                }
            });
        }
    </script>
@endpush
