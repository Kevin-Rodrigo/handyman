@extends('providermanagement::layouts.master')

@section('title',translate('bank_Info'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Provider_Bank_Information')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('provider.update_bank_info')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <h4 class="c1 mb-30">{{translate('general_information')}}</h4>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="bank_name"
                                                    placeholder="{{translate('Bank_Name')}}"
                                                    value="{{$provider->bank_detail->bank_name??''}}" required>
                                            <label>{{translate('Bank_Name')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="branch_name"
                                                    placeholder="{{translate('Branch_Name')}}"
                                                    value="{{$provider->bank_detail->branch_name??''}}" required>
                                            <label>{{translate('Branch_Name')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="acc_no"
                                                    placeholder="{{translate('Account_No')}}"
                                                    value="{{$provider->bank_detail->acc_no??''}}" required>
                                            <label>{{translate('Account_No')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="acc_holder_name"
                                                    placeholder="{{translate('A/C_Holder_Name')}}"
                                                    value="{{$provider->bank_detail->acc_holder_name??''}}" required>
                                            <label>{{translate('A/C_Holder_Name')}}</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="routing_number"
                                                    placeholder="{{translate('routing_number')}}"
                                                    value="{{$provider->bank_detail->routing_number??''}}"
                                                    required>
                                            <label>{{translate('routing_number')}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-4 flex-wrap justify-content-end">
                                    <button type="reset" class="btn btn--secondary">{{translate('Reset')}}</button>
                                    <button type="submit" class="btn btn--primary">{{translate('Submit')}}</button>
                                </div>
                            </form>
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
