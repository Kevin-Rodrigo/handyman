@extends('frontend.layouts.master')
@section('title')
    Profile
@endsection
@section('content')
    <section>
        <div class="container">
            <div class="breadcrumb-div pt-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-primary-color" href="{{ route('home') }}"><i
                                    class="fa-solid fa-house fs-7 me-2"></i>Home</a>
                        </li>
                        <li class="breadcrumb-item  active breadcrumb-item-left" aria-current="page">Account Details
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="container contact-us">
            <h2 class="section-title fw-bold">Account Details</h2>
        </div>



        <div class="container my-5">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card p-3 rounded-4" style="background: lightgray;">
                        <div class="d-flex justify-content-center align-items-center flex-column my-4"
                            style="border-bottom: 1px solid gray;">
                            @if ($user->profile_image == 'default.png')
                                <img class="my-2" id="selectedImage"
                                    style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%; box-shadow: 1px 3px 4px lightgray;"
                                    src="https://bookingdo.paponapps.co.in/storage/app/public/admin-assets/images/about/default.png"
                                    alt="">
                            @else
                                <img class="my-2" id="selectedImage"
                                    style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%; box-shadow: 1px 3px 4px lightgray;"
                                    src="{{ asset('public/' . $user->profile_image) }}" alt="">
                            @endif
                            <h5>Profile</h5>
                            <p>{{ $user->email }}</p>
                        </div>
                        <ul class="nav nav-tabs d-flex flex-column">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile">Edit
                                    Profile</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password">Change
                                    Password</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" id="booking-tab" data-bs-toggle="tab" href="#booking">Booking
                                    List</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="wishlist-tab" data-bs-toggle="tab" href="#wishlist">Wishlist</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="account-tab" data-bs-toggle="tab" href="#account">Delete
                                    Account</a>
                            </li> --}}
                            <li class="nav-item">
                                <form action="{{ route('customer-logout') }}" method="POST">
                                    @csrf
                                    <a class="nav-link" style="cursor:pointer"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 card p-3 rounded-4">
                    <div class="tab-content mt-2">
                        <div class="tab-pane fade show active" id="profile">
                            <h2 style="border-bottom: 1px solid lightgray; padding-bottom: 10px;">Edit Profile</h2>
                            <form class="row my-3 needs-validation" method="POST" action="{{ route('updateprofile') }}"
                                enctype="multipart/form-data" novalidate="">
                                @csrf
                                <p>Upload Your Profile Photo <span class="text-danger">*</span></p>
                                <div class="d-flex align-items-center gap-4">
                                    @if ($user->profile_image == 'default.png')
                                        <img class="my-2" id="selectedImage"
                                            style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%; box-shadow: 1px 3px 4px lightgray;"
                                            src="https://bookingdo.paponapps.co.in/storage/app/public/admin-assets/images/about/default.png"
                                            alt="">
                                    @else
                                        <img class="my-2" id="selectedImage"
                                            style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%; box-shadow: 1px 3px 4px lightgray;"
                                            src="{{ asset('public/' . $user->profile_image) }}" alt="">
                                    @endif

                                    <div class="input-group">
                                        <input type="file" class="form-control" id="imageInput" accept="image/*"
                                            style="display: none;" name="profile_image">
                                        <button type="button" class='btn btn-primary btn-submit py-2 px-3 rounded-4'
                                            onclick="document.getElementById('imageInput').click()">Change</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label text-muted">First Name</label>
                                    <input type="text" class="form-control input-h" name="first_name"
                                        value="{{ $user->first_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label text-muted">Last Name</label>
                                    <input type="text" class="form-control input-h" name="last_name"
                                        value="{{ $user->last_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label text-muted">Email </label>
                                    <input type="email" class="form-control input-h" name="email"
                                        value="{{ $user->email }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label text-muted">Mobile <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-h" name="phone"
                                        value="{{ $user->phone }}" required>
                                </div>
                                <div class="my-3">
                                    <button class="btn btn-primary btn-submit rounded-4" type="submit">Save
                                        Changes</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade">
                            <form class="row my-3" method="POST"
                                action="https://bookingdo.paponapps.co.in/theme-1/checklogin-normal">
                                <h2 style="border-bottom: 1px solid lightgray; padding-bottom: 10px;">Change Password
                                </h2>
                                <div class="form-group">
                                    <label for="" class="form-label text-muted">Current Password </label>
                                    <input type="password" class="form-control input-h" name="email"
                                        placeholder="Current Password" id="email" value="user@yopmail.com" required>
                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label text-muted">New Password </label>
                                    <input type="password" class="form-control input-h" name="email"
                                        placeholder="New Password" id="email" value="user@yopmail.com" required>
                                </div>
                                <div class="form-group">
                                    <label for="" class="form-label text-muted">Confirm Password </label>
                                    <input type="password" class="form-control input-h" name="email"
                                        placeholder="Confirm Password" id="email" value="user@yopmail.com" required>
                                </div>

                                <div class="my-3">
                                    <button class="btn btn-primary btn-submit rounded-4" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="booking">
                            <h2 style="border-bottom: 1px solid lightgray; padding-bottom: 10px;">Booking List</h2>
                            <div class="card my-3 shadow h-100 w-100 border-0 overflow-hidden ">
                                <div class="row responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th class="ps-5">Booking ID</th>
                                            <th>Total Amount</th>
                                            <th>Payment Status	</th>
                                            <th>Schedule Date</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                            @foreach($bookings as $booking)
                                            <tr>
                                                <td class="ps-5">{{$booking->readable_id}}</td>
                                                <td>{{with_currency_symbol($booking->total_booking_amount)}}</td>
                                                <td>
                                                    @if($booking->payment_status == '0')
                                                    Unpaid
                                                    @else
                                                    Paid
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($booking->service_schedule)->format('d, M, Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="wishlist">
                            <h2 style="border-bottom: 1px solid lightgray; padding-bottom: 10px;">My Wishlist</h2>
                        </div>
                        <div class="tab-pane fade" id="account">
                            <h2 style="border-bottom: 1px solid lightgray; padding-bottom: 10px;">Delete Account</h2>
                            <h6>Before You Go...</h6>
                            <ul>
                                <li>Take a Backup of your data here</li>
                                <li>If You delete your account, You will lose your all data</li>
                            </ul>
                            <div class="d-flex align-items-center gap-3">
                                <input type="radio" name="" id="">
                                <p class="pt-3">Yes i would like to delete my account</p>
                            </div>
                            <button class="btn btn-primary btn-submit rounded-4 mt-5">Delete my Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        document.getElementById('imageInput').addEventListener('change', function() {
            var input = this;
            var image = document.getElementById('selectedImage');
            var reader = new FileReader();

            reader.onload = function(e) {
                image.src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        });
    </script>
@endpush
