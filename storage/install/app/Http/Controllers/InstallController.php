<?php

namespace App\Http\Controllers;

use App\Traits\ActivationClass;
use App\Traits\UnloadedHelpers;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Madnest\Madzipper\Facades\Madzipper;
use Modules\UserManagement\Entities\User;
use Ramsey\Uuid\Uuid;

class InstallController extends Controller
{
    use ActivationClass, UnloadedHelpers;

    public function step0(): Factory|View|Application
    {
        return view('installation.step0');
    }

    public function step1(Request $request): View|Factory|RedirectResponse|Application
    {
        if (Hash::check('step_1', $request['token'])) {
            $permission['curl_enabled'] = function_exists('curl_version');
            $permission['db_file_write_perm'] = is_writable(base_path('.env'));
            $permission['routes_file_write_perm'] = is_writable(base_path('app/Providers/RouteServiceProvider.php'));
            return view('installation.step1', compact('permission'));
        }
        session()->flash('error', 'Access denied!');
        return redirect()->route('step0');
    }

    public function step2(Request $request): View|Factory|RedirectResponse|Application
    {
        if (Hash::check('step_2', $request['token'])) {
            return view('installation.step2');
        }
        session()->flash('error', 'Access denied!');
        return redirect()->route('step0');
    }

    public function step3(Request $request): View|Factory|RedirectResponse|Application
    {
        if (Hash::check('step_3', $request['token'])) {
            return view('installation.step3');
        }
        session()->flash('error', 'Access denied!');
        return redirect()->route('step0');
    }

    public function step4(Request $request): View|Factory|RedirectResponse|Application
    {
        if (Hash::check('step_4', $request['token'])) {
            return view('installation.step4');
        }
        session()->flash('error', 'Access denied!');
        return redirect()->route('step0');
    }

    public function step5(Request $request): View|Factory|RedirectResponse|Application
    {
        if (Hash::check('step_5', $request['token'])) {
            return view('installation.step5');
        }
        session()->flash('error', 'Access denied!');
        return redirect()->route('step0');
    }

    public function purchase_code(Request $request): RedirectResponse
    {
        $this->setEnvironmentValue('SOFTWARE_ID', 'NDAyMjQ3NzI=');
        $this->setEnvironmentValue('BUYER_USERNAME', $request['username']);
        $this->setEnvironmentValue('PURCHASE_CODE', $request['purchase_key']);

        $post = [
            'name' => $request['name'],
            'email' => $request['email'],
            'username' => $request['username'],
            'purchase_key' => $request['purchase_key'],
            'domain' => preg_replace("#^[^:/.]*[:/]+#i", "", url('/')),
        ];
        $response = $this->dmvf($post);

        return redirect($response.'?token='.bcrypt('step_3'));
    }

    public function system_settings(Request $request): View|Factory|RedirectResponse|Application
    {
        if (!Hash::check('step_6', $request['token'])) {
            session()->flash('error', 'Access denied!');
            return redirect()->route('step0');
        }

        User::create([
            'id' => Uuid::uuid4(),
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'user_type' => 'super-admin',
            'password' => bcrypt($request['password']),
            'phone' => $request['phone'],
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);

        Artisan::call('module:enable');
        //sleep(5);
        return view('installation.step6');
    }

    public function database_installation(Request $request): Redirector|Application|RedirectResponse
    {
        if (self::check_database_connection($request->DB_HOST, $request->DB_DATABASE, $request->DB_USERNAME, $request->DB_PASSWORD)) {

            $key = base64_encode(random_bytes(32));
            $output = 'APP_NAME=Demandium' . time() . '
                    APP_ENV=live
                    APP_KEY=base64:' . $key . '
                    APP_DEBUG=false
                    APP_INSTALL=true
                    APP_LOG_LEVEL=debug
                    APP_URL=' . URL::to('/') . '

                    DB_CONNECTION=mysql
                    DB_HOST=' . $request->DB_HOST . '
                    DB_PORT=3306
                    DB_DATABASE=' . $request->DB_DATABASE . '
                    DB_USERNAME=' . $request->DB_USERNAME . '
                    DB_PASSWORD=' . $request->DB_PASSWORD . '

                    BROADCAST_DRIVER=log
                    CACHE_DRIVER=file
                    SESSION_DRIVER=file
                    SESSION_LIFETIME=60
                    QUEUE_DRIVER=sync

                    AWS_ENDPOINT=
                    AWS_ACCESS_KEY_ID=
                    AWS_SECRET_ACCESS_KEY=
                    AWS_DEFAULT_REGION=us-east-1
                    AWS_BUCKET=

                    REDIS_HOST=127.0.0.1
                    REDIS_PASSWORD=null
                    REDIS_PORT=6379

                    PUSHER_APP_ID=
                    PUSHER_APP_KEY=
                    PUSHER_APP_SECRET=
                    PUSHER_APP_CLUSTER=mt1

                    PURCHASE_CODE=' . session('purchase_key') . '
                    BUYER_USERNAME=' . session('username') . '
                    SOFTWARE_ID=NDAyMjQ3NzI=

                    SOFTWARE_VERSION=2.1
                    ';
            $file = fopen(base_path('.env'), 'w');
            fwrite($file, $output);
            fclose($file);

            $path = base_path('.env');
            if (file_exists($path)) {
                return redirect()->route('step4', ['token' => $request['token']]);
            } else {
                session()->flash('error', 'Database error!');
                return redirect()->route('step3', ['token' => bcrypt('step_3')]);
            }
        } else {
            session()->flash('error', 'Database host error!');
            return redirect()->route('step3', ['token' => bcrypt('step_3')]);
        }
    }

    public function import_sql(): Redirector|RedirectResponse|Application
    {
        try {
            $sql_path = base_path('installation/backup/database.sql');
            DB::unprepared(file_get_contents($sql_path));

            return redirect()->route('step5', ['token' => bcrypt('step_5')]);
        } catch (\Exception $exception) {
            session()->flash('error', 'Your database is not clean, do you want to clean database then import?');
            return back();
        }
    }

    public function force_import_sql(): Redirector|RedirectResponse|Application
    {
        try {
            Artisan::call('db:wipe');
            $sql_path = base_path('installation/backup/database.sql');
            DB::unprepared(file_get_contents($sql_path));

            return redirect()->route('step5', ['token' => bcrypt('step_5')]);
        } catch (\Exception $exception) {
            session()->flash('error', 'Check your database permission!');
            return back();
        }
    }

    function check_database_connection($db_host = "", $db_name = "", $db_user = "", $db_pass = ""): bool
    {
        try {
            if (@mysqli_connect($db_host, $db_user, $db_pass, $db_name)) {
                return true;
            } else {
                return false;
            }
        }catch(\Exception $exception){
            return false;
        }
    }
}
