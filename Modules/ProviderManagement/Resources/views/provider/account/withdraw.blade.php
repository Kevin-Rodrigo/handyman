@extends('providermanagement::layouts.master')

@section('title',translate('Withdraw'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-title-wrap mb-3">
                <h2 class="page-title">{{translate('Account_Information')}}</h2>
            </div>

            <!-- Nav Tabs -->
            <div class="mb-3">
                <ul class="nav nav--tabs nav--tabs__style2">
                    <li class="nav-item">
                        <a class="nav-link {{$page_type=='overview'?'active':''}}"
                           href="{{route('provider.account_info', ['page_type'=>'overview'])}}">{{translate('Overview')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$page_type=='commission-info'?'active':''}}"
                           href="{{route('provider.account_info', ['page_type'=>'commission-info'])}}">{{translate('Commission_Info')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$page_type=='review'?'active':''}}"
                           href="{{route('provider.account_info', ['page_type'=>'review'])}}">{{translate('Reviews')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$page_type=='promotional_cost'?'active':''}}"
                           href="{{route('provider.account_info', ['page_type'=>'promotional_cost'])}}">{{translate('Promotional_Cost')}}</a>
                    </li>
                </ul>
            </div>
            <!-- End Nav Tabs -->

            <!-- Tab Content -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="overview-tab-pane">
                    {{-- Modal --}}
                    <div class="modal fade" id="withdrawRequestModal" tabindex="-1" aria-modal="true" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{route('provider.withdraw.store')}}" method="POST">
                                    @csrf
                                    <div class="modal-body p-30">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        <h3 class="modal-body_title mb-4">{{translate('Withdraw_Request')}}</h3>

                                        <select class="js-select" id="withdraw_method" name="withdraw_method" required>
                                            <option value="" selected disabled>{{translate('Select_Withdraw_Method')}}</option>
                                            @foreach($withdrawal_methods as $item)
                                                <option value="{{$item['id']}}">{{$item['method_name']}}</option>
                                            @endforeach
                                        </select>

                                        <div id="method-filed__div">

                                        </div>

                                        <div class="form-group mt-2">
                                            <label for="wr_num" class="fz-16 c1 mb-2">{{translate('Note')}}</label>
                                            <textarea type="text" class="form-control" name="note" placeholder="{{translate('Note')}}" maxlength="255"></textarea>
                                        </div>

                                        <input type="number" class="my-3 fz-34 text-center bg-transparent border-0" step="any" name="amount"
                                               value="0" placeholder="amount" id="amount" min="{{$withdraw_request_amount['minimum']}}" max="{{$withdraw_request_amount['maximum']}}"
                                                >
                                        <label class="my-3 fz-34 text-left">$</label>

                                        <div class="fz-15 text-muted border-bottom pb-4 text-center">
                                            <div>{{translate('Available_Balance')}} {{with_currency_symbol($collectable_cash)}}</div>

                                            <div>{{translate('Minimum_Request_Amount')}} {{with_currency_symbol($withdraw_request_amount['minimum'])}}</div>
                                            <div>{{translate('Maximum_Request_Amount')}} {{with_currency_symbol($withdraw_request_amount['maximum'])}}</div>
                                        </div>

                                        <ul class="radio-list justify-content-center mt-4">
                                            @forelse($withdraw_request_amount['random'] as $key=>$item)
                                            <li>
                                                <input type="radio" id="withdraw_amount{{$key+1}}" name="withdraw_amount" onclick="predefined_amount_input({{$item}})" hidden>
                                                <label for="withdraw_amount{{$key+1}}">{{$item}}$</label>
                                            </li>
                                            @empty
                                                <li>
                                                    <input type="radio" id="withdraw_amount" name="withdraw_amount" onclick="predefined_amount_input(500)" hidden>
                                                    <label for="withdraw_amount">500$</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="withdraw_amount2" name="withdraw_amount" onclick="predefined_amount_input(1000)" hidden>
                                                    <label for="withdraw_amount2">1000$</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="withdraw_amount3" name="withdraw_amount" onclick="predefined_amount_input(2000)" hidden>
                                                    <label for="withdraw_amount3">2000$</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="withdraw_amount4" name="withdraw_amount" onclick="predefined_amount_input(5000)" hidden>
                                                    <label for="withdraw_amount4">5000$</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="withdraw_amount5" name="withdraw_amount" onclick="predefined_amount_input(10000)" hidden>
                                                    <label for="withdraw_amount5">10000$</label>
                                                </li>
                                            @endforelse
                                        </ul>

                                        <div class="modal-body_btns d-flex justify-content-center mt-4">
                                            <button type="submit" class="btn btn--primary">{{translate('Send_Withdraw_Request')}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- new design end -->

                    <div class="card">
                        <div class="card-body">
                            <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                <form action="{{url()->current()}}"
                                      class="search-form search-form_style-two"
                                      method="GET">
                                    @csrf
                                    <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                        <input type="search" class="theme-input-style search-form__input"
                                               value="{{$search}}" name="search"
                                               placeholder="{{translate('search_here')}}">
                                    </div>
                                    <button type="submit" class="btn btn--primary">
                                        {{translate('search')}}
                                    </button>
                                </form>

                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <button class="btn btn--primary" data-bs-toggle="modal" data-bs-target="#withdrawRequestModal">{{translate('Withdraw_Request')}}</button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="example" class="table align-middle">
                                    <thead class="text-nowrap">
                                        <tr>
                                            <th>{{translate('SL')}}</th>
                                            <th>{{translate('Note')}}</th>
                                            <th>{{translate('Requested_Amount')}}</th>
                                            <th>{{translate('Admin_Note')}}</th>
                                            <th>{{translate('Status')}}</th>
                                            <th>{{translate('Requested_at')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($withdraw_requests as $key=>$withdraw_request)
                                        <tr>
                                            <td>{{$withdraw_requests->firstitem()+$key}}</td>
                                            <td>{{$withdraw_request->note}}</td>
                                            <td>{{$withdraw_request->amount}}</td>
                                            <td>{{$withdraw_request->admin_note}}</td>
                                            <td>{{translate($withdraw_request->request_status)}}</td>
                                            <td>{{date('d-M-y H:i a',strtotime($withdraw_request->created_at))}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                {!! $withdraw_requests->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Tab Content -->
        </div>
    </div>
    <!-- End Main Content -->
@endsection

@push('script')
    <script>
        "use Strict"
        $('#withdraw_method').on('change', function () {
            var method_id = this.value;

            // Set header if need any otherwise remove setup part
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('provider.withdraw.method.list')}}" + "?method_id=" + method_id,
                data: {},
                processData: false,
                contentType: false,
                type: 'get',
                success: function (response) {
                    let method_fields = response.content.method_fields;
                    $("#method-filed__div").html("");
                    method_fields.forEach((element, index) => {
                        $("#method-filed__div").append(`
                        <div class="form-group mt-2">
                            <label for="wr_num" class="fz-16 c1 mb-2">${element.input_name.replaceAll('_', ' ')}</label>
                            <input type="${element.input_type}" class="form-control" name="${element.input_name}" placeholder="${element.placeholder}" ${element.is_required === 1 ? 'required' : ''}>
                        </div>
                    `);
                    })

                },
                error: function () {

                }
            });
        });

        function predefined_amount_input(amount) {
            document.getElementById("amount").value = amount;
        }
    </script>

@endpush
