<?php
$booking = \Modules\BookingModule\Entities\Booking::where('provider_id', auth()->user()->provider->id)->get();
$max_booking_amount = (business_config('max_booking_amount', 'booking_setup'))->live_values;
$subscribed_sub_category_ids = \Modules\ProviderManagement\Entities\SubscribedService::where(['provider_id' => auth()->user()->provider->id])->ofSubscription(1)->pluck('sub_category_id')->toArray();
$pending_booking_count = \Modules\BookingModule\Entities\Booking::ofBookingStatus('pending')
    ->where('zone_id', auth()->user()->provider->zone_id)
    ->whereIn('sub_category_id', $subscribed_sub_category_ids)
    ->when($max_booking_amount > 0, function($query) use ($max_booking_amount) {
        $query->where(function ($query) use ($max_booking_amount) {
            $query->where('payment_method', 'cash_after_service')
                ->where(function ($query) use ($max_booking_amount) {
                    $query->where('is_verified', 1)
                        ->orWhere('total_booking_amount', '<=', $max_booking_amount);
                })
                ->orWhere('payment_method', '<>', 'cash_after_service');
        });
    })
    ->count();

$accepted_booking_count =\Modules\BookingModule\Entities\Booking::where('provider_id', auth()->user()->provider->id)
   ->when($max_booking_amount > 0, function($query) use ($max_booking_amount) {
    $query->where(function ($query) use ($max_booking_amount) {
        $query->where('payment_method', '!=', 'cash_after_service')
            ->orWhere(function ($query) use ($max_booking_amount) {
                $query->where('payment_method', 'cash_after_service')
                    ->where('total_booking_amount', '<=', $max_booking_amount)
                    ->orWhere('is_verified', 1);
            });
    });
})->where('booking_status', 'accepted')->count();

$logo = business_config('business_logo', 'business_information');
?>

@php($provider = auth()->user()->provider)
<aside class="aside">
    <!-- Aside Header -->
    <div class="aside-header">
        <!-- Logo -->
        <a href="{{route('admin.dashboard')}}" class="logo d-flex gap-2">
            <img src="{{asset('storage/app/public/business')}}/{{$logo->live_values??""}}"
                 onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                 style="max-height: 50px" alt=""
                 class="main-logo">
            <img src="{{asset('storage/app/public/business')}}/{{$logo->live_values??""}}"
                 onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                 style="max-height: 40px" alt=""
                 class="mobile-logo">
        </a>
        <!-- End Logo -->

        <!-- Aside Toggle Menu Button -->
        <button class="toggle-menu-button aside-toggle border-0 bg-transparent p-0 dark-color">
            <span class="material-icons">menu</span>
        </button>
        <!-- End Aside Toggle Menu Button -->
    </div>
    <!-- End Aside Header -->

    <!-- Aside Body -->
    <div class="aside-body" data-trigger="scrollbar">
        <div class="user-profile media gap-3 align-items-center my-3">
            <div class="avatar">
                <img class="avatar-img rounded-circle"
                     src="{{asset('storage/app/public/provider/logo')}}/{{ $provider->logo }}"
                     onerror="this.src='{{asset('public/assets/provider-module')}}/img/user2x.png'"
                     alt="">
            </div>
            <div class="media-body ">
                <h5 class="card-title">{{ Str::limit($provider->company_email, 30) }}</h5>
                <span class="card-text">{{ Str::limit($provider->company_name, 30) }}</span>
            </div>
        </div>

        <!-- Nav -->
        <ul class="nav">
            <li class="nav-category">{{translate('main')}}</li>
            <li>
                <a href="{{route('provider.dashboard')}}"
                   class="{{request()->is('provider/dashboard')?'active-menu':''}}">
                    <span class="material-icons" title="{{translate('dashboard')}}">dashboard</span>
                    <span class="link-title">{{translate('dashboard')}}</span>
                </a>
            </li>

            <li class="nav-category"
                title="{{translate('Service_Management')}}">{{translate('Service_Management')}}</li>
            <li>
                <a href="{{route('provider.service.available')}}"
                   class="{{request()->is('provider/service/*')?'active-menu':''}}">
                    <span class="material-icons" title="{{translate('available_services')}}">home_repair_service</span>
                    <span class="link-title">{{translate('available_services')}}</span>
                </a>
            </li>
            <li>
                <a href="{{route('provider.sub_category.subscribed', ['status'=>'all'])}}"
                   class="{{request()->is('provider/sub-category/subscribed*')?'active-menu':''}}">
                    <span class="material-icons" title="{{translate('my_Subscriptions')}}">subscriptions</span>
                    <span class="link-title">{{translate('my_subscriptions')}}</span>
                </a>
            </li>


            <li class="has-sub-item {{request()->is('provider/serviceman/*')?'sub-menu-opened':''}}">
                <a href="#" class="{{request()->is('provider/serviceman/*')?'active-menu':''}}">
                    <span class="material-icons" title="{{translate('Service_Man')}}">man</span>
                    <span class="link-title">{{translate('Service_Man')}}</span>
                </a>
                <!-- Sub Menu -->
                <ul class="nav sub-menu">
                    <li>
                        <a href="{{route('provider.serviceman.list', ['status'=>'all'])}}"
                           class="{{request()->is('provider/serviceman/list')?'active-menu':''}}">
                            {{translate('Serviceman_List')}}
                        </a>
                    </li>
                    <li>
                        <a href="{{route('provider.serviceman.create')}}"
                           class="{{request()->is('provider/serviceman/create')?'active-menu':''}}">
                            {{translate('add_new_serviceman')}}
                        </a>
                    </li>
                </ul>
                <!-- End Sub Menu -->
            </li>


            <li class="nav-category" title="{{translate('booking_management')}}">
                {{translate('booking_management')}}
            </li>
            <li class="has-sub-item {{request()->is('provider/booking/*')?'sub-menu-opened':''}}">
                <a href="#" class="{{request()->is('provider/booking/*')?'active-menu':''}}">
                    <span class="material-icons" title="{{translate('bookings')}}">shopping_cart</span>
                    <span class="link-title">{{translate('bookings')}}</span>
                </a>

                <!-- Sub Menu -->
                <ul class="nav sub-menu">
                    @php($bidding_status = (int)((business_config('bidding_status', 'bidding_system'))->live_values ?? 0))
                    @if($bidding_status)
                            <?php
                            $ignored_posts = \Modules\BidModule\Entities\IgnoredPost::where('provider_id', auth()->user()->provider->id)->pluck('post_id')->toArray();
                            $bidding_post_validity = (int)(business_config('bidding_post_validity', 'bidding_system'))->live_values;
                            $posts = \Modules\BidModule\Entities\Post::where('is_booked', 0)
                                ->whereNotIn('id', $ignored_posts)
                                ->whereIn('sub_category_id', $subscribed_sub_category_ids)
                                ->where('zone_id', auth()->user()->provider->zone_id)
                                ->whereBetween('created_at', [Carbon\Carbon::now()->subDays($bidding_post_validity), Carbon\Carbon::now()])
                                ->get();

                            foreach ($posts as $key=>$post) {
                                if ($post->bids) {
                                    foreach ($post->bids as $bid) {
                                        if ($bid->status == 'denied') unset($posts[$key]);
                                    }
                                }
                            }

                            $posts = $posts->count();
                            ?>
                        <li>
                            <a href="{{route('provider.booking.post.list', ['type'=>'all'])}}"
                               class="{{request()->is('provider/booking/post') || request()->is('provider/booking/post/details*') ? 'active-menu' : ''}}">
                                <span class="link-title">{{translate('Customized_Requests')}}
                                    <span class="count">{{$posts??0}}</span>
                                </span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{route('provider.booking.list', ['booking_status'=>'pending'])}}"
                           class="{{request()->is('provider/booking/list') && request()->query('booking_status')=='pending'?'active-menu':''}}">
                            <span class="link-title">{{translate('Booking_Requests')}}
                                <span class="count">{{$pending_booking_count}}</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('provider.booking.list', ['booking_status'=>'accepted'])}}"
                           class="{{request()->is('provider/booking/list') && request()->query('booking_status')=='accepted'?'active-menu':''}}">
                            <span class="link-title">{{translate('Accepted')}}
                                <span class="count">{{$accepted_booking_count}}</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('provider.booking.list', ['booking_status'=>'ongoing'])}}"
                           class="{{request()->is('provider/booking/list') && request()->query('booking_status')=='ongoing'?'active-menu':''}}">
                            <span class="link-title">{{translate('Ongoing')}}
                                <span class="count">{{$booking->where('booking_status', 'ongoing')->count()}}</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('provider.booking.list', ['booking_status'=>'completed'])}}"
                           class="{{request()->is('provider/booking/list') && request()->query('booking_status')=='completed'?'active-menu':''}}">
                            <span class="link-title">{{translate('Completed')}}
                                <span class="count">{{$booking->where('booking_status', 'completed')->count()}}</span>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('provider.booking.list', ['booking_status'=>'canceled'])}}"
                           class="{{request()->is('provider/booking/list') && request()->query('booking_status')=='canceled'?'active-menu':''}}">
                            <span class="link-title">{{translate('Canceled')}}
                                <span class="count">{{$booking->where('booking_status', 'canceled')->count()}}</span>
                            </span>
                        </a>
                    </li>
                </ul>
                <!-- End Sub Menu -->
            </li>

            <li class="nav-category" title="{{translate('account')}}">{{translate('account_management')}}</li>
            <li>
                <a href="{{route('provider.account_info', ['page_type'=>'overview'])}}"
                   class="{{request()->is('provider/account-info*') || request()->is('provider/withdraw') ?'active-menu':''}}">
                    <span class="material-icons" title="{{translate('Account_Information')}}">account_circle</span>
                    <span class="link-title">{{translate('Account_Information')}}</span>
                </a>
            </li>
            <li>
                <a href="{{route('provider.bank_info')}}"
                   class="{{request()->is('provider/bank-info*')?'active-menu':''}}">
                    <span class="material-icons" title="{{translate('bank_information')}}">account_balance</span>
                    <span class="link-title">{{translate('bank_information')}}</span>
                </a>
            </li>
            <!-- business settings -->
            <li class="nav-category"
            title="{{translate('system_management')}}">{{translate('system_management')}}</li>
            <li>
                <a href="{{route('provider.business-settings.get-business-information')}}"
                class="{{request()->is('provider/business-settings/get-business-information')?'active-menu':''}}">
                    <span class="material-icons" title="Business Settings">business_center</span>
                    <span class="link-title">{{translate('business_settings')}}</span>
                </a>
            </li>
            <!-- Report -->
            <li class="nav-category" title="{{translate('report_management')}}">
                {{translate('report_management')}}
            </li>
            <li class="has-sub-item {{request()->is('provider/report/*')?'sub-menu-opened':''}}">
                <a href="#" class="{{request()->is('provider/report/*')?'active-menu':''}}">
                    <span class="material-icons" title="Customers">event_note</span>
                    <span class="link-title">{{translate('Reports')}}</span>
                </a>
                <!-- Sub Menu -->
                <ul class="nav sub-menu">
                    <li>
                        <a href="{{route('provider.report.transaction', ['transaction_type'=>'all'])}}"
                           class="{{request()->is('provider/report/transaction')?'active-menu':''}}">
                            {{translate('transaction')}}
                        </a>
                    </li>
                    <li>
                        <a href="{{route('provider.report.business.overview')}}"
                           class="{{request()->is('provider/report/business*')?'active-menu':''}}">
                            {{translate('business')}}
                        </a>
                    </li>
                    <li>
                        <a href="{{route('provider.report.booking')}}"
                           class="{{request()->is('provider/report/booking')?'active-menu':''}}">
                            {{translate('booking')}}
                        </a>
                    </li>
                </ul>
                <!-- End Sub Menu -->
            </li>

            <!-- Help & support -->
            <li class="nav-category">{{translate('Help & support')}}</li>
            <li>
                <a href="{{route('provider.service.request-list')}}"
                   class="{{request()->is('provider/service/request-list*') || request()->is('provider/service/make-request*')?'active-menu':''}}">
                    <span class="material-icons" title="{{translate('Request for Service')}}">list</span>
                    <span class="link-title">{{translate('Service Requests')}}</span>
                </a>
            </li>
        </ul>
        <!-- End Nav -->
    </div>
    <!-- End Aside Body -->
</aside>
