<?php

namespace Modules\ProviderManagement\Http\Controllers\Web\Provider;

use App\CentralLogics\ProductLogic;
use App\Models\Item;
use App\Scopes\StoreScope;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\BidModule\Entities\IgnoredPost;
use Modules\BidModule\Entities\Post;
use Modules\BookingModule\Entities\Booking;
use Modules\BookingModule\Entities\BookingDetailsAmount;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Modules\CategoryManagement\Entities\Category;
use Modules\ChattingModule\Entities\ChannelList;
use Modules\PromotionManagement\Entities\PushNotification;
use Modules\ProviderManagement\Entities\BankDetail;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ProviderManagement\Entities\SubscribedService;
use Modules\ReviewModule\Entities\Review;
use Modules\ServiceManagement\Entities\Service;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\User;
use Modules\ZoneManagement\Entities\Zone;
use Rap2hpoutre\FastExcel\FastExcel;
use Session;
use Carbon\Carbon;

class ProviderController extends Controller
{
    private $provider, $account, $user, $push_notification, $serviceman;
    private $subscribedService;
    private Booking $booking;
    private Zone $zone;
    private Review $review;
    private Transaction $transaction;
    private ChannelList $channelList;
    private SubscribedService $subscribed_service;
    private BankDetail $bank_detail;
    protected BusinessSettings $business_settings;
    protected BookingDetailsAmount $booking_details_amount;
    protected  SubscribedService $subscribed_sub_categories;

    protected $google_map;

    public function __construct(ChannelList $channelList, Transaction $transaction, SubscribedService $subscribedService, BankDetail $bankDetail, Provider $provider, Account $account, User $user, PushNotification $pushNotification, Serviceman $serviceman, Booking $booking, Zone $zone, Review $review, Service $service, SubscribedService $subscribed_service, BankDetail $bank_detail, BusinessSettings $business_settings, BookingDetailsAmount $booking_details_amount)
    {
        $this->bank_detail = $bankDetail;
        $this->provider = $provider;
        $this->user = $user;
        $this->account = $account;
        $this->push_notification = $pushNotification;
        $this->serviceman = $serviceman;
        $this->subscribedService = $subscribedService;
        $this->google_map = business_config('google_map', 'third_party');
        $this->booking = $booking;
        $this->zone = $zone;
        $this->review = $review;
        $this->transaction = $transaction;
        $this->channelList = $channelList;
        $this->subscribed_sub_categories = $subscribedService;

        $this->subscribed_service = $subscribed_service;
        $this->bank_detail = $bank_detail;
        $this->business_settings = $business_settings;
        $this->booking_details_amount = $booking_details_amount;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function get_updated_data(Request $request): JsonResponse
    {
        $subscribed = $this->subscribed_sub_categories->where(['provider_id' => $request->user()->provider->id])
            ->where(['is_subscribed' => 1])
            ->pluck('sub_category_id')->toArray();

        $booking = $this->booking
            ->whereIn('sub_category_id', $subscribed)
            ->where('zone_id', $request->user()->provider->zone_id)
            ->where('is_checked', 0)->count();
        $notification_count = $this->push_notification->whereJsonContains('zone_ids', $request->user()->provider->zone_id)->count();
        $notifications = $this->push_notification->whereJsonContains('zone_ids', $request->user()->provider->zone_id)->latest()->take(50)->get();
        $message = $this->channelList->wherehas('channelUsers', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id)->where('is_read', 0);
        })->count();


        //bidding_service_request
        $ignored_posts = IgnoredPost::where('provider_id', auth()->user()->provider->id)->pluck('post_id')->toArray();
        $bidding_post_validity = (int)(business_config('bidding_post_validity', 'bidding_system'))->live_values;

        $unchecked_posts = Post::whereNotIn('id', $ignored_posts)
            ->whereIn('sub_category_id', $subscribed)
            ->where('zone_id', $request->user()->provider->zone_id)
            ->where('is_checked', 0)
            ->whereBetween('created_at', [Carbon::now()->subDays($bidding_post_validity), Carbon::now()])
            ->latest()
            ->get();

        $post = $unchecked_posts->first();

        //find distance
        $coordinates = auth()->user()->provider->coordinates ?? null;
        $distance = null;
        if(!is_null($coordinates) && isset($post) && $post->service_address) {
            $distance = get_distance(
                [$coordinates['latitude']??null, $coordinates['longitude']??null],
                [$post->service_address?->lat, $post->service_address?->lon]
            );
            $distance = ($distance) ? number_format($distance, 2) .' km' : null;
        }

        return response()->json([
            'status' => 1,
            'data' => [
                'booking' => $booking,
                'notification_count' => $notification_count,
                'notification_template' => view('providermanagement::provider.partials._notifications', compact('notifications'))->render(),
                'message' => $message,
                'unchecked_posts' => $unchecked_posts->count(),
                'post_content' => $post ? view('providermanagement::provider.partials._bidding_service_request', compact('post', 'distance'))->render() : null
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param Transaction $transaction
     * @param SubscribedService $subscribedService
     * @param Serviceman $serviceman
     * @return Renderable
     */
    public function dashboard(Request $request, Transaction $transaction, SubscribedService $subscribedService, Serviceman $serviceman): Renderable
    {
        $notification = $this->push_notification->whereJsonContains('zone_ids', $request->user()->provider->zone_id)->get()->count();
        session()->put('notification_count', $notification);

        $data = [];

        $max_booking_amount = (business_config('max_booking_amount', 'booking_setup'))->live_values;

        //top_cards
        $account = $this->account->where('user_id', $request->user()->id)->first();
        $data[] = ['top_cards' => [
            'total_earning' => $account['received_balance'] + $account['total_withdrawn'],
            'total_subscribed_services' => $this->subscribedService->where('provider_id', $request->user()->provider->id)
                ->with(['sub_category'])
                ->whereHas('category', function ($query) {
                    $query->where('is_active', 1);
                })->whereHas('sub_category', function ($query) {
                    $query->where('is_active', 1);
                })
                ->ofStatus(1)
                ->count(),
            'total_service_man' => $this->serviceman->where(['provider_id' => $request->user()->provider->id])->count(),
            'total_booking_served' => $request->user()->provider->bookings('completed')->count()
        ]];

        //provider total earning
        $total_earning = $this->booking_details_amount
            ->whereHas('booking', function ($query) use ($request) {
                $query->where('provider_id', $request->user()->provider->id)
                    ->ofBookingStatus('completed');
            })
            ->get()->sum('provider_earning');

        $data[] = ['provider_total_earning' => $total_earning];


        //booking_stats
        $booking_overview = DB::table('bookings')->where('provider_id', $request->user()->provider->id)
            ->select('booking_status', DB::raw('count(*) as total'))
            ->groupBy('booking_status')
            ->get();
        $total_bookings = $this->booking->where('provider_id', $request->user()->provider->id)->count();
        $data[] = ['booking_stats' => $booking_overview, 'total_bookings' => $total_bookings];


        //recent_bookings
        $sub_category_ids = $this->subscribed_service->where('provider_id', $request->user()->provider->id)->ofSubscription(1)->pluck('sub_category_id')->toArray();
        $recent_bookings = $this->booking->with(['detail.service' => function ($query) {
            $query->select('id', 'name', 'thumbnail');
        }])
            ->whereIn('sub_category_id', $sub_category_ids)
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
            ->where('booking_status', 'pending')
            ->latest()
            ->take(5)
            ->get();
        $data[] = ['recent_bookings' => $recent_bookings];


        //my_subscriptions
        $subscriptions = $subscribedService
            ->with(['sub_category'])
            ->withCount(['services', 'completed_booking'])
            ->where(['provider_id' => $request->user()->provider->id])
            ->whereHas('category', function ($query) {
                $query->where('is_active', 1);
            })->whereHas('sub_category', function ($query) {
                $query->where('is_active', 1);
            })
            ->ofStatus(1)
            ->take(5)->get();

        $data[] = ['subscriptions' => $subscriptions];


        //serviceman_list
        $serviceman_list = $this->serviceman->whereHas('user', function ($q) {
                $q->ofStatus(1);
            })->with(['user'])
            ->where(['provider_id' => $request->user()->provider->id])
            ->latest()
            ->take(5)->get();

        $data[] = ['serviceman_list' => $serviceman_list];

        //recent transactions
        $recent_transactions = $this->transaction->where(['to_user_id' => $request->user()->id])->where('credit', '>', 0)
            ->with(['booking'])
            ->latest()
            ->take(5)
            ->get();
        $data[] = [
            'recent_transactions' => $recent_transactions,
            'this_month_trx_count' => $transaction->where(['to_user_id' => $request->user()->id])->where('credit', '>', 0)->whereMonth('created_at', date('m'))->count()
        ];

        // Data for chart
        $year = session()->has('dashboard_earning_graph_year') ? session('dashboard_earning_graph_year') : date('Y');
        $amounts = $this->booking_details_amount
            ->whereHas('booking', function ($query) use ($request, $year) {
                $query->where('provider_id', $request->user()->provider->id)
                    ->whereYear('created_at', '=', $year)
                    ->ofBookingStatus('completed');
            })
            ->select(
                DB::raw('sum(provider_earning) as provider_earning'),

                DB::raw('MONTH(created_at) month')
            )
            ->groupby('month')->get()->toArray();

        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        foreach ($months as $month) {
            $found=0;
            foreach ($amounts as $key=>$item) {
                if ($item['month'] == $month) {
                    $chart_data['total_earning'][] = with_decimal_point($item['provider_earning']);
                    $found=1;
                }
            }
            if(!$found){
                $chart_data['total_earning'][] = with_decimal_point(0);
            }
        }
        //chart data end

        return view('providermanagement::dashboard', compact('data', 'chart_data'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function update_dashboard_earning_graph(Request $request): JsonResponse
    {
        $year = $request['year'];
        $amounts = $this->booking_details_amount
            ->whereHas('booking', function ($query) use ($request, $year) {
                $query->where('provider_id', $request->user()->provider->id)
                    ->whereYear('created_at', '=', $year)
                    ->ofBookingStatus('completed');
            })
            ->select(
                DB::raw('sum(provider_earning) as provider_earning'),

                DB::raw('MONTH(created_at) month')
            )
            ->groupby('month')->get()->toArray();

        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        foreach ($months as $month) {
            $found=0;
            foreach ($amounts as $key=>$item) {
                if ($item['month'] == $month) {
                    $chart_data['total_earning'][] = with_decimal_point($item['provider_earning']);
                    $found=1;
                }
            }
            if(!$found){
                $chart_data['total_earning'][] = with_decimal_point(0);
            }
        }
        //chart data end

        session()->put('dashboard_earning_graph_year', $request['year']);

        return response()->json($chart_data);
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function subscribed_sub_categories(Request $request): Renderable
    {
        $keys = explode(' ', $request['search']);
        $status = $request['status'];
        $search = $request['search'];
        $query_param = ['status' => $request['status'], 'search' => $request['search']];

        $subscribed_sub_categories = $this->subscribedService->where('provider_id', $request->user()->provider->id)
            ->with(['category', 'sub_category' => function ($query) {
                return $query->withCount(['services' => function ($query) {
                    $query->ofStatus(1);
                }])->with(['services']);
            }])->whereHas('category', function ($query) {
                $query->where('is_active', 1);
            })->whereHas('sub_category', function ($query) {
                $query->where('is_active', 1);
            })
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                $query->where('is_subscribed', ($request['status'] == 'subscribed' ? 1 : 0));
            })
            ->where(function ($query) use ($keys) {
                foreach ($keys as $key) {
                    $query->orWhereHas('sub_category', function ($query) use ($key) {
                        $query->where('name', 'LIKE', '%' . $key . '%');
                    });
                }
            })
            ->paginate(pagination_limit())->appends($query_param);

        return view('providermanagement::subscribedSubCategory', compact('subscribed_sub_categories', 'status',  'search'));
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function status_update(Request $request, $id): JsonResponse
    {
        $service = $this->subscribedService->where('id', $id)->first();
        $this->subscribedService->where('id', $id)->update(['is_subscribed' => !$service->is_subscribed]);

        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return View|Factory|RedirectResponse|Application
     */
    public function account_info(Request $request): View|Factory|RedirectResponse|Application
    {
        $page_type = $request['page_type'] ?? 'overview';

        if ($page_type == 'overview') {
            $page_type = $request['page_type'];

            $provider = $this->provider->with('owner.account')->withCount(['bookings'])->where('user_id', $request->user()->id)->first();
            $booking_overview = DB::table('bookings')->where('provider_id', $request->user()->provider->id)
                ->select('booking_status', DB::raw('count(*) as total'))
                ->groupBy('booking_status')
                ->get();

            $status = ['accepted', 'ongoing', 'completed', 'canceled'];
            $total = [];
            foreach ($status as $item) {
                if ($booking_overview->where('booking_status', $item)->first() !== null) {
                    $total[] = $booking_overview->where('booking_status', $item)->first()->total;
                } else {
                    $total[] = 0;
                }
            }

            //total earning
            $account = $this->account->where('user_id', $request->user()->id)->first();
            $total_earning = $account['received_balance'] + $account['total_withdrawn'];

            return view('providermanagement::provider.account.overview', compact('page_type', 'provider', 'total', 'total_earning'));

        } //commission-info
        elseif ($page_type == 'commission-info') {

            $provider = $this->provider->where('user_id', $request->user()->id)->first();
            $commission = $provider->commission_status == 1 ? $provider->commission_percentage : (business_config('default_commission', 'business_information'))->live_values;
            return view('providermanagement::provider.account.commission', compact('page_type', 'provider', 'commission'));

        } //review
        elseif ($page_type == 'review') {
            $search = $request->has('search') ? $request['search'] : '';
            $query_param = ['search' => $search, 'page_type' => $request['page_type']];

            $provider = $this->provider->with(['reviews'])->where('user_id', $request->user()->id)->first();
            $reviews = $this->review->with(['booking', 'service' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->when($request->has('search'), function ($query) use ($request) {
                    $query->whereHas('booking', function ($query) use ($request) {
                        $keys = explode(' ', $request['search']);
                        foreach ($keys as $key) {
                            $query->orWhere('readable_id', 'LIKE', '%' . $key . '%');
                        }
                    });
                })
                ->where('provider_id', $provider->id)
                ->latest()
                ->paginate(pagination_limit())->appends($query_param);

            return view('providermanagement::provider.account.review', compact('page_type', 'reviews', 'search', 'provider'));

        }
        //promotional cost
        elseif ($page_type == 'promotional_cost') {
            $promotional_cost_percentage = $this->business_settings->where('settings_type', 'promotional_setup')->get();
            return view('providermanagement::provider.account.promotional-cost', compact('page_type', 'promotional_cost_percentage'));
        }


        Toastr::error(translate('no_data_found'));
        return back();
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function bank_info(Request $request): Renderable
    {
        $provider = $this->provider->with('bank_detail')->where('user_id', $request->user()->id)->first();
        return view('providermanagement::bank-info', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_bank_info(Request $request)
    {
        Validator::make($request->all(), [
            'bank_name' => 'required',
            'branch_name' => 'required',
            'acc_no' => 'required',
            'acc_holder_name' => 'required',
            'routing_number' => 'required',
        ]);

        $this->bank_detail::updateOrCreate(
            ['provider_id' => $request->user()->provider->id],
            [
                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'acc_no' => $request->acc_no,
                'acc_holder_name' => $request->acc_holder_name,
                'routing_number' => $request->routing_number,
            ]
        );

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function available_services(Request $request): Renderable
    {
        return view('providermanagement::available-services');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function profile_info(Request $request): Renderable
    {
        $provider = $this->provider->with(['owner.addresses', 'zone'])->where('user_id', $request->user()->id)->first();
        $zones = $this->zone->select('id', 'name')->get();
        return view('providermanagement::profile-update', compact('provider', 'zones'));
    }

    /**
     * Modify provider information
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update_profile(Request $request): RedirectResponse
    {
        Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'contact_person_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'contact_person_email' => 'required',
            'zone_id' => 'required',

            'password' => isset($request->password) ? 'string|min:8' : '',
            'confirm_password' => isset($request->password) ? 'required|same:password' : '',

            'company_name' => 'required',
            'company_email' => 'required|unique:providers,id,' . $request->user()->provider->id,
            'company_phone' => 'required|unique:providers,id,' . $request->user()->provider->id,
            'company_address' => 'required',
            'logo' => 'image|mimes:jpeg,jpg,png,gif|max:10000',

            'latitude' => 'required',
            'longitude' => 'required',
        ])->validate();

        $provider = $this->provider::where('user_id', $request->user()->id)->first();
        $provider->company_name = $request->company_name;
        $provider->company_email = $request->company_email;
        $provider->company_phone = $request->company_phone;
        if ($request->has('logo')) {
            $provider->logo = file_uploader('provider/logo/', 'png', $request->file('logo'), $provider->logo);
        }
        $provider->company_address = $request->company_address;

        $provider->contact_person_name = $request->contact_person_name;
        $provider->contact_person_phone = $request->contact_person_phone;
        $provider->contact_person_email = $request->contact_person_email;
        $provider->zone_id = $request['zone_id'];
        $provider->coordinates = [
            'latitude' => $request['latitude'],
            'longitude' => $request['longitude'],
        ];

        $owner = $this->user->where('id', $request->user()->id)->first();
        $owner->first_name = $request->account_first_name;
        $owner->last_name = $request->account_last_name;
        //$owner->phone = $request->account_phone;

        if (isset($request->password)) {
            $owner->password = bcrypt($request->password);
        }

        DB::transaction(function () use ($provider, $owner) {
            $owner->save();
            $provider->save();
        });

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    public function download(Request $request)
    {
        $keys = explode(' ', $request['search']);
        $items = $this->subscribedService->where('provider_id', $request->user()->provider->id)
            ->with(['sub_category' => function ($query) {
                return $query->withCount('services')->with(['services']);
            }])
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->where('is_subscribed', (($request['status'] == 'subscribed') ? 1 : 0));
            })
            ->where(function ($query) use ($keys) {
                foreach ($keys as $key) {
                    $query->orWhereHas('sub_category', function ($query) use ($key) {
                        $query->where('name', 'LIKE', '%' . $key . '%');
                    });
                }
            })->get();
        return (new FastExcel($items))->download(time() . '-file.xlsx');
    }

    public function reviews_download(Request $request)
    {
        $items = $this->review->with(['booking'])
            ->when($request->has('search'), function ($query) use ($request) {
                $query->whereHas('booking', function ($query) use ($request) {
                    $keys = explode(' ', $request['search']);
                    foreach ($keys as $key) {
                        $query->orWhere('readable_id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->where('provider_id', auth()->user()->provider->id)
            ->latest()
            ->get();
        return (new FastExcel($items))->download(time() . '-file.xlsx');
    }
}
