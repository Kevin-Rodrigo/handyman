@extends('providermanagement::layouts.master')

@section('title',translate('Profile_Update'))

@push('css_or_js')

@endpush

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('Update_Profile')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('provider.profile_update') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="c1 mb-30">{{translate('General_Information')}}</h4>
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="company_name" value="{{ $provider->company_name }}"
                                                    placeholder="{{translate('Company_/_Individual_Name')}}">
                                            <label>{{translate('Company_/_Individual_Name')}}</label>
                                        </div>
                                        <div class="form-floating mb-30">
                                            <input type="email" class="form-control" name="company_email" value="{{ $provider->company_email }}"
                                                    placeholder="{{translate('Company_Email')}}">
                                            <label>{{translate('Company_Email')}}</label>
                                        </div>
                                        <div class="form-floating mb-30">
                                            <input oninput="this.value = this.value.replace(/[^+\d]+$/g, '').replace(/(\..*)\./g, '$1');" type="tel" class="form-control" name="company_phone" value="{{ $provider->company_phone }}"
                                                    placeholder="{{translate('Company_Phone')}}">
                                            <label>{{translate('Company_Phone')}}</label>
                                            <small class="d-block mt-1 text-danger">* ( {{translate('Country_Code_Required')}} )</small>
                                        </div>
                                        <div class="form-floating mb-30">
                                            <select class="select-zone theme-input-style w-100" name="zone_id" required>
                                                <option selected disabled>{{translate('Select_Zone')}}</option>
                                                @foreach($zones as $zone)
                                                        <option value="{{$zone->id}}" {{ $provider->zone->id == $zone->id ? 'selected' : '' }}>{{$zone->name}}</option>
                                                @endforeach
                                            </select>
                                            <small class="d-block mt-1 text-danger">* {{translate('Update your latitude & longitude according to the selected zone')}}</small>
                                        </div>
                                        <div class="form-floating mb-30">
                                            <textarea class="form-control" name="company_address"
                                                        placeholder="{{translate('Company_Address')}}">{!! $provider->company_address !!}</textarea>
                                            <label>{{translate('Company_Address')}}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-column align-items-center gap-3">
                                            <h3 class="mb-0">{{translate('Company_Logo')}}</h3>
                                            <div>
                                                <div class="upload-file">
                                                    <input type="file" class="upload-file__input" name="logo">
                                                    <div class="upload-file__img">
                                                        <img
                                                            src="{{asset('storage/app/public/provider/logo')}}/{{ $provider->logo }}"
                                                            onerror="this.src='{{asset('public/assets/provider-module')}}/img/media/upload-file.png'"
                                                            alt="">
                                                    </div>
                                                    <span class="upload-file__edit">
                                                        <span class="material-icons">edit</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="opacity-75 max-w220 mx-auto">Image format - jpg, png,
                                                jpeg,
                                                gif Image
                                                Size -
                                                maximum size 2 MB Image Ratio - 1:1</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h4 class="c1 mb-30">{{translate('Account_Information')}}</h4>
                                        <div class="row gx-2">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-30">
                                                    <input type="text" class="form-control" name="account_first_name" value="{{ $provider->owner->first_name }}"
                                                            placeholder="{{translate('First_Name')}}">
                                                    <label>{{translate('First_Name')}}</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-30">
                                                    <input type="text" class="form-control" name="account_last_name" value="{{ $provider->owner->last_name }}"
                                                            placeholder="{{translate('Last_Name')}}">
                                                    <label>{{translate('Last_Name')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-floating mb-30">
                                            <span class="form-control opacity-50"
                                                  data-bs-toggle="tooltip" data-bs-placement="top"
                                                  data-bs-custom-class="custom-tooltip"
                                                  data-bs-title="{{translate('Not_editable')}}">
                                                {{ $provider->owner->email }}
                                            </span>
                                            <label>{{translate('Email')}}</label>
                                        </div>
                                        <div class="form-floating mb-30">
                                            <span class="form-control opacity-50"
                                                  data-bs-toggle="tooltip" data-bs-placement="top"
                                                  data-bs-custom-class="custom-tooltip"
                                                  data-bs-title="{{translate('Not_editable')}}">{{$provider->owner->phone}}</span>
                                            <label>{{translate('Phone')}}</label>
                                            <small class="d-block mt-1 text-danger">* ( Country Code Required )</small>
                                        </div>
                                        <div class="row gx-2">
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-30">
                                                    <input type="password" class="form-control" name="password"
                                                            placeholder="{{translate('Password')}}"
                                                            autocomplete="off">
                                                    <label>{{translate('Password')}}</label>
                                                    <span class="material-icons togglePassword">visibility_off</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-floating mb-30">
                                                    <input type="password" class="form-control" name="confirm_password"
                                                            placeholder="{{translate('Confirm_Password')}}"
                                                            autocomplete="off">
                                                    <label>{{translate('Confirm_Password')}}</label>
                                                    <span class="material-icons togglePassword">visibility_off</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-wrap justify-content-between gap-3 mb-30">
                                            <h4 class="c1">{{translate('Contact_Person')}}</h4>

{{--                                                    <div class="custom-checkbox">--}}
{{--                                                        <input type="checkbox" class="custom-checkbox__input"--}}
{{--                                                               id="same-as-general" checked>--}}
{{--                                                        <label class="custom-checkbox__label"--}}
{{--                                                               for="same-as-general">{{translate('Same_as_General_Information')}}</label>--}}
{{--                                                    </div>--}}
                                        </div>
                                        <div class="form-floating mb-30">
                                            <input type="text" class="form-control" name="contact_person_name" value="{{ $provider->contact_person_name }}"
                                                    placeholder="{{translate('Name')}}">
                                            <label>{{translate('Name')}}</label>
                                        </div>
                                        <div class="form-floating mb-30">
                                            <input oninput="this.value = this.value.replace(/[^+\d]+$/g, '').replace(/(\..*)\./g, '$1');" type="tel" class="form-control" name="contact_person_phone" value="{{ $provider->contact_person_phone }}"
                                                    placeholder="{{translate('Phone')}}">
                                            <label>{{translate('Phone')}}</label>
                                            <small class="d-block mt-1 text-danger">* ( {{translate('Country_Code_Required')}} )</small>
                                        </div>
                                        <div class="form-floating mb-30">
                                            <input type="email" class="form-control" name="contact_person_email" value="{{ $provider->contact_person_email }}"
                                                    placeholder="{{translate('Business_Email')}}">
                                            <label>{{translate('Business_Email')}}</label>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="latitude" id="latitude"
                                                               placeholder="{{translate('latitude')}} *"
                                                               value="{{$provider->coordinates['latitude'] ?? null}}" required readonly
                                                               data-bs-toggle="tooltip" data-bs-placement="top"
                                                               title="{{translate('Select from map')}}">
                                                        <label>{{translate('latitude')}} *</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-30">
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" name="longitude" id="longitude"
                                                               placeholder="{{translate('longitude')}} *"
                                                               value="{{$provider->coordinates['longitude'] ?? null}}" required readonly
                                                               data-bs-toggle="tooltip" data-bs-placement="top"
                                                               title="{{translate('Select from map')}}">
                                                        <label>{{translate('longitude')}} *</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div id="location_map_div" style="height: 250px">
                                                    <input id="pac-input" class="form-control w-auto" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('search_your_location_here') }}"
                                                        type="text" placeholder="{{ translate('search_here') }}" />
                                                    <div id="location_map_canvas" class="overflow-hidden rounded" style="height: 100%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-4 flex-wrap justify-content-end mt-4">
                                    <button type="reset" class="btn btn--secondary">{{translate('Reset')}}</button>
                                    <button type="submit" class="btn btn--primary demo_check">{{translate('Update')}}</button>
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
    <script src="{{asset('public/assets/provider-module')}}/js/spartan-multi-image-picker.js"></script>
    <script>
        $("#multi_image_picker").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 2,
                rowHeight: '10%',
                groupClassName: 'col-3',
                //maxFileSize: '',
                dropFileLabel : "{{translate('Drop_here')}}",
                placeholderImage: {
                    image: '{{asset('public/assets/provider-module')}}/img/media/upload-file.png',
                    width: '75%',
                },

                onRenderedPreview : function(index){
                    toastr.success('{{translate('Image_added')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onRemoveRow : function(index){
                    console.log(index);
                },
                onExtensionErr : function(index, file){
                    toastr.error('{{translate('Please_only_input_png_or_jpg_type_file')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr : function(index, file){
                    toastr.error('{{translate('File_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        );
        //tooltip enable
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>

    <!-- Map scripts (address) -->
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
    <!-- End -->
@endpush
