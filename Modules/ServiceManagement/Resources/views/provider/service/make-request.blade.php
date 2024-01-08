@extends('providermanagement::layouts.master')

@section('title',translate('Request for Service'))

@push('css_or_js')
    <style>
        #serviceForm.show + .show_form-btn {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Request for Service')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body py-xl-5">
                            <div class="row justify-content-center align-items-center gy-5">
                                <div class="col-7 col-lg-5">
                                    <h3 class="mb-3">{{translate('Tell us more about your desired services')}}</h3>
                                    <p>{{translate('Suggest more services that are willing to book and help us make  more efficient platform for you')}} ...</p>

                                    <div class="collapse" id="serviceForm">
                                        <form action="{{route('provider.service.make-request')}}" method="post">
                                            @csrf
                                            <div class="mb-30">
                                                <select class="js-select category__select" name="category_id" required>
                                                    <option value="" selected disabled>{{translate('Select category')}}</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{$category['id']}}" {{old('category_id') == $category['id'] ? 'selected' : ''}}>{{$category['name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-floating mb-30">
                                                <input type="text" class="form-control" name="service_name" placeholder="{{translate('Service Name')}}" value="{{old('service_name')}}" required>
                                                <label>{{translate('Service Name')}}</label>
                                            </div>

                                            <div class="form-floating mb-30">
                                                <textarea class="form-control" placeholder="{{translate('Provide Some Description')}}" id="floatingTextarea" name="service_description" required>{{old('service_description')}}</textarea>
                                                <label for="floatingTextarea">{{translate('Provide Some Description')}}</label>
                                            </div>

                                            <button type="submit" class="btn btn--primary">{{translate('Send Request')}}</button>
                                        </form>
                                    </div>
                                    <a href="#serviceForm" class="btn btn--primary show_form-btn" data-bs-toggle="collapse">{{translate('Send Request')}}</a>


                                </div>
                                <div class="col-5 col-lg-5">
                                    <div class="text-center">
                                        <img width="220" src="{{asset('public/assets/admin-module/img/media/serv.png')}}" alt="">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function collapse() {
            $(document.body).on('click', '[data-toggle="collapse"]', function (e) {
                e.preventDefault();
                var target = '#' + $(this).data('target');

                $(this).slideToggle('collapsed');
                $(target).slideToggle();

            })
        }
        collapse();
    </script>

    <script>
        $(document).ready(function () {
            $('.category__select').select2({
                placeholder: "{{translate('Select_category')}}",
            });
        });
    </script>
@endpush
