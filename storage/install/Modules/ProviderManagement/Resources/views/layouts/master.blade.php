<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Page Title -->
    <title>@yield('title')</title>

    <!-- Meta Data -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon"
          href="{{asset('storage/app/public/business')}}/{{(business_config('business_favicon', 'business_information'))->live_values ?? null}}"/>

    <!-- Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet">


    <!-- ======= BEGIN GLOBAL MANDATORY STYLES ======= -->
    <link href="{{asset('public/assets/provider-module')}}/css/material-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/bootstrap.min.css"/>
    <link rel="stylesheet"
          href="{{asset('public/assets/provider-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.css"/>
    <!-- ======= END BEGIN GLOBAL MANDATORY STYLES ======= -->

    <!-- ======= BEGIN PAGE LEVEL PLUGINS STYLES ======= -->
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/plugins/apex/apexcharts.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/plugins/select2/select2.min.css"/>
    <!-- ======= END BEGIN PAGE LEVEL PLUGINS STYLES ======= -->

    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/toastr.css">

    <!-- ======= MAIN STYLES ======= -->
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/style.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/dev.css"/>
    <!-- ======= END MAIN STYLES ======= -->
    @stack('css_or_js')
</head>

<body>
<script>
    localStorage.theme && document.querySelector('body').setAttribute("theme", localStorage.theme);
    localStorage.dir && document.querySelector('html').setAttribute("dir", localStorage.dir);
</script>

<!-- Offcanval Overlay -->
<div class="offcanvas-overlay"></div>
<!-- Offcanval Overlay -->

<!-- Preloader -->
<div class="preloader"></div>
<!-- End Preloader -->

<!-- Header -->
@include('providermanagement::layouts.partials._header')
<!-- End Header -->

<!-- Aside -->
@include('providermanagement::layouts.partials._aside')
<!-- End Aside -->

<!-- Settings Sidebar -->
@include('providermanagement::layouts.partials._settings-sidebar')
<!-- End Settings Sidebar -->

<!-- Wrapper -->
<main class="main-area">
    <!-- Main Content -->
@yield('content')
<!-- End Main Content -->

<!-- Footer -->
@include('providermanagement::layouts.partials._footer')
<!-- End Footer -->

    <!-- Service Request Modal -->
    <div class="modal fade" id="serviceRequestModal" tabindex="-1"
         aria-labelledby="serviceRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="--bs-modal-width: 430px">
            <div class="modal-content">
                <!-- Content will be loaded -->
            </div>
        </div>
    </div>

</main>
<!-- End wrapper -->

<!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->
<script src="{{asset('public/assets/provider-module')}}/js/jquery-3.6.0.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/main.js"></script>
<!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->

<!-- ======= BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS ======= -->
<script src="{{asset('public/assets/provider-module')}}/plugins/select2/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.js-select').select2();
    });
</script>

{{--toastr and sweetalert--}}
<script src="{{asset('public/assets/provider-module')}}/js/sweet_alert.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/toastr.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/dev.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

@if(!request()->is('provider/profile-update') && is_null(auth()->user()->provider->coordinates))
    <script>
        console.log('pop up');
        Swal.fire({
            title: "{{translate('Update Location')}}",
            text: "{{translate('You must update your location first')}}",
            type: 'warning',
            showCloseButton: false,
            showCancelButton: false,
            confirmButtonColor: 'var(--c1)',
            confirmButtonText: "{{translate('Update from profile')}}",
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = "{{route('provider.profile_update')}}";
            }
        })
    </script>
@endif

<script>
    function form_alert(id, message) {
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

    function route_alert(route, message) {
        Swal.fire({
            title: "{{translate('are_you_sure')}}?",
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'var(--c2)',
            confirmButtonColor: 'var(--c1)',
            cancelButtonText: '{{translate('Cancel')}}',
            confirmButtonText: '{{translate('Yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.get({
                    url: route,
                    dataType: 'json',
                    data: {},
                    beforeSend: function () {
                        /*$('#loading').show();*/
                    },
                    success: function (data) {
                        console.log(data)
                        toastr.success(data.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    },
                    complete: function () {
                        /*$('#loading').hide();*/
                    },
                });
            }
        })
    }
</script>


<!-- ======= BEGIN **AUTO RUNNABLE** SCRIPTS ======= -->

<!-- Audio -->
<audio id="audio-element">
    <source src="{{asset('public/assets/provider-module')}}/sound/notification.mp3" type="audio/mpeg">
</audio>
<script>
    var audio = document.getElementById("audio-element");

    function playAudio(status) {
        status ? audio.play() : audio.pause();
    }
</script>

<script>
    function checkBooking(count) {
        console.log(count)
        sessionStorage.setItem("booking_count", parseInt(count));
    }

    function update_notification() {
        let count = $('#notification_count').text();
        let notification_count = sessionStorage.getItem("notification_count");

        if (parseInt(count) > 0) {
            sessionStorage.setItem("notification_count", parseInt(notification_count) + parseInt(count));
        }
    }

    setInterval(function () {
        $.get({
            url: '{{ route('provider.get_updated_data') }}',
            dataType: 'json',
            success: function (response) {
                let data = response.data;

                //notification
                let notification_count = sessionStorage.getItem("notification_count");
                if (notification_count == null || isNaN(notification_count)) {
                    notification_count = 0;
                    sessionStorage.setItem("notification_count", notification_count);
                }

                //booking
                let booking_count = sessionStorage.getItem("booking_count");
                if (booking_count == null || isNaN(parseInt(booking_count)) || data.booking === 0) {
                    booking_count = 0;
                    sessionStorage.setItem("booking_count", parseInt(booking_count));
                }

                //update header count
                let count = data.notification_count - parseInt(notification_count);
                document.getElementById("message_count").innerHTML = data.message;
                document.getElementById("notification_count").innerHTML = parseInt(count) < 0 ? 0 : parseInt(count);
                document.getElementById("show-notification-list").innerHTML = data.notification_template;

                //pop-up show if any new booking
                if (data.booking !== parseInt(booking_count) && data.booking > 0) {
                    playAudio(true);
                    Swal.fire({
                        title: '{{translate('New_Notification')}}',
                        text: "{{translate('You_have_new_booking_arrived')}}",
                        icon: 'info',
                        showCloseButton: true,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText: '{{translate('Show_Bookings')}}',
                    }).then((result) => {
                        if (result.value) {
                            playAudio(false);
                            checkBooking();
                            location.href = "{{route('provider.booking.list', ['booking_status'=>'pending'])}}";
                        } else if (result.dismiss === 'close') {
                            playAudio(false);
                            checkBooking(data.booking);
                        }
                    })
                }

                //bidding service request alert
                if (data.unchecked_posts > 0) {
                    playAudio(true);
                    if(data.post_content !== null) {
                        $('#serviceRequestModal .modal-content').html(data.post_content);
                    }
                    $('#serviceRequestModal').modal('show');
                }
            },
        });
    }, 10000);
</script>
<!-- ======= END **AUTO RUNNABLE** SCRIPTS ======= -->

<script>
    $("#search-form__input").on("keyup", function () {
        var value = this.value.toLowerCase().trim();
        $(".show-search-result a").show().filter(function () {
            return $(this).text().toLowerCase().trim().indexOf(value) == -1;
        }).hide();
    });
</script>

@stack('script')
<!-- ======= End BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS ======= -->
</body>

</html>
