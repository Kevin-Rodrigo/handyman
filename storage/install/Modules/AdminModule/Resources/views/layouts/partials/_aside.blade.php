<?php
$booking = \Modules\BookingModule\Entities\Booking::get();
$max_booking_amount = (business_config('max_booking_amount', 'booking_setup'))->live_values??0;
$pending_booking_count = \Modules\BookingModule\Entities\Booking::where('booking_status', 'pending')
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
$accepted_booking_count = \Modules\BookingModule\Entities\Booking::where('booking_status', 'accepted')
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
$pending_providers = \Modules\ProviderManagement\Entities\Provider::ofApproval(2)->count();
$logo = business_config('business_logo', 'business_information');
?>

<aside class="aside">
    <!-- Aside Header -->
    <div class="aside-header">
        <!-- Logo -->
        <!-- <a href="{{route('admin.dashboard')}}" class="logo d-flex gap-2"> -->
            <a href="{{route('admin.dashboard')}}" class="logo d-flex gap-2">
                <img src="{{asset('storage/app/public/business')}}/{{$logo->live_values??""}}"
                     onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                     alt="" class="main-logo">
                <img src="{{asset('storage/app/public/business')}}/{{$logo->live_values??""}}"
                     onerror="this.src='{{asset('public/assets/placeholder.png')}}'"
                     alt="" class="mobile-logo">
            </a>
        <!-- </a> -->
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
                     src="{{asset('storage/app/public/user/profile_image')}}/{{ auth()->user()->profile_image }}"
                     onerror="this.src='{{asset('public/assets/admin-module')}}/img/media/upload-file.png'"
                     alt="">
            </div>
            <div class="media-body ">
                <h5 class="card-title">{{\Illuminate\Support\Str::limit(auth()->user()->email,15)}}</h5>
                <span class="card-text">{{auth()->user()->user_type}}</span>
            </div>
        </div>

        <!-- Nav -->
        <ul class="nav">
            <li class="nav-category">{{translate('main')}}</li>
            <li>
                <a href="{{route('admin.dashboard')}}" class="{{request()->is('admin/dashboard')?'active-menu':''}}">
                    <span class="material-icons" title="{{translate('dashboard')}}">dashboard</span>
                    <span class="link-title">{{translate('dashboard')}}</span>
                </a>
            </li>

            @if(access_checker('addon_module'))
            <li class="nav-category" title="{{translate('system_addon')}}">
                {{translate('system_addon')}}
            </li>
                <li>
                    <a  class="{{Request::is('admin/addon')?'active-menu':''}}"
                    href="{{route('admin.addon.index')}}" title="{{translate('system_addons')}}">
                        <span class="material-icons" title="add_circle_outline">add_circle_outline</span>
                        <span class="link-title">{{translate('system_addons')}}</span>
                    </span>
                    </a>
                </li>
                <!-- End Dashboards -->

                {{-- addons--}}
            @if(count(config('addon_admin_routes'))>0)
                <li class="has-sub-item {{request()->is('admin/payment/configuration/*') || request()->is('admin/sms/configuration/*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/payment/configuration/*') || request()->is('admin/sms/configuration/*')?'active-menu':''}}">
                        <span class="material-icons" title="add_circle_outline">add_circle_outline</span>
                        <span class="link-title">{{translate('addon_menus')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav flex-column sub-menu">
                        @foreach(config('addon_admin_routes') as $routes)
                        @foreach($routes as $route)
                            <li>
                                <a class="{{ Request::is($route['path']) ?'active-menu':'' }}"
                                    href="{{ $route['url'] }}" title="{{ translate($route['name']) }}">
                                        {{ translate($route['name']) }}
                                </a>
                            </li>
                        @endforeach
                    @endforeach
                    </ul>
                    <!-- End Sub Menu -->
                </li>
            @endif
            <!--addon end-->

            @endif



            @if(access_checker('service_management'))
                <li class="nav-category" title="{{translate('service_management')}}">
                    {{translate('service_management')}}
                </li>
                <li>
                    <a href="{{route('admin.zone.create')}}" class="{{request()->is('admin/zone/*')?'active-menu':''}}">
                        <span class="material-icons" title="{{translate('service_zones')}}">map</span>
                        <span class="link-title">{{translate('service_zones')}}</span>
                    </a>
                </li>
                <li class="has-sub-item {{(request()->is('admin/category/*') || request()->is('admin/sub-category/*'))?'sub-menu-opened':''}}">
                    <a href="#" class="{{(request()->is('admin/category/*') || request()->is('admin/sub-category/*'))?'active-menu':''}}">
                        <span class="material-icons" title="Service Categories">category</span>
                        <span class="link-title">{{translate('service_categories')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.category.create')}}"
                               class="{{request()->is('admin/category/*')?'active-menu':''}}">
                                {{translate('category_setup')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.sub-category.create')}}"
                               class="{{request()->is('admin/sub-category/*')?'active-menu':''}}">
                                {{translate('sub_category_setup')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>
                <li class="has-sub-item {{request()->is('admin/service/*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/service/*')?'active-menu':''}}">
                        <span class="material-icons" title="Services">design_services</span>
                        <span class="link-title">{{translate('services')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav flex-column sub-menu">
                        <li>
                            <a href="{{route('admin.service.index')}}"
                               class="{{request()->is('admin/service/list')?'active-menu':''}}">
                                {{translate('service_list')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.service.create')}}"
                               class="{{request()->is('admin/service/create')?'active-menu':''}}">
                                {{translate('add_new_service')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.service.request.list')}}"
                               class="{{request()->is('admin/service/request/list*')?'active-menu':''}}">
                                <span class="link-title">{{translate('New Service Requests')}}</span>
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>
            @endif

            @if(access_checker('promotion_management'))
                <li class="nav-category" title="{{translate('promotion_management')}}">
                    {{translate('promotion_management')}}
                </li>
                <li class="has-sub-item {{request()->is('admin/discount/*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/discount/*')?'active-menu':''}}">
                        <span class="material-icons" title="{{translate('discounts')}}">redeem</span>
                        <span class="link-title">{{translate('discounts')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.discount.list')}}"
                               class="{{request()->is('admin/discount/list')?'active-menu':''}}">
                                {{translate('discount_list')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.discount.create')}}"
                               class="{{request()->is('admin/discount/create')?'active-menu':''}}">
                                {{translate('add_new_discount')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>
                <li class="has-sub-item {{request()->is('admin/coupon/*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/coupon/*')?'active-menu':''}}">
                        <span class="material-icons" title="{{translate('coupons')}}">sell</span>
                        <span class="link-title">{{translate('coupons')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.coupon.list')}}"
                               class="{{request()->is('admin/coupon/list')?'active-menu':''}}">
                                {{translate('coupon_list')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.coupon.create')}}"
                               class="{{request()->is('admin/coupon/create')?'active-menu':''}}">
                                {{translate('add_new_coupon')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>
                <li class="has-sub-item {{request()->is('admin/campaign/*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/campaign/*')?'active-menu':''}}">
                        <span class="material-icons" title="{{translate('campaigns')}}">campaign</span>
                        <span class="link-title">{{translate('campaigns')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.campaign.list')}}"
                               class="{{request()->is('admin/campaign/list')?'active-menu':''}}">
                                {{translate('campaign_list')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.campaign.create')}}"
                               class="{{request()->is('admin/campaign/create')?'active-menu':''}}">
                                {{translate('add_new_campaign')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>
                <li class="has-sub-item {{request()->is('admin/bonus/*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/bonus/*')?'active-menu':''}}">
                        <span class="material-icons matarial-symbols-outlined" title="{{translate('bonus')}}">price_change</span>
                        <span class="link-title">{{translate('Add Fund Bonus')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.bonus.list')}}"
                               class="{{request()->is('admin/bonus/list')?'active-menu':''}}">
                                {{translate('bonus_list')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.bonus.create')}}"
                               class="{{request()->is('admin/bonus/create')?'active-menu':''}}">
                                {{translate('add_new_bonus')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>
                <li>
                    <a href="{{route('admin.banner.create')}}"
                       class="{{request()->is('admin/banner/*')?'active-menu':''}}">
                        <span class="material-icons" title="{{translate('promotional_banners')}}">flag</span>
                        <span class="link-title">{{translate('promotional_banners')}}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.push-notification.create')}}"
                       class="{{request()->is('admin/push-notification/*')?'active-menu':''}}">
                        <span class="material-icons" title="{{translate('push_notification')}}">notifications</span>
                        <span class="link-title">{{translate('push_notification')}}</span>
                    </a>
                </li>
            @endif

            @if(access_checker('booking_management'))
                <li class="nav-category" title="{{translate('booking_management')}}">
                    {{translate('booking_management')}}
                </li>
                <li class="has-sub-item {{request()->is('admin/booking/*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/booking/*')?'active-menu':''}}">
                        <span class="material-icons" title="Bookings">calendar_month</span>
                        <span class="link-title">{{translate('bookings')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.booking.post.list', ['type'=>'all'])}}"
                               class="{{request()->is('admin/booking/post') || request()->is('admin/booking/post/details*') ? 'active-menu' : ''}}">
                                <span class="link-title">{{translate('Customized_Requests')}}
                                    <span class="count">{{\Modules\BidModule\Entities\Post::count()??0}}</span>
                                </span>
                            </a>
                        </li>
                        <li><a href="{{route('admin.booking.list.verification', ['booking_status'=>'pending'])}}"
                               class="{{request()->is('admin/booking/list/verification') && request()->query('booking_status')=='pending' ?'active-menu':''}}"><span
                                    class="link-title">{{translate('verify_requests')}} <span
                                        class="count">{{\Modules\BookingModule\Entities\Booking::where('is_verified', '0')->where('payment_method', 'cash_after_service')->Where('total_booking_amount', '>', $max_booking_amount)->whereIn('booking_status', ['pending', 'accepted'])->count()}}</span></span></a>
                        </li>
                        <li><a href="{{route('admin.booking.list', ['booking_status'=>'pending'])}}"
                               class="{{request()->is('admin/booking/list') && request()->query('booking_status')=='pending'?'active-menu':''}}"><span
                                    class="link-title">{{translate('Booking_Requests')}} <span
                                        class="count">{{$pending_booking_count}}</span></span></a>
                        </li>
                        <li><a href="{{route('admin.booking.list', ['booking_status'=>'accepted'])}}"
                               class="{{request()->is('admin/booking/list') && request()->query('booking_status')=='accepted'?'active-menu':''}}"><span
                                    class="link-title">{{translate('Accepted')}} <span
                                        class="count">{{$accepted_booking_count}}</span></span></a>
                        </li>
                        <li><a href="{{route('admin.booking.list', ['booking_status'=>'ongoing'])}}"
                               class="{{request()->is('admin/booking/list') && request()->query('booking_status')=='ongoing'?'active-menu':''}}"><span
                                    class="link-title">{{translate('Ongoing')}} <span
                                        class="count">{{$booking->where('booking_status', 'ongoing')->count()}}</span></span></a>
                        </li>
                        <li><a href="{{route('admin.booking.list', ['booking_status'=>'completed'])}}"
                               class="{{request()->is('admin/booking/list') && request()->query('booking_status')=='completed'?'active-menu':''}}"><span
                                    class="link-title">{{translate('Completed')}} <span
                                        class="count">{{$booking->where('booking_status', 'completed')->count()}}</span></span></a>
                        </li>
                        <li><a href="{{route('admin.booking.list', ['booking_status'=>'canceled'])}}"
                               class="{{request()->is('admin/booking/list') && request()->query('booking_status')=='canceled'?'active-menu':''}}"><span
                                    class="link-title">{{translate('Canceled')}} <span
                                        class="count">{{$booking->where('booking_status', 'canceled')->count()}}</span></span></a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>
            @endif

            @if(access_checker('provider_management'))
                <li class="nav-category"
                    title="{{translate('provider_management')}}">
                    {{translate('provider_management')}}
                </li>
                <li class="has-sub-item  {{(request()->is('admin/provider/list') || request()->is('admin/provider/create') || request()->is('admin/provider/details*') || request()->is('admin/provider/edit*') || request()->is('admin/provider/collect-cash*'))?'sub-menu-opened':''}}">
                    <a href="#" class="{{(request()->is('admin/provider/list') || request()->is('admin/provider/create') || request()->is('admin/provider/details*') || request()->is('admin/provider/edit*') || request()->is('admin/provider/collect-cash*'))?'active-menu':''}}">
                        <span class="material-icons" title="Providers">engineering</span>
                        <span class="link-title">{{translate('providers')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.provider.list', ['status'=>'all'])}}"
                                class="{{(request()->is('admin/provider/list'))?'active-menu':''}}">{{translate('Provider_List')}}</a>
                        </li>
                        <li><a href="{{route('admin.provider.create')}}"
                                class="{{(request()->is('admin/provider/create'))?'active-menu':''}}">{{translate('Add_New_Provider')}}</a></li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>
                <li>
                    <a href="{{route('admin.withdraw.request.list', ['status'=>'all'])}}"
                        class="{{request()->is('admin/withdraw/request*')?'active-menu':''}}">
                        <span class="material-icons" title="{{translate('withdraw_requests')}}">money</span>
                        <span class="link-title">{{translate('withdraw_requests')}}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.withdraw.method.list')}}"
                        class="{{request()->is('admin/withdraw/method*')||request()->is('admin/withdraw/method/create')||request()->is('admin/withdraw/method/edit*')?'active-menu':''}}">
                        <span class="material-icons" title="{{translate('withdraw_methods')}}">payments</span>
                        <span class="link-title">{{translate('withdrawal_methods')}}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.provider.onboarding_request', ['status'=>'onboarding'])}}"
                       class="{{request()->is('admin/provider/onboarding-request')?'active-menu':''}}">
                        <span class="material-icons" title="{{translate('Onboarding_Request')}}">description</span>
                        <span class="link-title">{{translate('Onboarding_Request')}} <span class="count">{{$pending_providers}}</span></span>
                    </a>
                </li>
            @endif

            @if(access_checker('system_management'))
                <li class="nav-category"
                    title="{{translate('system_management')}}">{{translate('system_management')}}</li>
                <li>
                    <a href="{{route('admin.business-settings.get-business-information')}}"
                       class="{{request()->is('admin/business-settings/get-business-information')?'active-menu':''}}">
                        <span class="material-icons" title="Business Settings">business_center</span>
                        <span class="link-title">{{translate('business_settings')}}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.business-settings.get-landing-information')}}"
                       class="{{request()->is('admin/business-settings/get-landing-information')?'active-menu':''}}">
                        <span class="material-icons" title="Business Settings">rocket_launch</span>
                        <span class="link-title">{{translate('landing_page_settings')}}</span>
                    </a>
                </li>
                <li class="has-sub-item {{request()->is('admin/configuration/*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/configuration/*')?'active-menu':''}}">
                        <span class="material-icons" title="Configurations">settings</span>
                        <span class="link-title">{{translate('configurations')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.configuration.get-notification-setting')}}"
                               class="{{request()->is('admin/configuration/get-notification-setting')?'active-menu':''}}">
                                {{translate('notification')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.configuration.get-third-party-config')}}"
                               class="{{request()->is('admin/configuration/get-third-party-config')?'active-menu':''}}">
                                {{translate('3rd_party')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.configuration.get-email-config')}}"
                               class="{{request()->is('admin/configuration/get-email-config')?'active-menu':''}}">
                                {{translate('email_config')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.configuration.sms-get')}}"
                               class="{{request()->is('admin/configuration/sms-get')?'active-menu':''}}">
                                {{translate('SMS_config')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.configuration.payment-get') }}"
                            class="{{ request()->is('admin/configuration/payment-get') || request()->is('admin/configuration/offline-payment/*') ? 'active-menu' : '' }}">
                             {{ translate('payment_config') }}
                         </a>

                        </li>
                        <li>
                            <a href="{{route('admin.configuration.get-app-settings')}}"
                               class="{{request()->is('admin/configuration/get-app-settings')?'active-menu':''}}">
                                {{translate('App_Settings')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>
                <li>
                    <a href="{{route('admin.business-settings.get-pages-setup')}}"
                       class="{{request()->is('admin/business-settings/get-pages-setup')?'active-menu':''}}">
                        <span class="material-icons" title="Page Settings">article</span>
                        <span class="link-title">{{translate('page_settings')}}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.business-settings.get-gallery-setup')}}"
                       class="{{request()->is('admin/business-settings/get-gallery-setup*')?'active-menu':''}}">
                        <span class="material-icons" title="Page Settings">collections_bookmark</span>
                        <span class="link-title">{{translate('Gallery')}}</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.business-settings.get-database-backup')}}"
                       class="{{request()->is('admin/business-settings/get-database-backup')?'active-menu':''}}">
                        <span class="material-icons" title="Page Settings">backup</span>
                        <span class="link-title">{{translate('Backup_Database')}}</span>
                    </a>
                </li>
            @endif


            @if(access_checker('employee_management'))
                <li class="nav-category"
                    title="{{translate('employee_management')}}">{{translate('employee_management')}}</li>
                <li>
                    <a href="{{route('admin.role.create')}}" class="{{request()->is('admin/role/*')?'active-menu':''}}">
                        <span class="material-icons" title="Employee">settings</span>
                        <span class="link-title">{{translate('employee_roles')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.employee.create')}}"
                       class="{{request()->is('admin/employee/create')?'active-menu':''}}">
                        <span class="material-icons" title="{{translate('add_new_employee')}}">add</span>
                        <span class="link-title">{{translate('add_new_employee')}}</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.employee.index')}}"
                       class="{{request()->is('admin/employee/list')?'active-menu':''}}">
                        <span class="material-icons" title="{{translate('employee_list')}}">list</span>
                        <span class="link-title">{{translate('employee_list')}}</span>
                    </a>
                </li>
            @endif


            @if(access_checker('customer_management'))
                <li class="nav-category" title="{{translate('customer_management')}}">
                    {{translate('customer_management')}}
                </li>

                <!-- Customers -->
                <li class="has-sub-item {{request()->is('admin/customer/list')||request()->is('admin/customer/create')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/customer/list')||request()->is('admin/customer/create')?'active-menu':''}}">
                        <span class="material-icons" title="Customers">person_outline</span>
                        <span class="link-title">{{translate('customers')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.customer.index')}}"
                               class="{{request()->is('admin/customer/list')?'active-menu':''}}">
                                {{translate('customer_list')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.customer.create')}}"
                               class="{{request()->is('admin/customer/create')?'active-menu':''}}">
                                {{translate('add_new_customer')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>

                <!-- Customer Wallet -->
                <li class="has-sub-item {{request()->is('admin/customer/wallet*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/customer/wallet*')?'active-menu':''}}">
                        <span class="material-icons" title="Customers">wallet</span>
                        <span class="link-title">{{translate('customer_wallet')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.customer.wallet.add-fund')}}"
                               class="{{request()->is('admin/customer/wallet/add-fund')?'active-menu':''}}">
                                {{translate('Add_fund')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.customer.wallet.report')}}"
                               class="{{request()->is('admin/customer/wallet/report')?'active-menu':''}}">
                                {{translate('Report')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>

                <!-- Customer Settings -->
                <li>
                    <a href="{{route('admin.customer.settings', ['web_page'=>'loyalty_point'])}}"
                       class="{{request()->is('admin/customer/settings')?'active-menu':''}}">
                        <span class="material-icons" title="Business Settings">settings</span>
                        <span class="link-title">{{translate('Customer_Settings')}}</span>
                    </a>
                </li>

                <!-- Customer Wallet -->
                <li class="has-sub-item {{request()->is('admin/customer/loyalty-point*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/customer/loyalty-point*')?'active-menu':''}}">
                        <span class="material-icons" title="Customers">paid</span>
                        <span class="link-title">{{translate('loyalty_point')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.customer.loyalty-point.report')}}"
                               class="{{request()->is('admin/customer/loyalty-point/report')?'active-menu':''}}">
                                {{translate('Report')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>
            @endif

            @if(access_checker('transaction_management'))
                <li class="nav-category" title="{{translate('transaction_management')}}">
                    {{translate('transaction_management')}}
                </li>
                <li>
                    <a href="{{route('admin.transaction.list', ['trx_type'=>'all'])}}"
                       class="{{request()->is('admin/transaction/list')?'active-menu':''}}">
                        <span class="material-icons" title="Customers">article</span>
                        <span class="link-title">{{translate('transactions')}}</span>
                    </a>
                </li>
            @endif

            @if(access_checker('report_management'))
                <li class="nav-category" title="{{translate('report_management')}}">
                    {{translate('report_management')}}
                </li>
                <li class="has-sub-item {{request()->is('admin/report/*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/report/*')?'active-menu':''}}">
                        <span class="material-icons" title="Customers">event_note</span>
                        <span class="link-title">{{translate('Reports')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.report.transaction', ['transaction_type'=>'all'])}}"
                               class="{{request()->is('admin/report/transaction')?'active-menu':''}}">
                                {{translate('transaction')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.report.business.overview')}}"
                               class="{{request()->is('admin/report/business*')?'active-menu':''}}">
                                {{translate('business')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.report.booking')}}"
                               class="{{request()->is('admin/report/booking')?'active-menu':''}}">
                                {{translate('booking')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.report.provider')}}"
                               class="{{request()->is('admin/report/provider')?'active-menu':''}}">
                                {{translate('provider')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>

                <li class="has-sub-item {{request()->is('admin/analytics/*')?'sub-menu-opened':''}}">
                    <a href="#" class="{{request()->is('admin/analytics/*')?'active-menu':''}}">
                        <span class="material-icons" title="Customers">analytics</span>
                        <span class="link-title">{{translate('Analytics')}}</span>
                    </a>
                    <!-- Sub Menu -->
                    <ul class="nav sub-menu">
                        <li>
                            <a href="{{route('admin.analytics.search.keyword')}}"
                               class="{{request()->is('admin/analytics/search/keyword')?'active-menu':''}}">
                                {{translate('Keyword_Search')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.analytics.search.customer')}}"
                               class="{{request()->is('admin/analytics/search/customer')?'active-menu':''}}">
                                {{translate('Customer_Search')}}
                            </a>
                        </li>
                    </ul>
                    <!-- End Sub Menu -->
                </li>
            @endif
        </ul>
        <!-- End Nav -->
    </div>
    <!-- End Aside Body -->
</aside>
