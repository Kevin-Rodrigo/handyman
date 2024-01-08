@extends('providermanagement::layouts.master')

@section('title',translate('Commission_Info'))

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
                           href="{{url()->current()}}?page_type=overview">{{translate('Overview')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$page_type=='commission-info'?'active':''}}"
                           href="{{url()->current()}}?page_type=commission-info">{{translate('Commission_Info')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$page_type=='review'?'active':''}}"
                           href="{{url()->current()}}?page_type=review">{{translate('Reviews')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$page_type=='promotional_cost'?'active':''}}"
                           href="{{url()->current()}}?page_type=promotional_cost">{{translate('Promotional_Cost')}}</a>
                    </li>
                </ul>
            </div>
            <!-- End Nav Tabs -->

            <!-- Tab Content -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="settings-tab-pane">
                    <div class="card">
                        <div class="card-body p-30">
                            <div class="discount-type">
                                <div class="my-4">
                                    <strong>{{translate('Commission_Percentage')}} : {{ $commission }} %</strong>
                                </div>

                                <div class="mb-30">
                                    <span>{{translate('Currently_you_are_using_business_commission_percentage_set_by_admin._If_you_want_to_change_the_percentage_please_contact_with_business_admin.')}}</span>
                                </div>
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


@endpush
