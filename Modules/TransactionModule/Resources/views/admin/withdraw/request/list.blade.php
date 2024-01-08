@extends('adminmodule::layouts.master')

@section('title',translate('withdraw_request_list'))

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
                        <h2 class="page-title">{{translate('withdraw_request_list')}}</h2>
                    </div>

                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                        <ul class="nav nav--tabs">
                            <li class="nav-item">
                                <a class="nav-link {{$status=='all'?'active':''}}"
                                   href="{{url()->current()}}?status=all">
                                    {{translate('All')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$status=='pending'?'active':''}}"
                                   href="{{url()->current()}}?status=pending">
                                    {{translate('Pending')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$status=='approved'?'active':''}}"
                                   href="{{url()->current()}}?status=approved">
                                    {{translate('Approved')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$status=='denied'?'active':''}}"
                                   href="{{url()->current()}}?status=denied">
                                    {{translate('Denied')}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{$status=='settled'?'active':''}}"
                                   href="{{url()->current()}}?status=settled">
                                    {{translate('Settled')}}
                                </a>
                            </li>
                        </ul>

                        <div class="d-flex gap-2 fw-medium">
                            <span class="opacity-75">{{translate('total_withdraw')}}:</span>
                            <span class="title-color">{{$withdraw_requests->total()}}</span>
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
                                                       placeholder="{{translate('search_by_company')}}">
                                            </div>
                                            <button type="submit"
                                                    class="btn btn--primary">{{translate('search')}}</button>
                                        </form>

                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                            <button type="button" class="btn btn--success" data-bs-toggle="modal" data-bs-target="#uploadFileModal">{{translate('Bulk_Status_Update')}}</button>
                                            <div class="dropdown">
                                                <button type="button"
                                                        class="btn btn--secondary text-capitalize dropdown-toggle"
                                                        data-bs-toggle="dropdown">
                                                    <span class="material-icons">file_download</span> {{translate('download')}}
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                    <li>
                                                        <a class="dropdown-item" href="{{route('admin.withdraw.request.download', ['status'=>$status])}}">
                                                            {{translate('excel')}}
                                                        </a>
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
                                                <select class="js-select theme-input-style w-100" id="multi-status__select" required>
                                                    <option selected disabled>{{translate('Update_status')}}</option>
                                                    <option value="denied">{{translate('Deny')}}</option>
                                                    <option value="approved">{{translate('Approve')}}</option>
                                                    <option value="settled">{{translate('Settle')}}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="table-responsive position-relative">
                                            <table id="example" class="table align-middle multi-select-table">
                                                <thead class="text-nowrap">
                                                <tr>
                                                    <th></th>
                                                    <th>{{translate('SL')}}</th>
                                                    <th>{{translate('Provider')}}</th>
                                                    <th>{{translate('Amount')}}</th>
                                                    <th>{{translate('Provider_Note')}}</th>
                                                    <th>{{translate('Admin_Note')}}</th>
                                                    <th>{{translate('Request_Time')}}</th>
                                                    <th class="text-center">{{translate('Status')}}</th>
                                                    <th class="text-center">{{translate('Action')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($withdraw_requests as $key=>$withdraw_request)
                                                    <tr>
                                                        <td><input type="checkbox" class="multi-check" value="{{$withdraw_request->id}}"></td>
                                                        <td>{{$withdraw_requests->firstitem()+$key}}</td>
                                                        <td class="text-capitalize">
                                                            @if($withdraw_request->user->provider)
                                                            <a href="{{route('admin.provider.details',[$withdraw_request->user->provider->id, 'web_page'=>'overview'])}}">
                                                                {{Str::limit($withdraw_request->user->provider->company_name, 30)}}
                                                            </a>
                                                            @else
                                                                {{translate('Not_available')}}
                                                            @endif
                                                        </td>
                                                        <td>{{with_currency_symbol($withdraw_request->amount)}}</td>
                                                        <td>{{Str::limit($withdraw_request->note, 100)}}</td>
                                                        <td>{{Str::limit($withdraw_request->admin_note, 100)}}</td>
                                                        <td>{{date('d-M-y H:iA', strtotime($withdraw_request->created_at))}}</td>
                                                        <td class="text-center">
                                                            @if($withdraw_request->request_status == 'pending')
                                                                <label class="badge badge-info">{{translate('pending')}}</label>
                                                            @elseif($withdraw_request->request_status == 'approved')
                                                                <label class="badge badge-success">{{translate('approved')}}</label>
                                                            @elseif($withdraw_request->request_status == 'settled')
                                                                <label class="badge badge-success">{{translate('Settled')}}</label>
                                                            @elseif($withdraw_request->request_status == 'denied')
                                                                <label class="badge badge-danger">{{translate('denied')}}</label>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-2 justify-content-center">
                                                                @if($withdraw_request->request_status=='pending')
                                                                    <!-- DENY -->
                                                                    <button type="button" class="btn btn--danger" data-bs-toggle="modal" data-bs-target="#denyModal-{{$withdraw_request->id}}">
                                                                        <span class="material-icons">block</span>{{translate('Deny')}}
                                                                    </button>
                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="denyModal-{{$withdraw_request->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-body">
                                                                                    <form action="{{route('admin.withdraw.request.update_status',[$withdraw_request->id, 'status'=>'denied'])}}" method="POST">
                                                                                        @csrf
                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                        <div class="text-center">
                                                                                            <img width="75" class="my-3" src="{{asset('public/assets/admin-module')}}/img/media/deny.png" alt="">
                                                                                            <h3 class="mb-3">{{translate('Deny_this_request')}}?</h3>
                                                                                        </div>

                                                                                        <div class="py-3 d-flex flex-wrap flex-md-nowrap gap-3 mb-2">
                                                                                            <!-- Customer Info -->
                                                                                            <div class="c1-light-bg radius-10 py-3 px-4 flex-grow-1">
                                                                                                <h4 class="mb-2">{{translate('Provider_Information')}}</h4>
                                                                                                @if($withdraw_request->provider)
                                                                                                    <h5 class="c1 mb-2">{{$withdraw_request->provider->company_name}}</h5>
                                                                                                    <ul class="list-info">
                                                                                                        <li>
                                                                                                            <span class="material-icons">phone_iphone</span>
                                                                                                            <a href="tel:{{$withdraw_request->provider->company_phone}}">{{$withdraw_request->provider->company_phone}}</a>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <span class="material-icons">map</span>
                                                                                                            <p>{{$withdraw_request->provider->company_address}}</p>
                                                                                                        </li>
                                                                                                    </ul>
                                                                                                @endif
                                                                                            </div>
                                                                                            <!-- End Customer Info -->

                                                                                            <!-- Lead Service Info -->
                                                                                            <div class="c1-light-bg radius-10 py-3 px-4 flex-grow-1">
                                                                                                <h4 class="mb-2">{{translate('Withdraw_Method_Information')}}</h4>
                                                                                                <ul class="list-info gap-1">
                                                                                                    @forelse($withdraw_request->withdrawal_method_fields as $key=>$value)
                                                                                                        <li>
                                                                                                            <span class="font-weight-bold"><b>{{translate($key)}}</b>: </span>
                                                                                                            <span>{{$value}}</span>
                                                                                                        </li>
                                                                                                    @empty
                                                                                                        <li><span>{{translate('Information_unavailable')}}</span></li>
                                                                                                    @endforelse
                                                                                                </ul>
                                                                                            </div>
                                                                                            <!-- End Lead Service Info -->
                                                                                        </div>

                                                                                        <textarea class="form-control h-140" placeholder="Note" name="note"></textarea>

                                                                                        <div class="mb-3 mt-4 d-flex justify-content-center gap-3">
                                                                                            <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">{{translate('Cancel')}}</button>
                                                                                            <button type="submit" class="btn btn--primary">{{translate('Yes')}}</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Modal End -->


                                                                    <!-- Approve -->
                                                                    <button type="button" class="btn btn--success" data-bs-toggle="modal" data-bs-target="#approveModal-{{$withdraw_request->id}}">
                                                                        <span class="material-icons">done_outline</span>{{translate('Approve')}}
                                                                    </button>
                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="approveModal-{{$withdraw_request->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-body">
                                                                                    <form action="{{route('admin.withdraw.request.update_status',[$withdraw_request->id, 'status'=>'approved'])}}" method="POST">
                                                                                        @csrf
                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                        <div class="text-center">
                                                                                            <img width="75" class="my-3" src="{{asset('public/assets/admin-module')}}/img/media/accept.png" alt="">
                                                                                            <h3 class="mb-3">{{translate('Accept_this_request')}}?</h3>
                                                                                        </div>

                                                                                        <div class="py-3 d-flex flex-wrap flex-md-nowrap gap-3 mb-2">
                                                                                            <!-- Customer Info -->
                                                                                            <div class="c1-light-bg radius-10 py-3 px-4 flex-grow-1">
                                                                                                <h4 class="mb-2">{{translate('Provider_Information')}}</h4>
                                                                                                @if($withdraw_request->provider)
                                                                                                    <h5 class="c1 mb-2">{{$withdraw_request->provider->company_name}}</h5>
                                                                                                    <ul class="list-info">
                                                                                                        <li>
                                                                                                            <span class="material-icons">phone_iphone</span>
                                                                                                            <a href="tel:{{$withdraw_request->provider->company_phone}}">{{$withdraw_request->provider->company_phone}}</a>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <span class="material-icons">map</span>
                                                                                                            <p>{{$withdraw_request->provider->company_address}}</p>
                                                                                                        </li>
                                                                                                    </ul>
                                                                                                @endif
                                                                                            </div>
                                                                                            <!-- End Customer Info -->

                                                                                            <!-- Lead Service Info -->
                                                                                            <div class="c1-light-bg radius-10 py-3 px-4 flex-grow-1">
                                                                                                <h4 class="mb-2">{{translate('Withdraw_Method_Information')}}</h4>
                                                                                                <ul class="list-info gap-1">
                                                                                                    @forelse($withdraw_request->withdrawal_method_fields as $key=>$value)
                                                                                                        <li>
                                                                                                            <span class="font-weight-bold"><b>{{translate($key)}}</b>: </span>
                                                                                                            <span>{{$value}}</span>
                                                                                                        </li>
                                                                                                    @empty
                                                                                                        <li><span>{{translate('Information_unavailable')}}</span></li>
                                                                                                    @endforelse
                                                                                                </ul>
                                                                                            </div>
                                                                                            <!-- End Lead Service Info -->
                                                                                        </div>

                                                                                        <textarea class="form-control h-140" placeholder="Note" name="note"></textarea>

                                                                                        <div class="mb-3 mt-4 d-flex justify-content-center gap-3">
                                                                                            <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">{{translate('Cancel')}}</button>
                                                                                            <button type="submit" class="btn btn--primary">{{translate('Yes')}}</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Modal End -->

                                                                @elseif($withdraw_request->request_status=='approved')
                                                                        <!-- Settle -->
                                                                        <button type="button" class="btn btn--success" data-bs-toggle="modal" data-bs-target="#approveModal-{{$withdraw_request->id}}">
                                                                            {{translate('Settle')}}
                                                                        </button>
                                                                        <!-- Modal -->
                                                                        <div class="modal fade" id="approveModal-{{$withdraw_request->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-lg">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-body">
                                                                                        <form action="{{route('admin.withdraw.request.update_status',[$withdraw_request->id, 'status'=>'settled'])}}" method="POST">
                                                                                            @csrf
                                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                            <div class="text-center">
                                                                                                <img width="75" class="my-3" src="{{asset('public/assets/admin-module')}}/img/media/settle.png" alt="">
                                                                                                <h3 class="mb-3">{{translate('Settled_this_request')}}?</h3>
                                                                                            </div>

                                                                                            <div class="py-3 d-flex flex-wrap flex-md-nowrap gap-3 mb-2">
                                                                                                <!-- Customer Info -->
                                                                                                <div class="c1-light-bg radius-10 py-3 px-4 flex-grow-1">
                                                                                                    <h4 class="mb-2">{{translate('Provider_Information')}}</h4>
                                                                                                    @if($withdraw_request->provider)
                                                                                                        <h5 class="c1 mb-2">{{$withdraw_request->provider->company_name}}</h5>
                                                                                                        <ul class="list-info">
                                                                                                            <li>
                                                                                                                <span class="material-icons">phone_iphone</span>
                                                                                                                <a href="tel:{{$withdraw_request->provider->company_phone}}">{{$withdraw_request->provider->company_phone}}</a>
                                                                                                            </li>
                                                                                                            <li>
                                                                                                                <span class="material-icons">map</span>
                                                                                                                <p>{{$withdraw_request->provider->company_address}}</p>
                                                                                                            </li>
                                                                                                        </ul>
                                                                                                    @endif
                                                                                                </div>
                                                                                                <!-- End Customer Info -->

                                                                                                <!-- Lead Service Info -->
                                                                                                <div class="c1-light-bg radius-10 py-3 px-4 flex-grow-1">
                                                                                                    <h4 class="mb-2">{{translate('Withdraw_Method_Information')}}</h4>
                                                                                                    <ul class="list-info gap-1">
                                                                                                        @forelse($withdraw_request->withdrawal_method_fields as $key=>$value)
                                                                                                            <li>
                                                                                                                <span class="font-weight-bold"><b>{{translate($key)}}</b>: </span>
                                                                                                                <span>{{$value}}</span>
                                                                                                            </li>
                                                                                                        @empty
                                                                                                            <li><span>{{translate('Information_unavailable')}}</span></li>
                                                                                                        @endforelse
                                                                                                    </ul>
                                                                                                </div>
                                                                                                <!-- End Lead Service Info -->
                                                                                            </div>

                                                                                            <textarea class="form-control h-140" placeholder="Note" name="note"></textarea>

                                                                                            <div class="mb-3 mt-4 d-flex justify-content-center gap-3">
                                                                                                <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">{{translate('Cancel')}}</button>
                                                                                                <button type="submit" class="btn btn--primary">{{translate('Yes')}}</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Modal End -->
                                                                @elseif($withdraw_request->request_status=='denied')
                                                                    <label class="badge badge-danger">{{translate('already_denied')}}</label>
                                                                @elseif($withdraw_request->request_status=='settled')
                                                                    <label class="badge badge-success">{{translate('already_settled')}}</label>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {!! $withdraw_requests->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button trigger modal -->

        </div>
    </div>
    <!-- End Main Content -->

    <!-- Modal -->
    <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body py-5">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mw-340 mx-auto">
                        <h3 class="text-uppercase text-center mb-4">{{translate('Upload_files')}} </h3>
                        <ul class="text-start text-muted d-flex flex-column gap-2">
                            <li>{{translate('Download Excel File From Withdraw list')}}</li>
                            <li>{{translate('Update  the request status column with the request status (approved, denied, settled)')}}</li>
                        </ul>
                        <p class="title-color fz-12 mb-5">{{translate('NB: Do not modify the initial row of the excel file')}}</p>
                        <form action="{{route('admin.withdraw.request.import')}}" id="uploadProgressForm" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex justify-content-center">
                                <div class="upload-file w-auto">
                                    <input type="file" id="fileInput" class="upload-file__input" name="withdraw_request_file">
                                    <div class="upload-file__img">
                                        <img onerror="this.src='{{asset('public/assets/admin-module/img/media/upload-file.png')}}'"
                                            src="{{asset('public/assets/admin-module')}}/img/media/upload-file.png"
                                            alt="">
                                    </div>
                                    <span class="upload-file__edit">
                                        <span class="material-icons">edit</span>
                                    </span>
                                </div>
                            </div>

                            <div class="mt-5 card p-3">
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="">
                                        <img width="24" src="{{asset('public/assets/admin-module')}}/img/media/excel.png"
                                            alt="">
                                    </div>
                                    <div class="flex-grow-1 text-start">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-1">
                                            <span id="name_of_file" class="text-truncate">{{translate('file_name')}}</span>
                                            <span class="text-muted" id="progress-label">0%</span>
                                        </div>
                                        <progress id="uploadProgress" class="w-100" value="0" max="100"></progress>
                                    </div>
                                    <button type="reset" class="btn-close position-static border rounded-circle border-secondary p-2 fz-10" aria-label="Close"></button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn--primary mt-4 w-100">{{translate('Submit')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        $('#multi-status__select').change( function() {
            var request_ids = [];
            $('input:checkbox.multi-check').each(function () {
                if(this.checked) {
                    request_ids.push( $(this).val() );
                }
            });

            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: '',
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
                        url: "{{route('admin.withdraw.request.update_multiple_status')}}",
                        data: {
                            request_ids: request_ids,
                            status: $(this).val()
                        },
                        type: 'put',
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

    <script>
        $(window).on('load', function() {
            $(".upload-file__input").on("change", function () {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    let img = $(this).siblings(".upload-file__img").find('img');

                    let file = this.files[0];
                    let isImage = file['type'].split('/')[0] == 'image';

                    if (isImage) {
                        reader.onload = function (e) {
                            img.attr("src", e.target.result);
                        };
                    } else {
                        reader.onload = function (e) {
                            img.attr("src", "{{asset('public/assets/admin-module/img/media/excel.png')}}");
                        };
                    }

                    reader.readAsDataURL(file);

                    reader.addEventListener('progress', (event) => {
                        if (event.loaded && event.total) {
                            const percent = (event.loaded / event.total) * 100;
                            $('#uploadProgress').val(percent);
                            $('#progress-label').html(Math.round(percent) + '%');
                            $('#name_of_file').html(file.name);
                        }
                    });
                }
            });
        })
    </script>
@endpush
