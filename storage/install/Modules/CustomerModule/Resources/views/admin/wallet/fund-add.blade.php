@extends('adminmodule::layouts.master')

@section('title',translate('Add_fund'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('add_Fund')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body p-30">
                            <form action="{{route('admin.customer.wallet.add-fund')}}" method="post" enctype="multipart/form-data"
                                  id="customer-fund-form">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-30">
                                            <select class="js-select" name="user_id" id="user_id" required>
                                                <option selected disabled>{{translate('Select_customer')}}</option>
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}" {{$user->id == old('user_id') ? 'selected' : ''}}>
                                                        {{$user->first_name.' '.$user->last_name}} ({{$user->phone}})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" name="amount" id="amount"
                                                       placeholder="{{translate('amount')}}"
                                                       required value="{{old('amount')}}"
                                                        min="0" max="99999999999999999999" step="any">
                                                <label>{{translate('Amount')}} *</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" name="reference"
                                                       placeholder="{{translate('Reference')}}"
                                                       value="{{old('reference')}}"
                                                       maxlength="100">
                                                <label>{{translate('Reference (Optional)')}}</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn--primary" type="button"
                                                    onclick="fund_alert('customer-fund-form')">
                                                {{translate('submit')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function fund_alert(id) {
            if ($("#amount").val() <= 0) {
                toastr.error("{{translate('Amount can not be less than or equal to zero')}}", {CloseButton: true, ProgressBar: true});
                return false;
            }
            var message = '{{translate('Do you really want to add fund ')}}' + $("#amount").val() + " {{currency_code()}} to " + $('#user_id').find(":selected").text();
            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: message,
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
                    $('#' + id).submit()
                }
            })
        }
    </script>
@endpush
