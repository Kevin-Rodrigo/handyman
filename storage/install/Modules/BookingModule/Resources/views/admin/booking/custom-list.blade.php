@extends('adminmodule::layouts.master')

@section('title',translate('Booking_List'))

@push('css_or_js')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Page Title -->
                <div class="page-title-wrap mb-3">
                    <h2 class="page-title">Customize Booking Requests</h2>
                </div>

                <!-- Tab Menu -->
                <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom mx-lg-4 mb-10 gap-3">
                    <ul class="nav nav--tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all-tab-pane" aria-selected="true" role="tab">All</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#new-booking-tab-pane" aria-selected="false" tabindex="-1" role="tab">New Booking Request</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#placed-offer-tab-pane" aria-selected="false" tabindex="-1" role="tab">Placed Offer</button>
                        </li>
                    </ul>

                    <div class="d-flex gap-2 fw-medium">
                        <span class="opacity-75">Total Customize Booking : </span>
                        <span class="title-color">20</span>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                    <form action="#" class="search-form search-form_style-two">
                                        <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                            <input type="search" class="theme-input-style search-form__input"
                                                placeholder="Search Here">
                                        </div>
                                        <button type="button"
                                            class="btn btn--primary text-capitalize">Search</button>
                                    </form>

                                    <div class="d-flex flex-wrap align-items-center gap-3">
                                        <div class="dropdown">
                                            <button type="button"
                                                class="btn btn--secondary text-capitalize dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <span class="material-icons">file_download</span> download
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                <li><a class="dropdown-item" href="#">Copy</a></li>
                                                <li><a class="dropdown-item" href="#">CSV</a></li>
                                                <li><a class="dropdown-item" href="#">Excel</a></li>
                                                <li><a class="dropdown-item" href="#">PDF</a></li>
                                                <li><a class="dropdown-item" href="#">Print</a></li>
                                            </ul>
                                        </div>

                                        <button type="button" class="btn text-capitalize filter-btn px-0">
                                            <span class="material-icons">filter_list</span> Filter
                                            <span class="count">15</span>
                                        </button>

                                        <div class="dropdown">  
                                            <button type="button" class="bg-transparent border-0"
                                                data-bs-toggle="dropdown">
                                                <span class="material-icons title-color">settings</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Zone Name</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Providers</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Services</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Status</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Action</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table align-middle" style="min-width: 800px">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Customer Info</th>
                                                <th>Booking Request Time</th>
                                                <th>Service Time</th>
                                                <th>Category</th>
                                                <th>Other Provider Offering</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a href="#">100025</a></td>
                                                <td>
                                                    <div>
                                                        <div class="customer-name fw-medium">Leonardo</div>
                                                        <a href="tel:+880372786552" class="fs-12">+880372786552</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div>30/03/2023</div>
                                                        <div>11:30am</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div>30/03/2023</div>
                                                        <div>11:30am</div>
                                                    </div>
                                                </td>
                                                <td>Electronic Repair</td>
                                                <td>
                                                    <div class="dropdown-hover">
                                                        <div class="dropdown-hover-toggle" data-bs-toggle="dropdown">
                                                            5 Providers
                                                        </div>

                                                        <ul class="dropdown-hover-menu">
                                                            <li>
                                                                <div class="media gap-3">
                                                                    <div class="avatar border rounded">
                                                                        <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5>Molnár Fruzsina</h5>
                                                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                            <span class="text-danger">price offered</span>
                                                                            <h5 class="text-primary">$150</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="media gap-3">
                                                                    <div class="avatar border rounded">
                                                                        <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5>Molnár Fruzsina</h5>
                                                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                            <span class="text-danger">price offered</span>
                                                                            <h5 class="text-primary">$150</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="media gap-3">
                                                                    <div class="avatar border rounded">
                                                                        <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5>Molnár Fruzsina</h5>
                                                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                            <span class="text-danger">price offered</span>
                                                                            <h5 class="text-primary">$150</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="media gap-3">
                                                                    <div class="avatar border rounded">
                                                                        <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5>Molnár Fruzsina</h5>
                                                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                            <span class="text-danger">price offered</span>
                                                                            <h5 class="text-primary">$150</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="media gap-3">
                                                                    <div class="avatar border rounded">
                                                                        <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5>Molnár Fruzsina</h5>
                                                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                            <span class="text-danger">price offered</span>
                                                                            <h5 class="text-primary">$150</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-actions d-flex justify-content-center">
                                                        <button type="button"
                                                            class="table-actions_view action-btn" data-bs-toggle="dropdown">
                                                            <span class="material-icons">more_horiz</span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                            <li><a class="dropdown-item" href="#">View details</a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newBookingModal">Placed Offer</a></li>
                                                            <li><a class="dropdown-item" href="#">See My Offer</a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ignoreRequestModal">Ignore/Reject</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">100025</a></td>
                                                <td>
                                                    <div>
                                                        <div class="customer-name fw-medium">Leonardo</div>
                                                        <a href="tel:+880372786552" class="fs-12">+880372786552</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div>30/03/2023</div>
                                                        <div>11:30am</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div>30/03/2023</div>
                                                        <div>11:30am</div>
                                                    </div>
                                                </td>
                                                <td>Electronic Repair</td>
                                                <td>
                                                    <div class="dropdown-hover">
                                                        <div class="dropdown-hover-toggle" data-bs-toggle="dropdown">
                                                            5 Providers
                                                        </div>

                                                        <ul class="dropdown-hover-menu">
                                                            <li>
                                                                <div class="media gap-3">
                                                                    <div class="avatar border rounded">
                                                                        <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5>Molnár Fruzsina</h5>
                                                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                            <span class="text-danger">price offered</span>
                                                                            <h5 class="text-primary">$150</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="media gap-3">
                                                                    <div class="avatar border rounded">
                                                                        <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5>Molnár Fruzsina</h5>
                                                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                            <span class="text-danger">price offered</span>
                                                                            <h5 class="text-primary">$150</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="media gap-3">
                                                                    <div class="avatar border rounded">
                                                                        <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5>Molnár Fruzsina</h5>
                                                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                            <span class="text-danger">price offered</span>
                                                                            <h5 class="text-primary">$150</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-actions d-flex justify-content-center">
                                                        <button type="button"
                                                            class="table-actions_view action-btn" data-bs-toggle="dropdown">
                                                            <span class="material-icons">more_horiz</span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                            <li><a class="dropdown-item" href="#">View details</a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newBookingModal">Placed Offer</a></li>
                                                            <li><a class="dropdown-item" href="#">See My Offer</a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ignoreRequestModal">Ignore/Reject</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">100025</a></td>
                                                <td>
                                                    <div>
                                                        <div class="customer-name fw-medium">Leonardo</div>
                                                        <a href="tel:+880372786552" class="fs-12">+880372786552</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div>30/03/2023</div>
                                                        <div>11:30am</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div>30/03/2023</div>
                                                        <div>11:30am</div>
                                                    </div>
                                                </td>
                                                <td>Electronic Repair</td>
                                                <td>
                                                    <div class="dropdown-hover">
                                                        <div class="dropdown-hover-toggle" data-bs-toggle="dropdown">
                                                            5 Providers
                                                        </div>

                                                        <ul class="dropdown-hover-menu">
                                                            <li>
                                                                <div class="media gap-3">
                                                                    <div class="avatar border rounded">
                                                                        <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5>Molnár Fruzsina</h5>
                                                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                            <span class="text-danger">price offered</span>
                                                                            <h5 class="text-primary">$150</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="media gap-3">
                                                                    <div class="avatar border rounded">
                                                                        <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5>Molnár Fruzsina</h5>
                                                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                            <span class="text-danger">price offered</span>
                                                                            <h5 class="text-primary">$150</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="media gap-3">
                                                                    <div class="avatar border rounded">
                                                                        <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <h5>Molnár Fruzsina</h5>
                                                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                                            <span class="text-danger">price offered</span>
                                                                            <h5 class="text-primary">$150</h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="table-actions d-flex justify-content-center">
                                                        <button type="button"
                                                            class="table-actions_view action-btn" data-bs-toggle="dropdown">
                                                            <span class="material-icons">more_horiz</span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                            <li><a class="dropdown-item" href="#">View details</a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newBookingModal">Placed Offer</a></li>
                                                            <li><a class="dropdown-item" href="#">See My Offer</a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ignoreRequestModal">Ignore/Reject</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="new-booking-tab-pane" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                    <form action="#" class="search-form search-form_style-two">
                                        <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                            <input type="search" class="theme-input-style search-form__input"
                                                placeholder="Search Here">
                                        </div>
                                        <button type="button"
                                            class="btn btn--primary text-capitalize">Search</button>
                                    </form>

                                    <div class="d-flex flex-wrap align-items-center gap-3">
                                        <div class="dropdown">
                                            <button type="button"
                                                class="btn btn--secondary text-capitalize dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <span class="material-icons">file_download</span> download
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                <li><a class="dropdown-item" href="#">Copy</a></li>
                                                <li><a class="dropdown-item" href="#">CSV</a></li>
                                                <li><a class="dropdown-item" href="#">Excel</a></li>
                                                <li><a class="dropdown-item" href="#">PDF</a></li>
                                                <li><a class="dropdown-item" href="#">Print</a></li>
                                            </ul>
                                        </div>

                                        <button type="button" class="btn text-capitalize filter-btn px-0">
                                            <span class="material-icons">filter_list</span> Filter
                                            <span class="count">15</span>
                                        </button>

                                        <div class="dropdown">  
                                            <button type="button" class="bg-transparent border-0"
                                                data-bs-toggle="dropdown">
                                                <span class="material-icons title-color">settings</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Zone Name</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Providers</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Services</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Status</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Action</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table align-middle" style="min-width: 800px">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Customer Info</th>
                                                <th>Booking Request Time</th>
                                                <th>Service Time</th>
                                                <th>Category</th>
                                                <th>Other Provider Offering</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a href="#">100025</a></td>
                                                <td>
                                                    <div>
                                                        <div class="customer-name fw-medium">Leonardo</div>
                                                        <a href="tel:+880372786552" class="fs-12">+880372786552</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div>30/03/2023</div>
                                                        <div>11:30am</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div>30/03/2023</div>
                                                        <div>11:30am</div>
                                                    </div>
                                                </td>
                                                <td>Electronic Repair</td>
                                                <td>5 Providers</td>
                                                <td>
                                                    <div class="table-actions d-flex justify-content-center">
                                                        <button type="button"
                                                            class="table-actions_view action-btn" data-bs-toggle="dropdown">
                                                            <span class="material-icons">more_horiz</span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                            <li><a class="dropdown-item" href="#">View details</a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newBookingModal">Placed Offer</a></li>
                                                            <li><a class="dropdown-item" href="#">See My Offer</a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ignoreRequestModal">Ignore/Reject</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="placed-offer-tab-pane" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="data-table-top d-flex flex-wrap gap-10 justify-content-between">
                                    <form action="#" class="search-form search-form_style-two">
                                        <div class="input-group search-form__input_group">
                                            <span class="search-form__icon">
                                                <span class="material-icons">search</span>
                                            </span>
                                            <input type="search" class="theme-input-style search-form__input"
                                                placeholder="Search Here">
                                        </div>
                                        <button type="button"
                                            class="btn btn--primary text-capitalize">Search</button>
                                    </form>

                                    <div class="d-flex flex-wrap align-items-center gap-3">
                                        <div class="dropdown">
                                            <button type="button"
                                                class="btn btn--secondary text-capitalize dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <span class="material-icons">file_download</span> download
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                <li><a class="dropdown-item" href="#">Copy</a></li>
                                                <li><a class="dropdown-item" href="#">CSV</a></li>
                                                <li><a class="dropdown-item" href="#">Excel</a></li>
                                                <li><a class="dropdown-item" href="#">PDF</a></li>
                                                <li><a class="dropdown-item" href="#">Print</a></li>
                                            </ul>
                                        </div>

                                        <button type="button" class="btn text-capitalize filter-btn px-0">
                                            <span class="material-icons">filter_list</span> Filter
                                            <span class="count">15</span>
                                        </button>

                                        <div class="dropdown">  
                                            <button type="button" class="bg-transparent border-0"
                                                data-bs-toggle="dropdown">
                                                <span class="material-icons title-color">settings</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Zone Name</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Providers</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Services</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Status</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <li class="dropdown-item py-2">
                                                    <div
                                                        class="d-flex align-items-center gap-4 justify-content-between">
                                                        <span>Action</span>
                                                        <label class="switcher">
                                                            <input class="switcher_input" type="checkbox"
                                                                checked="checked">
                                                            <span class="switcher_control"></span>
                                                        </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table align-middle" style="min-width: 800px">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Customer Info</th>
                                                <th>Booking Request Time</th>
                                                <th>Service Time</th>
                                                <th>Category</th>
                                                <th>Other Provider Offering</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a href="#">100025</a></td>
                                                <td>
                                                    <div>
                                                        <div class="customer-name fw-medium">Leonardo</div>
                                                        <a href="tel:+880372786552" class="fs-12">+880372786552</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div>30/03/2023</div>
                                                        <div>11:30am</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div>30/03/2023</div>
                                                        <div>11:30am</div>
                                                    </div>
                                                </td>
                                                <td>Electronic Repair</td>
                                                <td>5 Providers</td>
                                                <td>
                                                    <div class="table-actions d-flex justify-content-center">
                                                        <button type="button"
                                                            class="table-actions_view action-btn" data-bs-toggle="dropdown">
                                                            <span class="material-icons">more_horiz</span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                                            <li><a class="dropdown-item" href="#">View details</a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newBookingModal">Placed Offer</a></li>
                                                            <li><a class="dropdown-item" href="#">See My Offer</a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ignoreRequestModal">Ignore/Reject</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <!-- Modal Buttons -->
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn--primary" data-bs-toggle="modal" data-bs-target="#alertModal">
                            Alert Modal
                            </button>
                            <button type="button" class="btn btn--primary" data-bs-toggle="modal" data-bs-target="#providerInfoModal">
                            Provider Information Modal
                            </button>
                            <button type="button" class="btn btn--primary" data-bs-toggle="modal" data-bs-target="#offerDetailsModal">
                            Offer Details Modal
                            </button>
                            <button type="button" class="btn btn--primary" data-bs-toggle="modal" data-bs-target="#serviceRequestModal">
                            Service Request Modal
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Service Request Modal -->
                <div class="modal fade" id="serviceRequestModal" tabindex="-1" aria-labelledby="serviceRequestModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="--bs-modal-width: 430px">
                        <div class="modal-content">
                            <div class="modal-header pb-0 border-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body pb-4 px-lg-4">
                                <div class="d-flex flex-column align-items-center">
                                    <img width="75" class="mb-3" src="{{asset('public/assets/admin-module')}}/img/icons/notification.png" alt="">

                                    <h3 class="text-primary text-center mb-3">New Service Request</h3>

                                    <div class="mb-4 text-center d-flex flex-column align-items-center">
                                        <div class="avatar avatar-lg mb-2">
                                            <img class="rounded" src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" alt="">
                                        </div>
                                        <div>Jhone Doe</div>
                                        <div class="text-muted fs-12">0.8km away from you</div>
                                    </div>

                                    <div class="media gap-2 mb-4">
                                        <img width="30" src="{{asset('public/assets/admin-module')}}/img/media/appliance.png" alt="">
                                        <div class="media-body">
                                            <h5>Appliance Repair</h5>
                                            <div class="text-muted fs-12">Electronics Repair</div>
                                        </div>
                                    </div>

                                    <button class="btn btn--primary">Ok, Let me check</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Offer Details Modal -->
                <div class="modal fade" id="offerDetailsModal" tabindex="-1" aria-labelledby="offerDetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="--bs-modal-width: 430px">
                        <div class="modal-content">
                            <div class="modal-header px-sm-4">
                                <h4 class="modal-title text-primary" id="offerDetailsModalLabel">My Offer Details</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body pb-4 px-lg-4">
                                <div class="">

                                    <div class="d-flex gap-4 mb-4">
                                        <div class="media gap-2 ">
                                            <img width="30" src="{{asset('public/assets/admin-module')}}/img/media/appliance.png" alt="">
                                            <div class="media-body">
                                                <h5>Appliance Repair</h5>
                                                <div class="text-muted fs-12">Electronics Repair</div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class=" border-start ps-4">
                                                <div class="d-flex gap-2 flex-wrap align-items-center">
                                                    <span class="text-danger fs-12">price offered</span>
                                                    <h4 class="text-primary">$150</h4>
                                                </div>
                                                <span class="text-muted fs-12">2days ago</span>
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="text-muted mb-2">Description:</h3>
                                    <p>Hello. I am willing to take the service. I will provide all the equipments for repair and my servicemen will handle all the expense needed for . But I’ll not provide the cost if any parts of the machine is broken.  This cost have to bear from customer.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Provider Info Modal -->
                <div class="modal fade" id="providerInfoModal" tabindex="-1" aria-labelledby="providerInfoModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" style="--bs-modal-width: 630px">
                        <div class="modal-content">
                            <div class="modal-header px-sm-4">
                                <h4 class="modal-title text-primary" id="providerInfoModalLabel">Provider Information</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body pb-4 px-lg-4">
                                <div class="media flex-column flex-sm-row flex-wrap gap-3">
                                    <img width="173" class="radius-10" src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" alt="">
                                    <div class="media-body">
                                        <h5 class="fw-medium mb-1">Molnár Fruzsina</h5>
                                        <div class="fs-12 d-flex flex-wrap align-items-center gap-2 mt-1">
                                            <span class="common-list_rating d-flex gap-1">
                                                <span class="material-icons text-primary fs-12">star</span>
                                                4.2
                                            </span>
                                            <span>122 Reviews</span>
                                        </div>

                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1 mb-3">
                                            <span class="text-danger">price offered</span>
                                            <h4 class="text-primary">$150</h4>
                                        </div>

                                        <h3 class="text-muted mb-2">Description:</h3>
                                        <p>Hello. I am willing to take the service. I will provide all the equipments for repair and my servicemen will handle all the expense needed for . But I’ll not provide the cost if any parts of the machine is broken.  This cost have to bear from customer.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alert Modal -->
                <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header pb-0 border-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body pb-sm-5 px-sm-5">
                                <div class="d-flex flex-column align-items-center gap-2 text-center">
                                    <img src="{{asset('public/assets/admin-module')}}/img/icons/alert.png" alt="">
                                    <h3>Alert!</h3>
                                    <p class="fw-medium">This request is with customized instructions. Please read the customer description and instructions thoroughly and place your pricing according to this</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Booking Request Modal -->
                <div class="modal fade" id="newBookingModal" tabindex="-1" aria-labelledby="newBookingModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="newBookingModalLabel">New Booking Request Form</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex gap-4 mb-4">
                                            <div class="media gap-2">
                                                <div class="avatar avatar-lg rounded">
                                                    <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" alt="">
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="text-primary">Jhon Doe</h5>
                                                    <div class="text-muted fs-12">0.8km away from you</div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="media gap-2 border-start ps-4">
                                                    <img width="30" src="{{asset('public/assets/admin-module')}}/img/media/appliance.png" alt="">
                                                    <div class="media-body">
                                                        <h5>Appliance Repair</h5>
                                                        <div class="text-muted fs-12">Electronics Repair</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <img width="18" src="{{asset('public/assets/admin-module')}}/img/media/edit-info.png" alt="">
                                            <h4>Service Requirement</h4>
                                        </div>
                                        <p class="fs-12">Need professional Service Providers who would give us the best AC repair service. From general inspection, to changing AC parts user could be avail every AC related service within a few moments.</p>
                                    </div>
                                </div>

                                <div class="card border mt-3">
                                    <div class="card-body">
                                        <div class="mb-30">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" placeholder="Offer Price" id="offer-price" data-bs-toggle="tooltip" data-bs-placement="top" title="Minimum Offer price $200">
                                                <label for="offer-price">Offer Price</label>
                                            </div>
                                        </div>
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Add Your Note" name="add-your-note" id="add-your-note"></textarea>
                                            <label for="add-your-note" class="d-flex align-items-center gap-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Minimum Offer price $200">Add Your Note <i class="material-icons">info</i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-end border-0 pt-0">
                                <button type="button" class="btn btn--primary">Send Your Offer</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ignore Request Modal -->
                <div class="modal fade" id="ignoreRequestModal" tabindex="-1" aria-labelledby="ignoreRequestModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex flex-column gap-2 align-items-center">
                                    <img width="75" class="mb-2" src="{{asset('public/assets/admin-module')}}/img/media/ignore-request.png" alt="">
                                    <h3>Are you sure you want to ignore this request?</h3>
                                    <div class="text-muted fs-12">You will lost the customer booking request</div>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center gap-3 border-0 pt-0 pb-4">
                                <button type="button" class="btn btn--secondary">Cancel</button>
                                <button type="button" class="btn btn--primary">Ignore</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Details -->
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-12">
                <!-- Page Title -->
                <div class="page-title-wrap mb-3">
                    <h2 class="page-title">Booking Details</h2>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="card bg-primary-light shadow-none">
                                    <div class="card-body pb-5">
                                        <div class="media flex-wrap gap-3">
                                            <img width="140" class="radius-10" src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" alt="">
                                            <div class="media-body">
                                                <div class="d-flex align-items-center gap-2 mb-3">
                                                    <span class="material-icons text-primary">person</span>
                                                    <h4>Customer Infotmation</h4>
                                                </div>
                                                <h5 class="text-primary mb-1">Jhon Doe</h5>
                                                <p class="text-muted fs-12">0.8km away from you</p>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="material-icons">phone_iphone</span>
                                                    <a href="tel:88013756987564">+88013756987564</a>
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
                                            <img width="18" src="{{asset('public/assets/admin-module')}}/img/media/more-info.png" alt="">
                                            <h4>Service Information</h4>
                                        </div>
                                        <div class="media gap-2 mb-4">
                                            <img width="30" src="{{asset('public/assets/admin-module')}}/img/media/appliance.png" alt="">
                                            <div class="media-body">
                                                <h5>Appliance Repair</h5>
                                                <div class="text-muted fs-12">Electronics Repair</div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex flex-column gap-2">
                                            <div class="fw-medium">Booking Request Time : <span class="fw-bold">20/02/2022 4:30 PM</span></div>
                                            <div class="fw-medium">Service Time : <span class="fw-bold">27/02/2022 4:30 PM</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center gap-2 bg-primary-light shadow-none">
                                        <img width="18" src="{{asset('public/assets/admin-module')}}/img/icons/instruction.png" alt="">
                                        <h5 class="text-uppercase">Additional Instruction</h5>
                                    </div>
                                    <div class="card-body pb-4">
                                        <ul class="d-flex flex-column gap-3 px-3" style="max-width: 340px">
                                            <li>Service man must bring all the equipment's</li>
                                            <li>Provider will pay if any product needed to buy to repair</li>
                                            <li>Provider will pay if any product needed to buy to repair</li>
                                            <li>Provider will pay if any product needed to buy to repair</li>
                                        </ul>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center gap-2 bg-primary-light shadow-none">
                                        <img width="18" src="{{asset('public/assets/admin-module')}}/img/icons/edit-info.png" alt="">
                                        <h5 class="text-uppercase">Service Description</h5>
                                    </div>
                                    <div class="card-body pb-4">
                                        <p>Need professional Service Providers who would give us the best AC repair service. From general inspection, to changing AC parts user could be avail every AC related service within a few moments.</p>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center gap-2 bg-primary-light shadow-none">
                                        <img width="18" src="{{asset('public/assets/admin-module')}}/img/icons/provider.png" alt="">
                                        <h5 class="text-uppercase">PLACED OFFER dETAILS</h5>
                                    </div>
                                    <div class="card-body pb-4">
                                        <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mb-3">
                                            <span class="text-danger">price offered</span>
                                            <h3 class="text-primary">$150</h3>
                                            <span class="text-muted fs-12">( 2days ago )</span>
                                        </div>

                                        <h3 class="text-muted mb-2">Note :</h3>
                                        <p>Need professional Service Providers who would give us the best AC repair service. From general inspection, to changing AC parts user could be avail every AC related service within a few moments.</p>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center gap-2 bg-primary-light shadow-none">
                                        <img width="18" src="{{asset('public/assets/admin-module')}}/img/icons/provider.png" alt="">
                                        <h5 class="text-uppercase">OTHER PROVIDER Offering</h5>
                                    </div>
                                    <div class="card-body pb-4">
                                        <div class="d-flex justify-content-between gap-3 mb-4">
                                            <div class="media gap-3">
                                                <div class="avatar avatar-lg">
                                                    <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                </div>
                                                <div class="media-body">
                                                    <h5>Molnár Fruzsina</h5>
                                                    <div class="fs-12 d-flex flex-wrap align-items-center gap-2 mt-1">
                                                        <span class="common-list_rating d-flex gap-1">
                                                            <span class="material-icons text-primary fs-12">star</span>
                                                            4.2
                                                        </span>
                                                        <span>122 Reviews</span>
                                                    </div>
                                                    <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                        <span class="text-danger">price offered</span>
                                                        <h4 class="text-primary">$150</h4>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <img width="24" src="{{asset('public/assets/admin-module')}}/img/icons/chat.png" alt="">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3 mb-4">
                                            <div class="media gap-3">
                                                <div class="avatar avatar-lg">
                                                    <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                </div>
                                                <div class="media-body">
                                                    <h5>Molnár Fruzsina</h5>
                                                    <div class="fs-12 d-flex flex-wrap align-items-center gap-3 mt-1">
                                                        <span class="common-list_rating d-flex gap-1">
                                                            <span class="material-icons text-primary fs-12">star</span>
                                                            4.2
                                                        </span>
                                                        <span>122 Reviews</span>
                                                    </div>
                                                    <div class="d-flex gap-2 flex-wrap align-items-center fs-12 mt-1">
                                                        <span class="text-danger">price offered</span>
                                                        <h4 class="text-primary">$150</h4>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <img width="24" src="{{asset('public/assets/admin-module')}}/img/icons/chat.png" alt="">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between gap-3">
                                            <div class="media gap-3">
                                                <div class="avatar avatar-lg">
                                                    <img src="{{asset('public/assets/admin-module')}}/img/avatar/serviceman.png" class="rounded" alt="">
                                                </div>
                                                <div class="media-body">
                                                    <h5>Molnár Fruzsina</h5>
                                                    <div class="fs-12 d-flex flex-wrap align-items-center gap-3 mt-1">
                                                        <span class="common-list_rating d-flex gap-1">
                                                            <span class="material-icons text-primary fs-12">star</span>
                                                            4.2
                                                        </span>
                                                        <span>122 Reviews</span>
                                                    </div>
                                                    <div class="d-flex gap-2  flex-wrap align-items-center fs-12 mt-1">
                                                        <span class="text-danger">price offered</span>
                                                        <h4 class="text-primary">$150</h4>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <img width="24" src="{{asset('public/assets/admin-module')}}/img/icons/chat.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <button class="btn btn-danger">Ignore</button>
                            <button class="btn btn--primary">Place Offer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
