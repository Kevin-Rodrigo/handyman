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
