@extends('providermanagement::layouts.master')

@section('title',translate('chat_list'))

@push('css_or_js')

@endpush

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="page-title-wrap mb-3">
                <h2 class="page-title">{{translate('chat_module')}}</h2>
            </div>

            <div class="row gx-1">
                <div class="col-xl-3 col-lg-4">
                    <div class="card card-body px-0 h-100">
                        <div class="media align-items-center px-3 gap-3 mb-4">
                            <div class="position-relative">
                                <img
                                    onerror="this.src='{{asset('public/assets/provider-module/img/user2x.png')}}'"
                                    src="{{asset('storage/app/public')}}/provider/logo/{{auth()->user()->provider->logo}}"
                                    class="avatar rounded-circle">
                                <span class="avatar-status bg-success"></span>
                            </div>
                            <div class="media-body">
                                <h5 class="profile-name">{{auth()->user()->provider->company_name}}</h5>
                                <!-- <span class="fz-12">Super Admin</span> -->
                            </div>
                        </div>

                        <div class="inbox_people">
                            <form action="#" class="search-form mx-3">
                                <div class="input-group search-form__input_group">
                                        <span class="search-form__icon">
                                            <span class="material-icons">search</span>
                                        </span>
                                    <input type="search" class="h-40 flex-grow-1 search-form__input" id="chat-search"
                                           placeholder="{{translate('search_here')}}">
                                </div>
                            </form>

                            <div class="inbox_chat d-flex flex-column mt-1">
                                @foreach($chat_list as $chat)
                                    @php($from_user=$chat->channelUsers->where('user_id','!=',auth()->id())->first())
                                    <div class="chat_list {{$chat->is_read==0?'active':''}}" id="chat-{{$chat->id}}"
                                         onclick="fetch_conversation('{{route('provider.chat.ajax-conversation',['channel_id'=>$chat->id,'offset'=>1])}}','{{$chat->id}}')">
                                        <div class="chat_people media gap-10" id="chat_people">
                                            <div class="position-relative">
                                                <img
                                                    onerror="this.src='{{asset('public/assets/provider-module/img/user2x.png')}}'"
                                                    @if(isset($from_user->user) && $from_user->user->user_type == 'super-admin')
                                                    src="{{asset('storage/app/public')}}/user/profile_image/{{isset($from_user->user)?$from_user->user->profile_image:'def.png'}}"
                                                    @elseif(isset($from_user->user) && $from_user->user->user_type == 'provider-serviceman')
                                                    src="{{asset('storage/app/public')}}/serviceman/profile/{{isset($from_user->user)?$from_user->user->profile_image:'def.png'}}"
                                                    @elseif(isset($from_user->user) && $from_user->user->user_type == 'customer')
                                                    src="{{asset('storage/app/public')}}/user/profile_image/{{isset($from_user->user)?$from_user->user->profile_image:'def.png'}}"
                                                    @endif
                                                    class="avatar rounded-circle">
                                                <span class="avatar-status bg-success"></span>
                                            </div>
                                            <div class="chat_ib media-body">
                                                <h5 class="">{{isset($from_user->user)?$from_user->user->first_name:translate('no_user_found')}}</h5>
                                                @php($phone_visibility = business_config('phone_number_visibility_for_chatting', 'business_information')->live_values ?? '0')
                                                @if($phone_visibility == 1 || (isset($from_user->user) && $from_user->user->user_type != 'customer'))
                                                    <span class="fz-12">{{isset($from_user->user)?$from_user->user->phone:''}}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($chat->is_read==0)
                                            <div class="bg-info text-white radius-50 px-1 fz-12"
                                                 id="badge-{{$chat->id}}">
                                                <span class="material-symbols-outlined">swipe_up</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8 mt-4 mt-lg-0">
                    <div class="card-header d-flex justify-content-end radius-10 mb-1">
                        <button class="btn btn--primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#modal-conversation-start">
                            <span class="material-icons">add</span>
                            {{translate('start_conversation')}}
                        </button>
                    </div>
                    <div class="card card-body card-chat justify-content-between" id="set-conversation">
                        <h4 class="d-flex align-items-center justify-content-center my-auto gap-2">
                            <span class="material-icons">chat</span>
                            {{translate('start_conversation')}}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-conversation-start" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <label for="with-user" class="d-flex gap-2 fw-semibold">
                        <span class="material-icons">chat</span>
                        {{translate('with_user')}}
                    </label>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{route('provider.chat.create-channel')}}" method="post">
                    @csrf
                    <div class="modal-body p-30">
                        <div class="form-group mb-30">
                            <select class="form-control" name="user_type" id="user_type">
                                <option value="0" selected disabled>{{translate('Select_User_Type')}}</option>
                                <option value="super-admin">{{translate('super-admin')}}</option>
                                <option value="provider-serviceman">{{translate('serviceman')}}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control" id="super-admin" name="super-admin">
                                <option value="0" selected disabled>{{translate('Select_User')}}</option>
                                @foreach($super_admin as $admin)
                                    <option value="{{$admin->id}}">
                                        {{$admin->first_name . ' ' . $admin->last_name}} ({{$admin->phone}})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-30" id="serviceman" style="display: none">
                            <select class="form-control js-select" name="serviceman_id">
                                <option value="0" disabled selected>{{translate('---Select_Serviceman---')}}</option>
                                @foreach($servicemen as $item)
                                    <option value="{{$item->user->id}}">
                                        {{$item->user->first_name??'' . ' '.$item->user->last_name??''}} ({{$item->user->phone}})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--secondary" data-bs-dismiss="modal" aria-label="Close">{{translate('close')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('start')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        "use Strict"

        function fetch_conversation(route, chat_id) {
            $.get({
                url: route,
                dataType: 'json',
                data: {},
                beforeSend: function () {
                    /*$('#loading').show();*/
                },
                success: function (response) {
                    /* console.log(response.template) */
                    $('#set-conversation').empty().html(response.template);
                    document.getElementById('chat-' + chat_id).classList.remove("active");
                    document.getElementById('badge-' + chat_id).classList.add("hide-div");
                },
                complete: function () {
                    /*$('#loading').hide();*/
                },
            });
        }
    </script>

    <script>
        $("#chat-search").on("keyup", function () {
            var value = this.value.toLowerCase().trim();
            $(".inbox_chat div").show().filter(function () {
                return $(this).text().toLowerCase().trim().indexOf(value) == -1;
            }).hide();
        });
    </script>

    <script>
        "use Strict"
        $('#user_type').on('change', function () {
            if(this.value==='super-admin'){
                $('#super-admin').show();
                $('#serviceman').hide();
            }else if(this.value==='provider-serviceman'){
                $('#super-admin').hide();
                $('#serviceman').show();
            }
        });
    </script>
@endpush
