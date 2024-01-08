<?php

namespace Modules\AdminModule\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ServiceManagement\Entities\Service;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\User;

class AdminController extends Controller
{
    protected Provider $provider;
    protected Account $account;
    protected $booking;
    protected $service;
    protected $user;
    protected $transaction;

    public function __construct(Provider $provider, Service $service, Account $account, Booking $booking, User $user, Transaction $transaction)
    {
        $this->provider = $provider;
        $this->service = $service;
        $this->account = $account;
        $this->booking = $booking;
        $this->user = $user;
        $this->transaction = $transaction;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param Transaction $transaction
     * @return JsonResponse
     */
    public function dashboard(Request $request, Transaction $transaction): JsonResponse
    {
        $request['sections'] = explode(',', $request['sections']);

        $validator = Validator::make($request->all(), [
            'sections' => 'required|array',
            'sections.*' => 'in:top_cards,earning_stats,top_providers,recent_transactions,recent_bookings,top_providers,zone_wise_bookings',
            'year' => 'integer|min:2000|max:' . (date('Y') + 1),
            'month' => 'integer|min:1|max:12',
            'stats_type' => 'in:full_year,full_month'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $data = [];
        if (in_array('top_cards', $request['sections'])) {
            $account_data = $this->account->where(['user_id' => $request->user()->id])->first();
            $data[] = ['top_cards' => [
                'total_commission_earning' => $account_data['received_balance'],
                'total_system_earning' => $this->account->sum('received_balance') + $this->account->sum('total_withdrawn'),
                'total_customer' => $this->user->where(['user_type' => 'customer'])->count(),
                'total_provider' => $this->provider->where(['is_approved' => 1])->count(),
                'total_services' => $this->service->count()
            ]];
        }

        if (in_array('earning_stats', $request['sections'])) {
            $all_earnings = $transaction->where('credit', '>', 0)->whereIn('to_user_account', ['received_balance', 'total_withdrawn'])
                ->when($request->has('stats_type') && $request['stats_type'] == 'full_year', function ($query) use ($request) {
                    return $query->whereYear('created_at', '=', $request['year'])->select(
                        DB::raw('IFNULL(sum(credit),0) as sums'),
                        DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month')
                    )->groupby('year', 'month');
                })->when($request->has('stats_type') && $request['stats_type'] == 'full_month', function ($query) use ($request) {
                    return $query->whereYear('created_at', '=', $request['year'])->whereMonth('created_at', '=', $request['month'])->select(
                        DB::raw('IFNULL(sum(credit),0) as sums'),
                        DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month, DAY(created_at) day')
                    )->groupby('year', 'month', 'day');
                })->get()->toArray();

            $all_commissions = $transaction->where(['trx_type' => 'commission'])->whereIn('to_user_account', ['received_balance'])
                ->when($request->has('stats_type') && $request['stats_type'] == 'full_year', function ($query) use ($request) {
                    return $query->whereYear('created_at', '=', $request['year'])->select(
                        DB::raw('IFNULL(sum(credit),0) as sums'),
                        DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month')
                    )->groupby('year', 'month');
                })->when($request->has('stats_type') && $request['stats_type'] == 'full_month', function ($query) use ($request) {
                    return $query->whereYear('created_at', '=', $request['year'])->whereMonth('created_at', '=', $request['month'])->select(
                        DB::raw('IFNULL(sum(credit),0) as sums'),
                        DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month, DAY(created_at) day')
                    )->groupby('year', 'month', 'day');
                })->get()->toArray();

            $data[] = ['earning_stats' => $all_earnings, 'commission_stats' => $all_commissions];
        }

        if (in_array('recent_transactions', $request['sections'])) {
            $recent_transactions = $this->transaction
                ->with(['booking'])
                ->latest()
                ->take(5)
                ->get();
            $data[] = [
                'recent_transactions' => $recent_transactions,
                'this_month_trx_count' => $transaction->whereMonth('created_at', date('m'))->count()
            ];
        }

        if (in_array('recent_bookings', $request['sections'])) {
            $bookings = $this->booking->with(['detail.service' => function ($query) {
                $query->select('id', 'name', 'thumbnail');
            }])->take(5)->latest()->get();
            $data[] = ['bookings' => $bookings];
        }

        if (in_array('top_providers', $request['sections'])) {
            $top_providers = $this->provider->with(['owner'])->take(5)->get();
            $data[] = ['top_providers' => $top_providers];
        }

        if (in_array('zone_wise_bookings', $request['sections'])) {
            $zone_wise_bookings = $this->booking
                ->with('zone')
                ->select('zone_id', DB::raw('count(*) as total'))
                ->groupBy('zone_id')
                ->get();
            $data[] = ['zone_wise_bookings' => $zone_wise_bookings, 'total_count' => $this->booking->count()];
        }

        return response()->json(response_formatter(DEFAULT_200, $data), 200);
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
     * Modify provider information
     * @param Request $request
     * @return JsonResponse
     */
    public function update_profile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'phone' => 'required|unique:users,phone,' . $request->user()->id,
            'profile_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'password' => '',
            'confirm_password' => $request->has('password') ? 'required|same:password' : '',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $user = $this->user->find($request->user()->id);
        $user->first_name = $request->first_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->last_name = $request->last_name;
        if ($request->has('profile_image')) {
            $user->profile_image = file_uploader('user/profile_image/', 'png', $request->profile_image, $user->profile_image);
        }
        if ($request->has('password')) {
            $user->password = bcrypt($request->confirm_password);
        }
        $user->save();

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }
}
