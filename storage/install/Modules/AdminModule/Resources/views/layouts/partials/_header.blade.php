<header class="header fixed-top">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
            <div class="col-2">
                <!-- Header Menu -->
                <div class="header-toogle-menu">
                    <button class="toggle-menu-button aside-toggle border-0 bg-transparent p-0 dark-color">
                        <span class="material-icons">menu</span>
                    </button>
                </div>
                <!-- End Header Menu -->
            </div>
            <div class="col-10">
                <!-- Header Right -->
                <div class="header-right">
                    <ul class="nav justify-content-end align-items-center gap-30">
                        <li>
                            <button class="toggle-search-btn px-0 d-sm-none">
                                <span class="material-icons">search</span>
                            </button>
                            <!-- Header Search -->
                            <form action="#" class="search-form" autocomplete="off">
                                <div class="input-group position-relative search-form__input_group">
                                    <span class="search-form__icon">
                                        <span class="material-icons">search</span>
                                    </span>
                                    <input type="search" class="theme-input-style search-form__input"
                                           id="search-form__input" placeholder="{{translate('Search_Here')}}"/>
                                    <div class="dropdown-menu rounded">
                                        <div class="show-search-result">
                                            @foreach(get_routes('admin') as $route)
                                                <a href="{{url('/')}}/{{$route}}" class="dropdown-item-text title-color hover-color-c2 text-capitalize">
                                                    {{str_replace('admin','',implode(' ',explode('/',$route)))}}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- End Header Search -->
                        </li>
                        <li>
                            <!-- Header Messages -->
                            <div class="messages">
                                <a href="{{route('admin.chat.index')}}" class="header-icon count-btn">
                                    <span class="material-icons">sms</span>
                                    <span class="count" id="message_count">0</span>
                                </a>
                            </div>
                            <!-- End Main Header Messages -->
                        </li>
                        <li>
                            <!-- User -->
                            <div class="user mt-n1">
                                <a href="#" class="header-icon user-icon" data-bs-toggle="dropdown">
                                    <img width="30" height="30"
                                         src="{{asset('storage/app/public/user/profile_image')}}/{{ auth()->user()->profile_image }}"
                                         onerror="this.src='{{asset('public/assets/admin-module')}}/img/user2x.png'"
                                         class="rounded-circle" alt="">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{route('admin.profile_update')}}"
                                       class="dropdown-item-text media gap-3 align-items-center">
                                        <div class="avatar">
                                            <img class="avatar-img rounded-circle" width="50" height="50"
                                                 src="{{asset('storage/app/public/user/profile_image')}}/{{ auth()->user()->profile_image }}"
                                                 onerror="this.src='{{asset('public/assets/provider-module')}}/img/user2x.png'"
                                                 alt="">
                                        </div>
                                        <div class="media-body ">
                                            <h5 class="card-title">{{ Str::limit(auth()->user()->first_name, 20) }}</h5>
                                            <span class="card-text">{{ Str::limit(auth()->user()->email, 20) }}</span>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="{{route('admin.profile_update')}}">
                                        <span class="text-truncate" title="Settings">{{translate('Settings')}}</span>
                                    </a>
                                    <a class="dropdown-item" href="{{route('admin.auth.logout')}}">
                                        <span class="text-truncate" title="Sign Out">{{translate('Sign_Out')}}</span>
                                    </a>
                                </div>
                            </div>
                            <!-- End User -->
                        </li>
                    </ul>
                </div>
                <!-- End Header Right -->
            </div>
        </div>
    </div>
</header>
