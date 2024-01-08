<?php

namespace App\Providers;

use App\Traits\ActivationClass;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Modules\AddonModule\Traits\AddonHelper;

ini_set('memory_limit', '512M');

class AppServiceProvider extends ServiceProvider
{
    use ActivationClass, AddonHelper;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     */
    public function boot(Request $request)
    {
        if (($request->is('admin/auth/login') || $request->is('provider/auth/login')) && $request->isMethod('post')) {
            $response = $this->actch();
            $data = json_decode($response->getContent(), true);
            if (!$data['active']) {
                return Redirect::away(base64_decode('aHR0cHM6Ly9hY3RpdmF0aW9uLjZhbXRlY2guY29t'))->send();
            }
        }

        Config::set('addon_admin_routes',$this->get_addon_admin_routes());
        Config::set('get_payment_publish_status',$this->get_payment_publish_status());

        try {
            Config::set('default_pagination', 25);
            Paginator::useBootstrap();
        } catch (\Exception $ex) {
            info($ex);
        }
    }
}
