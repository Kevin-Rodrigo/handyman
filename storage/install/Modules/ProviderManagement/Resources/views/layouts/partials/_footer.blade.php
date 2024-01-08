<footer class="footer mt-auto">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 d-flex justify-content-center justify-content-md-start mb-2 mb-md-0">
                {{(business_config('footer_text','business_information'))->live_values??""}} <span class="currentYear ml-3"></span>
            </div>
            <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                <ul class="list-inline list-separator">
                    <li>
                        <a href="{{route('provider.profile_update')}}">{{translate('Profile')}}</a>
                    </li>
                    <li>
                        <a href="{{route('provider.dashboard')}}">
                            <span class="material-icons">home</span>
                        </a>
                    </li>
                    <li>
                        <span class="badge badge-success opacity-75">{{translate('Software_version')}} : {{ env('SOFTWARE_VERSION') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
