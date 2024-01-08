<!-- Inbox Message Header -->
<div
    class="inbox_msg_header d-flex flex-wrap gap-3 justify-content-between align-items-center border px-3 py-2 rounded mb-4">
    <!-- Profile -->
    <div class="media align-items-center gap-3">
        <div class="position-relative">
            <img
                onerror="this.src='{{asset('public/assets/admin-module/img/user2x.png')}}'"
                @if(isset($from_user->user) && $from_user->user->user_type == 'customer')
                src="{{asset('storage/app/public')}}/user/profile_image/{{isset($from_user->user)?$from_user->user->profile_image:'def.png'}}"
                @elseif(isset($from_user->user) && $from_user->user->user_type == 'provider-admin')
                src="{{asset('storage/app/public')}}/provider/logo/{{isset($from_user->user->provider)?$from_user->user->provider->logo:'def.png'}}"
                @elseif(isset($from_user->user) && $from_user->user->user_type == 'provider-serviceman')
                src="{{asset('storage/app/public')}}/serviceman/profile/{{isset($from_user->user)?$from_user->user->profile_image:'def.png'}}"
                @endif
                class="avatar rounded-circle">
            <span class="avatar-status bg-success"></span>
        </div>
        <div class="media-body">
            <h5 class="profile-name">{{isset($from_user->user)?$from_user->user->first_name:translate('no_user_found')}}</h5>
            <span class="fz-12">{{isset($from_user->user)?$from_user->user->phone:''}}</span>
        </div>
    </div>
    <!-- End Profile -->
</div>
<!-- End Inbox Message Header -->

<div class="messaging">
    <div class="inbox_msg d-flex flex-column-reverse" data-trigger="scrollbar">
        <div class="upload_img"></div>
        <div class="upload_file"></div>
        @php($format=['jpg','png','jpeg','JPG','PNG','JPEG'])
        @foreach($conversation as $chat)
            @if($chat->user->id==auth()->user()->id)
                <div class="outgoing_msg">
                    @if($chat->message!=null)
                        <p class="message_text">
                            {{$chat->message}}
                        </p>
                    @endif

                    @if(count($chat->conversationFiles)>0)
                        @foreach($chat->conversationFiles as $file)
                            @if(in_array($file->file_type,$format))
                                <img width="150"
                                     src="{{asset('storage/app/public/conversation')}}/{{$file->file_name}}">
                            @else
                                <a href="{{asset('storage/app/public/conversation')}}/{{$file->file_name}}"
                                   download>{{$file->file_name}}</a>
                            @endif
                        @endforeach
                    @endif
                    <span class="time_date d-flex justify-content-end">
                        {{date('H:i a | M d',strtotime($chat->created_at))}}
                    </span>
                </div>
            @else
                <div class="received_msg">
                    @if($chat->message!=null)
                        <p class="message_text">
                            {{$chat->message}}
                        </p>
                    @endif

                    @if(count($chat->conversationFiles)>0)
                        @foreach($chat->conversationFiles as $file)
                            @if($file->file_type=='png')
                                <img width="150"
                                     src="{{asset('storage/app/public/conversation')}}/{{$file->file_name}}">
                            @else
                                <a href="{{asset('storage/app/public/conversation')}}/{{$file->file_name}}"
                                   download>{{$file->file_name}}</a>
                            @endif
                        @endforeach
                    @endif
                    <span class="time_date"> {{date('H:i a | M d',strtotime($chat->created_at))}}</span>
                </div>
            @endif
        @endforeach
    </div>

    <div class="type_msg">
        <form class="mt-4" id="send-sms-form">
            <div class="input_msg_write">
                <input name="channel_id" class="hide-div" value="{{$channel_id}}"
                       id="chat-channel-id">
                <textarea class="form-control h-120" id="msgInputValue" type="text"
                          placeholder="{{translate('send_a_message')}}"
                          aria-label="Search" name="message"></textarea>
                <div class="send-msg-btns d-flex justify-content-end">
                    <div class="add-img">
                        <span class="material-icons">add_photo_alternate</span>
                        <input type="file" class="file_input img_input" name="files[]" multiple>
                    </div>
                    <div class="add-attatchment">
                        <span class="material-icons">attach_file</span>
                        <input type="file" class="file_input document_input" name="files[]" multiple>
                    </div>
                    <button class="" type="button" id="btnSendData">
                        <span class="material-icons">send</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('#btnSendData').on('click', function () {
        var form = $('#send-sms-form')[0];
        var formData = new FormData(form);
        // Set header if need any otherwise remove setup part
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('admin.chat.send-message')}}",// your request url
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (response) {
                $('.inbox_msg').html(response.template);
                $(".file_input").val("");
                $("#send-sms-form")[0].reset();
            },
            error: function () {

            }
        });
    });

    //Upload File
    $(".type_msg .img_input").on("change", function (e) {
        var filename = $(e.target).val().split('\\').pop();
        $(".messaging .upload_img").html( "<div class='d-flex justify-content-between gap-2 align-items-center show-upload-file'><span class=''>" + filename + "</span><span class='material-icons upload-file-close'>close</span></div>" );
        $(".messaging .inbox_msg").scrollTop( 0 );
        $('.upload-file-close').on('click', function() {
            $(this).parents('.show-upload-file').remove();
            $(".type_msg .img_input").val(null);
        });
    });
    $(".type_msg .document_input").on("change", function (e) {
        var filename = $(e.target).val().split('\\').pop();
        $(".messaging .upload_file").html( "<div class='d-flex justify-content-between gap-2 align-items-center show-upload-file'><span class=''>" + filename + "</span><span class='material-icons upload-file-close'>close</span></div>" );
        $(".messaging .inbox_msg").scrollTop( 0 );
        $('.upload-file-close').on('click', function() {
            $(this).parents('.show-upload-file').remove();
            $(".type_msg .document_input").val(null);
        });
    });
</script>
