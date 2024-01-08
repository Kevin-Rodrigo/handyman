@extends('providermanagement::layouts.master')

@section('title',translate('My_Subscriptions'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('My_Subscriptions')}}</h2>
                    </div>

                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                        <ul class="nav nav--tabs">
                            <li class="nav-item">
                                <a class="nav-link {{$status == 'all' ? 'active' : ''}}"
                                   href="{{url()->current()}}?status=all">{{translate('All')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$status == 'subscribed' ? 'active' : ''}}"
                                   href="{{url()->current()}}?status=subscribed">{{translate('Subscribed_Sub_categories')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$status == 'unsubscribed' ? 'active' : ''}}"
                                   href="{{url()->current()}}?status=unsubscribed">{{translate('Unsubscribed_Sub_categories')}}</a>
                            </li>
                        </ul>

                        <div class="d-flex gap-2 fw-medium">
                            <span class="opacity-75">{{translate('Total_Sub_Categories')}}:</span>
                            <span class="title-color">{{$subscribed_sub_categories->total()}}</span>
                        </div>
                    </div>


                    <div class="tab-content">
                        <div class="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example" class="table align-middle">
                                            <thead>
                                            <tr>
                                                <th>{{translate('Sub_Category_Name')}}</th>
                                                <th>{{translate('Category')}}</th>
                                                <th>{{translate('Services')}}</th>
                                                <th>{{translate('Status')}}</th>
                                                <th class="text-center">{{translate('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($subscribed_sub_categories as $key=>$sub_category)
                                                <tr>
                                                    <td>{{ Str::limit($sub_category->sub_category['name']??translate('Unavailable'), 30) }}</td>
                                                    <td>{{ Str::limit($sub_category->category['name']??translate('Unavailable'), 30) }}</td>
                                                    <td>{{ $sub_category->sub_category->services_count ?? 0 }}</td>
                                                    <td id="td-{{$sub_category->id}}">
                                                        {{ $sub_category->is_subscribed == 1 ? translate('Subscribed') : translate('unsubscribed')}}
                                                    </td>
                                                    <td class="text-center">
                                                        <form action="javascript:void(0)" method="post" class="hide-div"
                                                              id="form-{{$sub_category->id}}">
                                                            @csrf
                                                            @method('put')
                                                            <input name="sub_category_id"
                                                                   value="{{$sub_category->sub_category_id}}">
                                                        </form>
                                                        @if($sub_category->is_subscribed == 1)
                                                            <button type="button" class="btn btn--danger"
                                                                    id="button-{{$sub_category->id}}"
                                                                    onclick="update_subscription('{{$sub_category->id}}')">
                                                                {{translate('unsubscribe')}}
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn--primary"
                                                                    id="button-{{$sub_category->id}}"
                                                                    onclick="update_subscription('{{$sub_category->id}}')">
                                                                {{translate('subscribe')}}
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {!! $subscribed_sub_categories->links() !!}
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
                    send_request(formData, id);
                }
            })
        }

        function update_view(id) {
            const subscribe_button = document.querySelector('#button-' + id);
            if (subscribe_button.classList.contains('btn--danger')) {
                subscribe_button.classList.remove('btn--danger');
                subscribe_button.classList.add('btn--primary');
                $('#button-' + id).text('{{translate('subscribe')}}')
                $('#td-' + id).text('{{translate('unsubscribed')}}')
            } else {
                subscribe_button.classList.remove('btn--primary');
                subscribe_button.classList.add('btn--danger');
                $('#button-' + id).text('{{translate('unsubscribe')}}')
                $('#td-' + id).text('{{translate('subscribed')}}')
            }
        }

        function send_request(formData, id) {
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
                    if (response.response_code === 'default_204') {
                        toastr.warning('{{translate('this_category_is_not_available_in_your_zone')}}')
                    } else {
                        toastr.success('{{translate('successfully_updated')}}')
                        update_view(id)
                    }
                },
                error: function () {

                },
                complete: function () {
                    $('.preloader').hide()
                }
            });
            return is_success;
        }
    </script>

@endpush
