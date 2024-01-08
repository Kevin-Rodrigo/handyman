@extends('providermanagement::layouts.master')

@section('title',translate('business_setup'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="{{asset('public/assets/admin-module')}}/plugins/dataTables/select.dataTables.min.css"/>
@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-wrap mb-3">
                        <h2 class="page-title">{{translate('business_setup')}}</h2>
                    </div>

                    <div class="card">
                        <div class="card-body p-30">
                            <form action="{{route('provider.business-settings.set-business-information')}}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row g-4">
                                    @php($service_man_booking_cancel = collect([ ['key' => 'provider_serviceman_can_cancel_booking','info_message' => 'Service Men Can Cancel Booking', 'title' => 'Cancel Booking Req'] ]))
                                    @php($service_man_booking_edit = collect([ ['key' => 'provider_serviceman_can_edit_booking','info_message' => 'Service Men Can Edit Booking', 'title' => 'Edit Booking Req'] ]))

                                    <div class="col-md-6 col-12">
                                        <div class="border p-3 rounded d-flex justify-content-between">
                                            <div>
                                                <span class="text-capitalize">{{translate($service_man_booking_cancel[0]['title'])}}</span>
                                                <i class="material-icons px-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{translate($service_man_booking_cancel[0]['info_message'] ?? '')}}"
                                                >info</i>
                                            </div>
                                            <label class="switcher">
                                                @php($value = $data_values->where('key_name', $service_man_booking_cancel[0]['key'])?->first()?->live_values ?? null)
                                                <input class="switcher_input" id="{{$service_man_booking_cancel[0]['key']}}" type="checkbox" name="{{$service_man_booking_cancel[0]['key']}}"
                                                        value="1" {{$value ? 'checked' : ''}}
                                                        onclick="switch_alert('{{$service_man_booking_cancel[0]['key']}}', $(this).is(':checked')===true?1:0,  'Want to change the status of {{ucfirst(translate($service_man_booking_cancel[0]['key']))}}')"
                                                >
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="border p-3 rounded d-flex justify-content-between">
                                            <div>
                                                <span class="text-capitalize">{{translate($service_man_booking_edit[0]['title'])}}</span>
                                                <i class="material-icons px-1" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{translate($service_man_booking_edit[0]['info_message'] ?? '')}}"
                                                >info</i>
                                            </div>
                                            <label class="switcher">
                                                @php($value = $data_values->where('key_name', $service_man_booking_edit[0]['key'])?->first()?->live_values ?? null)
                                                <input class="switcher_input" id="{{$service_man_booking_edit[0]['key']}}" type="checkbox" name="{{$service_man_booking_edit[0]['key']}}"
                                                        value="1" {{$value ? 'checked' : ''}}
                                                        onclick="switch_alert('{{$service_man_booking_edit[0]['key']}}', $(this).is(':checked')===true?1:0,  'Want to change the status of {{ucfirst(translate($service_man_booking_edit[0]['key']))}}')"
                                                >
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <button type="reset" class="btn btn-secondary">{{translate('reset')}}
                                    </button>
                                    <button type="submit" class="btn btn--primary">{{translate('update')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End Tab Content -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{asset('public/assets/admin-module')}}/plugins/select2/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.js-select').select2();
        });
    </script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/admin-module')}}/plugins/dataTables/dataTables.select.min.js"></script>

    <script>
        function switch_alert(id, status, message) {
            Swal.fire({
                title: "{{translate('are_you_sure')}}?",
                text: message,
                type: 'warning',
                showDenyButton: true,
                showCancelButton: true,
                denyButtonColor: 'var(--c2)',
                confirmButtonColor: 'var(--c1)',
                confirmButtonText: 'Save',
                denyButtonText: `Don't save`,
            }).then((result) => {
                if (result.value) {
                    {{--Swal.fire('{{translate('Saved changes')}}', '', 'success')--}}
                } else {
                    if (status === 1) $(`#${id}`).prop('checked', false);
                    if (status === 0) $(`#${id}`).prop('checked', true);

                    Swal.fire('{{translate('Changes are not saved')}}', '', 'info')
                }
            })
        }
    </script>
@endpush
