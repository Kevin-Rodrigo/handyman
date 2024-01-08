<?php

namespace Modules\AdminModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\BookingModule\Entities\Booking;
use Modules\BookingModule\Entities\BookingDetailsAmount;
use Modules\ChattingModule\Entities\ChannelList;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ServiceManagement\Entities\Service;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\User;
use function auth;
use function bcrypt;
use function file_uploader;
use function response;
use function response_formatter;
use function view;

class AdminController extends Controller
{
    protected Provider $provider;
    protected Account $account;
    protected $booking;
    protected $service;
    protected $user;
    protected $transaction;
    protected $channelList;
    protected BookingDetailsAmount $booking_details_amount;

    public function __construct(ChannelList $channelList, Provider $provider, Service $service, Account $account, Booking $booking, User $user, Transaction $transaction, BookingDetailsAmount $booking_details_amount)
    {
        $this->provider = $provider;
        $this->service = $service;
        $this->account = $account;
        $this->booking = $booking;
        $this->user = $user;
        $this->transaction = $transaction;
        $this->channelList = $channelList;
        $this->booking_details_amount = $booking_details_amount;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param Transaction $transaction
     * @return Application|Factory|View
     */
    public function dashboard(Request $request, Transaction $transaction): Application|Factory|View
    {
        $data = [];
        $account_data = $this->account->where(['user_id' => auth()->id()])->first();
        $data[] = ['top_cards' => [
            'total_commission_earning' => $account_data['received_balance'] ?? 0,
            'total_system_earning' => $this->account->sum('received_balance') + $this->account->sum('total_withdrawn'),
            'total_customer' => $this->user->where(['user_type' => 'customer'])->count(),
            'total_provider' => $this->provider->where(['is_approved' => 1])->count(),
            'total_services' => $this->service->count()
        ]];

        //admin total earning
        $total_earning = $this->booking_details_amount
            ->whereHas('booking', function ($query) use ($request) {
                $query->ofBookingStatus('completed');
            })->get()->sum('admin_commission');

        $data[] = ['admin_total_earning' => $total_earning];

        //recent transactions
        $recent_transactions = $this->transaction
            ->with(['booking'])
            ->whereMonth('created_at', now()->month)
            ->latest()
            ->take(5)
            ->get();
        $data[] = [
            'recent_transactions' => $recent_transactions,
            'this_month_trx_count' => $transaction->count()
        ];

        //recent bookings
        $bookings = $this->booking->with(['detail.service' => function ($query) {
                $query->select('id', 'name', 'thumbnail');
            }])
            ->where('booking_status', 'pending')
            ->take(5)->latest()->get();
        $data[] = ['bookings' => $bookings];

        //top providers
        $top_providers = $this->provider
            ->withCount(['reviews'])
            ->with(['owner', 'reviews'])
            ->ofApproval(1)
            ->take(5)->orderBy('avg_rating', 'DESC')->get();
        $data[] = ['top_providers' => $top_providers];

        //zone wise booking data
        $zone_wise_bookings = $this->booking
            ->with('zone')
            ->whereHas('zone', function ($query) {
                $query->ofStatus(1);
            })
            ->whereMonth('created_at', now()->month)
            ->select('zone_id', DB::raw('count(*) as total'))
            ->groupBy('zone_id')
            ->get();
        $data[] = ['zone_wise_bookings' => $zone_wise_bookings, 'total_count' => $this->booking->count()];

        // Data for chart
        $year = session()->has('dashboard_earning_graph_year') ? session('dashboard_earning_graph_year') : date('Y');
        $amounts = $this->booking_details_amount
            ->whereHas('booking', function ($query) use ($request, $year) {
                $query->whereYear('created_at', '=', $year)->ofBookingStatus('completed');
            })
            ->select(
                DB::raw('sum(admin_commission) as admin_commission'),

                DB::raw('MONTH(created_at) month')
            )
            ->groupby('month')->get()->toArray();

        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        foreach ($months as $month) {
            $found=0;
            foreach ($amounts as $key=>$item) {
                if ($item['month'] == $month) {
                    $chart_data['total_earning'][] = with_decimal_point($item['admin_commission']);
                    $chart_data['commission_earning'][] = with_decimal_point($item['admin_commission']);
                    $found=1;
                }
            }
            if(!$found){
                $chart_data['total_earning'][] = with_decimal_point(0);
                $chart_data['commission_earning'][] = with_decimal_point(0);
            }
        }
        //chart data end

        return view('adminmodule::dashboard', compact('data', 'chart_data'));
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function update_dashboard_earning_graph(Request $request)
    {
        $year = $request['year'];
        $amounts = $this->booking_details_amount
            ->whereHas('booking', function ($query) use ($request, $year) {
                $query->whereYear('created_at', '=', $year)->ofBookingStatus('completed');
            })
            ->select(
                DB::raw('sum(admin_commission) as admin_commission'),

                DB::raw('MONTH(created_at) month')
            )
            ->groupby('month')->get()->toArray();

        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        foreach ($months as $month) {
            $found=0;
            foreach ($amounts as $key=>$item) {
                if ($item['month'] == $month) {
                    $chart_data['total_earning'][] = with_decimal_point($item['admin_commission']);
                    $chart_data['commission_earning'][] = with_decimal_point($item['admin_commission']);
                    $found=1;
                }
            }
            if(!$found){
                $chart_data['total_earning'][] = with_decimal_point(0);
                $chart_data['commission_earning'][] = with_decimal_point(0);
            }
        }
        //chart data end

        session()->put('dashboard_earning_graph_year', $request['year']);

        return response()->json($chart_data);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        if (in_array($request->user()->user_type, ADMIN_USER_TYPES)) {
            $user = $this->user->where(['id' => auth('api')->id()])->with(['roles'])->first();
            return response()->json(response_formatter(DEFAULT_200, $user), 200);
        }
        return response()->json(response_formatter(DEFAULT_403), 401);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Request $request): JsonResponse
    {
        if (in_array($request->user()->user_type, ADMIN_USER_TYPES)) {
            return response()->json(response_formatter(DEFAULT_200, auth('api')->user()), 200);
        }
        return response()->json(response_formatter(DEFAULT_403), 401);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function profile_info(Request $request): Renderable
    {
        return view('adminmodule::admin.profile-update');
    }

    /**
     * Modify provider information
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_profile(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'profile_image' => 'image|mimes:jpeg,jpg,png,gif|max:10240',
            'password' => '',
            'confirm_password' => !is_null($request->password) ? 'required|same:password' : '',
        ]);

        $user = $this->user->find($request->user()->id);
        $user->first_name = $request->first_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->last_name = $request->last_name;
        if ($request->has('profile_image')) {
            $user->profile_image = file_uploader('user/profile_image/', 'png', $request->profile_image, $user->profile_image);
        }
        if (!is_null($request->password)) {
            $user->password = bcrypt($request->confirm_password);
        }
        $user->save();

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function get_updated_data(Request $request): JsonResponse
    {
        $message = $this->channelList->wherehas('channelUsers', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id)->where('is_read', 0);
        })->count();

        return response()->json([
            'status' => 1,
            'data' => [
                'message' => $message
            ]
        ]);
    }
}
