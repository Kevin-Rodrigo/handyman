@extends('providermanagement::layouts.master')

@section('title',translate('Review'))

@section('title',translate('provider_details'))

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
            <div class="card mb-30">
                <div class="card-body p-30">
                    <div class="row">
                        <div class="col-lg-5 mb-30 mb-lg-0 d-flex justify-content-center">
                            <div class="rating-review">
                                <h2 class="rating-review__title">
                                    <span class="rating-review__out-of">{{$provider->avg_rating}}</span>/5
                                </h2>
                                <div class="rating">
                                    <span
                                        class="{{$provider->avg_rating>=1?'material-icons':'material-symbols-outlined'}}">{{$provider->avg_rating>=1?'star':'grade'}}</span>
                                    <span
                                        class="{{$provider->avg_rating>=2?'material-icons':'material-symbols-outlined'}}">{{$provider->avg_rating>=2?'star':'grade'}}</span>
                                    <span
                                        class="{{$provider->avg_rating>=3?'material-icons':'material-symbols-outlined'}}">{{$provider->avg_rating>=3?'star':'grade'}}</span>
                                    <span
                                        class="{{$provider->avg_rating>=4?'material-icons':'material-symbols-outlined'}}">{{$provider->avg_rating>=4?'star':'grade'}}</span>
                                    <span
                                        class="{{$provider->avg_rating>=5?'material-icons':'material-symbols-outlined'}}">{{$provider->avg_rating>=5?'star':'grade'}}</span>
                                </div>
                                <div class="rating-review__info d-flex flex-wrap gap-3">
                                    @php($total_review_count=$provider->reviews->count())
                                    <span>{{$provider->rating_count}} {{translate('ratings')}}</span>
                                    <span>{{$total_review_count}} {{translate('reviews')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <ul class="common-list common-list__style2 after-none gap-10">
                                <li>
                                    <span class="review-name">{{translate('excellent')}}</span>
                                    @php($excellent_count=$provider->reviews->where('review_rating',5)->count())
                                    @php($excellent=(divnum($excellent_count,$total_review_count))*100)
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar"
                                                style="width: {{$excellent}}%"
                                                aria-valuenow="{{$excellent}}" aria-valuemin="0"
                                                aria-valuemax="100">
                                        </div>
                                    </div>
                                    <span class="review-count">{{$excellent_count}}</span>
                                </li>
                                <li>
                                    <span class="review-name">{{translate('good')}}</span>
                                    @php($good_count=$provider->reviews->where('review_rating',4)->count())
                                    @php($good=(divnum($excellent_count,$total_review_count))*100)
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{$good}}%"
                                                aria-valuenow="{{$good}}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <span class="review-count">{{$good_count}}</span>
                                </li>
                                <li>
                                    <span class="review-name">{{translate('avarage')}}</span>
                                    @php($average_count=$provider->reviews->where('review_rating',3)->count())
                                    @php($average=(divnum($average_count,$total_review_count))*100)
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar"
                                                style="width: {{$average}}%"
                                                aria-valuenow="{{$average}}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <span class="review-count">{{$average_count}}</span>
                                </li>
                                <li>
                                    <span class="review-name">{{translate('below_avarage')}}</span>
                                    @php($below_average_count=$provider->reviews->where('review_rating',2)->count())
                                    @php($below_average=(divnum($below_average_count,$total_review_count))*100)
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar"
                                                style="width: {{$below_average}}%"
                                                aria-valuenow="{{$below_average}}" aria-valuemin="0"
                                                aria-valuemax="100">
                                        </div>
                                    </div>
                                    <span class="review-count">{{$below_average_count}}</span>
                                </li>
                                <li>
                                    <span class="review-name">{{translate('poor')}}</span>
                                    @php($poor_count=$provider->reviews->where('review_rating',1)->count())
                                    @php($poor=(divnum($poor_count,$total_review_count))*100)
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{$poor}}%"
                                                aria-valuenow="{{$poor}}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <span class="review-count">{{$poor_count}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end border-bottom pb-2 mb-10">
                <div class="d-flex gap-2 fw-medium">
                    <span class="opacity-75">{{translate('Total_Reviews')}}:</span>
                    <span class="title-color">{{$reviews->total()}}</span>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                        <form action="{{url()->current()}}?page_type={{$page_type}}"
                                class="search-form search-form_style-two"
                                method="POST">
                            @csrf
                            <div class="input-group search-form__input_group">
                                    <span class="search-form__icon">
                                        <span class="material-icons">search</span>
                                    </span>
                                <input type="search" class="theme-input-style search-form__input"
                                        value="{{$search??''}}" name="search"
                                        placeholder="{{translate('search_here')}}">
                            </div>
                            <button type="submit"
                                    class="btn btn--primary">{{translate('search')}}</button>
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
                                            href="{{route('provider.reviews.download',['search'=>$search??''])}}">{{translate('excel')}}</a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="example" class="table align-middle">
                            <thead>
                            <tr>
                                <th>{{translate('Booking_ID')}}</th>
                                <th>{{translate('Service')}}</th>
                                <th>{{translate('Booking_Date')}}</th>
                                <th>{{translate('Ratings')}}</th>
                                <th>{{translate('Reviews')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>{{isset($review->booking) ? $review->booking['readable_id'] : ''}}</td>
                                    <td>{{$review->service ? $review->service->name : translate('Service_unavailable')}}</td>
                                    <td>{{date('d-M-y H:iA', strtotime($review->created_at))}}</td>
                                    <td>{{($review->review_rating ?? 0)}}</td>
                                    <td>{{($review->review_comment ?? '')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                    {!! $reviews->links() !!}
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
