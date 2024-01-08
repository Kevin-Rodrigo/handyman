<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Page Title -->
    <title>{{translate('Provider_Registration')}}</title>

    <!-- Meta Data -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <!-- Favicon -->
    <link rel="shortcut icon"
          href="{{asset('storage/app/public/business')}}/{{(business_config('business_favicon', 'business_information'))->live_values ?? null}}"/>

    <!-- Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet"/>

    <!-- ======= BEGIN GLOBAL MANDATORY STYLES ======= -->
    <link href="{{asset('public/assets/provider-module')}}/css/material-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.css"/>
    <!-- ======= END BEGIN GLOBAL MANDATORY STYLES ======= -->

    <!-- ======= MAIN STYLES ======= -->
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/style.css" />
    <!-- ======= END MAIN STYLES ======= -->
    <link rel="stylesheet" href="{{asset('public/assets/provider-module')}}/css/toastr.css">
</head>

<body>
<!-- Preloader -->
<div class="preloader"></div>
<!-- End Preloader -->

<!-- Login Form -->
<div class="register-form dark-support" data-bg-img="{{asset('public/assets/provider-module')}}/img/media/login-bg.png">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <form action="{{route('provider.auth.sign-up-submit')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card my-5 ov-hidden">
                        <div class="register-wrap">
                            <div class="register-left">
                                <img class="login-img" src="{{asset('public/assets/provider-module')}}/img/media/login-img.png"
                                     alt="">
                            </div>
                            <div class="register-right-wrap card-body px-xl-5">
                                <div class="text-center mb-5">
                                    <img class="login-img login-logo mb-2"
                                         src="{{asset('storage/app/public/business')}}/{{business_config('business_logo', 'business_information')->live_values??''}}"
                                         onerror="this.src='{{asset('public/assets/provider-module/img/logo.png')}}'"
                                         alt="">
                                    <h5 class="text-uppercase c1 mb-3">{{ (business_config('business_name', 'business_information'))->live_values ?? 'Demandium' }}</h5>
                                </div>

                                <div id="register-vertical-steps">
                                    <h3>Step 1 <span class="step-info">{{translate('General_Information')}}</span></h3>
                                    <section>
                                        <div class="">
                                            <div class="mb-4">
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" value="{{old('company_name')}}" name="company_name" placeholder="{{translate('Company_/_Individual_Name')}}">
                                                        <label>{{translate('Company_/_Individual_Name')}}</label>
                                                    </div>
                                                </div>
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="tel" oninput="this.value = this.value.replace(/[^+\d]+$/g, '').replace(/(\..*)\./g, '$1');" class="form-control" value="{{old('company_phone')}}" name="company_phone" placeholder="{{translate('Phone')}}">
                                                        <label>{{translate('Company_Phone')}}</label>
                                                        <small class="text-danger d-flex mt-1">{{translate('* ( Country_Code_Required )')}}</small>
                                                    </div>
                                                </div>
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="email" class="form-control" value="{{old('company_email')}}" name="company_email" placeholder="{{translate('Email')}}">
                                                        <label>{{translate('Company_Email')}}</label>
                                                    </div>
                                                </div>
                                                <div class="mb-30">
                                                    <select name="zone_id" id="zone_id" class="form-control">
                                                        <option value="0" selected disabled>{{translate('Select_Zone')}}</option>
                                                        @foreach($zones as $zone)
                                                            <option value="{{$zone->id}}" {{old('zone_id')==$zone->id?'selected':''}}>{{$zone->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-floating">
                                                        <textarea class="form-control" name="company_address" placeholder="{{translate('Address')}}">{{old('company_address')}}</textarea>
                                                        <label>{{translate('Address')}}</label>
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <div class="d-flex flex-wrap justify-content-between gap-3">
                                                        <p class="opacity-75 max-w220">{{translate('Image_format_-_jpg,_png,_jpeg,_gif_Image_Size_-_maximum_size_2_MB_Image_Ratio_-_1:1')}}</p>

                                                        <div>
                                                            <div class="upload-file">
                                                                <input type="file" class="upload-file__input" name="logo">
                                                                <span class="upload-file__edit">
                                                                    <span class="material-icons">edit</span>
                                                                </span>
                                                                <div class="upload-file__img">
                                                                    <img src="{{asset('public/assets/provider-module')}}/img/media/upload-file.png"
                                                                         alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <h3>Step 2 <span class="step-info">{{translate('Contact_Person_Info')}}</span></h3>
                                    <section>
                                        <div class="">
                                            <div class="mb-4">
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" value="{{old('contact_person_name')}}" name="contact_person_name" placeholder="{{translate('Contact_Person_Name')}}">
                                                        <label>{{translate('Name')}}</label>
                                                    </div>
                                                </div>
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="tel" oninput="this.value = this.value.replace(/[^+\d]+$/g, '').replace(/(\..*)\./g, '$1');" class="form-control" value="{{old('contact_person_phone')}}" name="contact_person_phone" placeholder="{{translate('Contact_Person_Phone')}}">
                                                        <label>{{translate('Phone')}}</label>
                                                        <smal class="text-danger d-flex mt-1">{{translate('* ( Country_Code_Required )')}}</smal>
                                                    </div>
                                                </div>
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="email" class="form-control" value="{{old('contact_person_email')}}" name="contact_person_email" placeholder="{{translate('Email')}}">
                                                        <label>{{translate('Email')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <h3>Step 3 <span class="step-info">{{translate('Business_Information')}}</span></h3>
                                    <section>
                                        <div class="">
                                            <div class="mb-4">
                                                <div class="mb-30">
                                                    <select name="identity_type" id="identity_type" class="form-control">
                                                        <option value="0" selected disabled>{{translate('Identity_Type')}}</option>
                                                        <option value="passport" {{old('identity_type')=='passport'?'selected':''}}>{{translate('passport')}}</option>
                                                        <option value="nid" {{old('identity_type')=='nid'?'selected':''}}>{{translate('nid')}}</option>
                                                        <option value="driving_licence" {{old('identity_type')=='driving_licence'?'selected':''}}>{{translate('driving_licence')}}</option>
                                                        <option value="trade_license" {{old('identity_type')=='trade_license'?'selected':''}}>{{translate('trade_license')}}</option>
                                                        <option value="company_id" {{old('identity_type')=='company_id'?'selected':''}}>{{translate('company_id')}}</option>
                                                    </select>
                                                </div>
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" value="{{old('identity_number')}}" name="identity_number" placeholder="{{translate('Identity_Number')}}">
                                                        <label>{{translate('Identity_Number')}}</label>
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <div class="mb-3">{{translate('Identity_Image')}}</div>
                                                    <div id="multi_image_picker2" class="row"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <h3>Step 4 <span class="step-info">{{translate('Account_Information')}}</span></h3>
                                    <section>
                                        <div class="">
                                            <div class="mb-4">
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" value="{{old('account_first_name')}}" name="account_first_name" placeholder="{{translate('First_Name')}}">
                                                        <label>{{translate('First_Name')}}</label>
                                                    </div>
                                                </div>
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" value="{{old('account_last_name')}}" name="account_last_name" placeholder="{{translate('Last_Name')}}">
                                                        <label>{{translate('Last_Name')}}</label>
                                                    </div>
                                                </div>
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="tel" oninput="this.value = this.value.replace(/[^+\d]+$/g, '').replace(/(\..*)\./g, '$1');" class="form-control" value="{{old('account_phone')}}" name="account_phone" placeholder="{{translate('Phone')}}">
                                                        <label>{{translate('Phone')}}</label>
                                                        <small class="text-danger d-flex mt-1">* ( {{translate('country_code_required')}} )</small>
                                                    </div>
                                                </div>
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="email" class="form-control" value="{{old('account_email')}}" name="account_email" placeholder="{{translate('Email')}}">
                                                        <label>{{translate('Email')}}</label>
                                                    </div>
                                                </div>
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <span class="material-icons togglePassword">visibility_off</span>
                                                        <input type="password" class="form-control" value="" name="password" placeholder="{{translate('Password')}}">
                                                        <label>{{translate('Password')}}</label>
                                                    </div>
                                                </div>
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <span class="material-icons togglePassword">visibility_off</span>
                                                        <input type="password" class="form-control" value="" name="confirm_password" placeholder="{{translate('Confirm_Password')}}">
                                                        <label>{{translate('Confirm_Password')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <h3>Step 5 <span class="step-info">{{translate('Address_Information')}}</span></h3>
                                    <section>
                                        <div class="">
                                            <div class="row g-3">
                                                <!-- Latitude -->
                                                <div class="col-12">
                                                    <div class="form-group mb-0">
                                                        <label class="form-label text-capitalize"
                                                               for="latitude">{{ translate('latitude') }}</label>
                                                        <input type="text" id="latitude" name="latitude"
                                                               class="form-control"
                                                               placeholder="{{ translate('Ex:') }} 23.8118428"
                                                               value="{{ old('latitude') }}" required readonly
                                                               data-bs-toggle="tooltip" data-bs-placement="top"
                                                               title="{{translate('Select from map')}}">
                                                    </div>
                                                </div>

                                                <!-- Longitude -->
                                                <div class="col-12">
                                                    <div class="form-group mb-0">
                                                        <label class="form-label text-capitalize"
                                                               for="longitude">{{ translate('longitude') }}</label>
                                                        <input type="text" step="0.1" name="longitude"
                                                               class="form-control"
                                                               placeholder="{{ translate('Ex:') }} 90.356331"
                                                               id="longitude"
                                                               value="{{ old('longitude') }}" required readonly
                                                               data-bs-toggle="tooltip" data-bs-placement="top"
                                                               title="{{translate('Select from map')}}">
                                                    </div>
                                                </div>

                                                <!-- Map -->
                                                <div class="col-12">
                                                    <div id="location_map_div" style="height: 250px">
                                                        <input id="pac-input" class="form-control w-auto" data-toggle="tooltip"
                                                           data-placement="right"
                                                           data-original-title="{{ translate('search_your_location_here') }}"
                                                           type="text" placeholder="{{ translate('search_here') }}"/>
                                                        <div id="location_map_canvas" class="overflow-hidden rounded h-100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" style="display:none;"></button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Login Form -->

<!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->
<script src="{{asset('public/assets/provider-module')}}/js/jquery-3.6.0.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/main.js"></script>
<!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->

{{--toastr and sweetalert--}}
<script src="{{asset('public/assets/provider-module')}}/js/sweet_alert.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

<!-- ======= BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS ======= -->
<script src="{{asset('public/assets/provider-module')}}/plugins/jquery-steps/jquery.steps.min.js"></script>

<script>
    (function ($) {
        "use strict";

        $("#register-vertical-steps").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "fade",
            stepsOrientation: "vertical",
            autoFocus: true,
            onFinished: function (event, currentIndex) {
                $('button[type="submit"]').trigger('click');
            }
        });

    })(jQuery);
</script>

<script src="{{asset('public/assets/provider-module')}}/js//tags-input.min.js"></script>
<script src="{{asset('public/assets/provider-module')}}/js/spartan-multi-image-picker.js"></script>
<script>
    $("#multi_image_picker2").spartanMultiImagePicker({
        fieldName: 'identity_images[]',
        maxCount: 2,
        allowedExt: 'png|jpg|jpeg',
        rowHeight: 'auto',
        groupClassName: 'col-6',
        //maxFileSize: '',
        dropFileLabel: "{{translate('Drop_here')}}",
        placeholderImage: {
            image: '{{asset('public/assets/admin-module')}}/img/media/banner-upload-file.png',
            width: '100%',
        },

        onRenderedPreview: function (index) {
            toastr.success('{{translate('Image_added')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        },
        onRemoveRow: function (index) {
            console.log(index);
        },
        onExtensionErr: function (index, file) {
            toastr.error('{{translate('Please_only_input_png_or_jpg_type_file')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        },
        onSizeErr: function (index, file) {
            toastr.error('{{translate('File_size_too_big')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }

    });
</script>
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



<script src="https://maps.googleapis.com/maps/api/js?key={{business_config('google_map', 'third_party')?->live_values['map_api_key_client']}}&libraries=places&v=3.45.8"></script>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewer').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileEg1").change(function () {
        readURL(this);
    });


    $( document ).ready(function() {
        function initAutocomplete() {
            var myLatLng = {

                lat: 23.811842872190343,
                lng: 90.356331
            };
            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: {
                    lat: 23.811842872190343,
                    lng: 90.356331
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];


                geocoder.geocode({
                    'latLng': latlng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').innerHtml = results[1].formatted_address;
                        }
                    }
                });
            });
            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function(event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();
                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        initAutocomplete();
    });


    $('.__right-eye').on('click', function(){
        if($(this).hasClass('active')) {
            $(this).removeClass('active')
            $(this).find('i').removeClass('tio-invisible')
            $(this).find('i').addClass('tio-hidden-outlined')
            $(this).siblings('input').attr('type', 'password')
        }else {
            $(this).addClass('active')
            $(this).siblings('input').attr('type', 'text')


            $(this).find('i').addClass('tio-invisible')
            $(this).find('i').removeClass('tio-hidden-outlined')
        }
    })
</script>
<!-- ======= End BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS ======= -->
</body>
</html>
