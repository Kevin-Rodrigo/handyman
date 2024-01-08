@foreach($notifications as $notification)
    <a href="#" class="dropdown-item-text media gap-3">
        <div class="avatar title-color hover-color-c2">
            <span class="material-icons">notifications</span>
        </div>
        <div class="media-body ">
            <img
                onerror="this.src='{{asset('public/assets/provider-module/img/logo-icon.png')}}'"
                src="{{asset('storage/app/public')}}/push-notification/{{$notification->cover_image}}"
                class="avatar rounded-circle">
            <h5 class="card-title">{{$notification->title}}</h5>
            <p class="card-text fz-14 mb-2">{{$notification->description}}</p>
            @php
                $to_time = strtotime($notification->created_at);
                $from_time = strtotime(now());
                $diff = round(abs($to_time - $from_time) / 60,2);
                $time = $diff .' '.translate('min');
                if ($diff>60){
                    $diff = round($diff/60);
                    $time = $diff.' '.translate('hr');
                    if ($diff>24){
                        $diff = round($diff/24);
                        $time = $diff.' '.translate('day');
                         if ($diff>30){
                            $diff = round($diff/30);
                            $time = $diff.' '.translate('month');
                             if ($diff>12){
                                $diff = round($diff/12);
                                $time = $diff.' '.translate('year');
                            }
                        }
                    }
                }
            @endphp
            <span class="card-text fz-12 text-opacity-75">{{$time}} {{translate('ago')}}</span>
        </div>
    </a>
    <div class="dropdown-divider"></div>
@endforeach
