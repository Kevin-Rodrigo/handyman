<?php

namespace Modules\ProviderManagement\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ProviderManagement\Entities\SubscribedService;
use Modules\ProviderManagement\Http\Requests\ProviderStoreRequest;
use Modules\ReviewModule\Entities\Review;
use Modules\ServiceManagement\Entities\Service;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\User;
use function bcrypt;
use function file_remover;
use function file_uploader;
use function response;
use function response_formatter;

class ProviderController extends Controller
{
    protected Provider $provider;
    protected User $owner;
    protected User $user;
    protected $service;
    protected $subscribedService;
    private Booking $booking;
    private $account;
    private $category;
    private $serviceman;
    private $review;
    protected Transaction $transaction;

    public function __construct(Transaction $transaction, Review $review, Serviceman $serviceman, Category $category, Account $account, Provider $provider, User $owner, Service $service, SubscribedService $subscribedService, Booking $booking)
    {
        $this->provider = $provider;
        $this->owner = $owner;
        $this->user = $owner;
        $this->service = $service;
        $this->subscribedService = $subscribedService;
        $this->booking = $booking;
        $this->account = $account;
        $this->category = $category;
        $this->serviceman = $serviceman;
        $this->review = $review;
        $this->transaction = $transaction;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param string $provider_id
     * @return JsonResponse
     */
    public function overview(Request $request, string $provider_id): JsonResponse
    {
        $provider = $this->provider->with('owner.account')->where('id', $provider_id)->first();

        if (!$this->account->where('user_id', $provider['user_id'])->exists()) {
            $this->account->create(['user_id' => $provider['user_id'], 0, 0, 0, 0, 0]);
        }

        $booking_overview = DB::table('bookings')->where('provider_id', $provider->id)
            ->select('booking_status', DB::raw('count(*) as total'))
            ->groupBy('booking_status')
            ->get();
        return response()->json(response_formatter(DEFAULT_200, ['provider_info' => $provider, 'booking_overview' => $booking_overview]), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'string' => 'string',
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:active,inactive,all'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $providers = $this->provider->with(['owner', 'zone'])->where(['is_approved' => 1])->withCount(['subscribed_services', 'bookings'])
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('company_phone', 'LIKE', '%' . $key . '%')
                            ->orWhere('company_email', 'LIKE', '%' . $key . '%')
                            ->orWhere('company_name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->ofApproval(1)
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $top_cards = [];
        $top_cards['total_providers'] = $this->provider->ofApproval(1)->count();
        $top_cards['total_onboarding_requests'] = $this->provider->ofApproval(2)->count();
        $top_cards['total_active_providers'] = $this->provider->ofApproval(1)->ofStatus(1)->count();
        $top_cards['total_inactive_providers'] = $this->provider->ofApproval(1)->ofStatus(0)->count();

        return response()->json(response_formatter(DEFAULT_200, ['providers' => $providers, 'top_cards' => $top_cards]), 200);
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param string $provider_id
     * @return JsonResponse
     */
    public function reviews(Request $request, string $provider_id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:active,inactive,all'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $reviews = $this->review->where('provider_id', $provider_id)
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $rating_group_count = DB::table('reviews')->where('provider_id', $provider_id)
            ->select('review_rating', DB::raw('count(*) as total'))
            ->groupBy('review_rating')
            ->get();

        $rating_info = [
            'rating_count' => $this->provider->find($provider_id)->rating_count,
            'average_rating' => $this->provider->find($provider_id)->avg_rating,
            'rating_group_count' => $rating_group_count,
        ];

        if ($reviews->count() > 0) {
            return response()->json(response_formatter(DEFAULT_200, ['reviews' => $reviews, 'rating' => $rating_info]), 200);
        }

        return response()->json(response_formatter(DEFAULT_404), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param string $provider_id
     * @return JsonResponse
     */
    public function subscribed_sub_categories(Request $request, string $provider_id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $subscribed = $this->subscribedService->where('provider_id', $provider_id)
            ->with(['sub_category' => function ($query) {
                return $query->withCount('services')->with(['services']);
            }])
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $subscribed), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param ProviderStoreRequest $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'contact_person_phone' => 'required',
            'contact_person_email' => 'required',

            'account_first_name' => 'required',
            'account_last_name' => 'required',
            'account_email' => 'required|email|unique:users,email',
            'account_phone' => 'required|unique:users,phone',
            'password' => 'required',
            'confirm_password' => 'required|same:password',

            'company_name' => 'required',
            'company_phone' => 'required|unique:providers',
            'company_address' => 'required',
            'company_email' => 'required|email|unique:providers',
            'logo' => 'required|image|mimes:jpeg,jpg,png,gif',

            'identity_type' => 'required|in:passport,driving_license,nid,trade_license,company_id',
            'identity_number' => 'required',
            'identity_images' => 'required|array',
            'identity_images.*' => 'image|mimes:jpeg,jpg,png,gif',

            'zone_ids' => 'required|array',
            'zone_ids.*' => 'uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $identity_images = [];
        foreach ($request->identity_images as $image) {
            $identity_images[] = file_uploader('provider/identity/', 'png', $image);
        }

        $provider = $this->provider;
        $provider->company_name = $request->company_name;
        $provider->company_phone = $request->company_phone;
        $provider->company_email = $request->company_email;
        $provider->logo = file_uploader('provider/logo/', 'png', $request->file('logo'));
        $provider->company_address = $request->company_address;

        $provider->contact_person_name = $request->contact_person_name;
        $provider->contact_person_phone = $request->contact_person_phone;
        $provider->contact_person_email = $request->contact_person_email;
        $provider->is_approved = 1;
        $provider->is_active = 1;
        $provider->zone_id = $request['zone_ids'][0];

        $owner = $this->owner;
        $owner->first_name = $request->account_first_name;
        $owner->last_name = $request->account_last_name;
        $owner->email = $request->account_email;
        $owner->phone = $request->account_phone;
        $owner->identification_number = $request->identity_number;
        $owner->identification_type = $request->identity_type;
        $owner->is_active = 1;
        $owner->identification_image = $identity_images;
        $owner->password = bcrypt($request->password);
        $owner->user_type = 'provider-admin';

        DB::transaction(function () use ($provider, $owner, $request) {
            $owner->save();
            $owner->zones()->sync($request->zone_ids);
            $provider->user_id = $owner->id;
            $provider->save();
        });

        return response()->json(response_formatter(PROVIDER_STORE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param string $provider_id
     * @return JsonResponse
     */
    public function bookings(Request $request, string $provider_id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $bookings = $this->booking->with(['customer'])->where(['provider_id' => $provider_id])
            ->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $bookings), 200);
    }


    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function edit(string $id): JsonResponse
    {
        $provider = $this->provider->with(['owner', 'zone'])->find($id);

        if (isset($provider)) {
            return response()->json(response_formatter(DEFAULT_200, $provider), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $provider = $this->provider->with('owner')->find($id);

        if (!isset($provider)) {
            return response()->json(response_formatter(DEFAULT_204), 200);
        }

        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'contact_person_phone' => 'required',
            'contact_person_email' => 'required',

            'password' => 'string|min:8',
            'confirm_password' => 'same:password',
            'account_first_name' => 'required',
            'account_last_name' => 'required',
            'account_phone' => 'required|unique:users,phone,' . $provider->user_id . ',id',

            'company_name' => 'required',
            'company_phone' => 'required|unique:providers,company_phone,' . $provider->id . ',id',
            'company_address' => 'required',
            'company_email' => 'required|email|unique:providers,company_email,' . $provider->id . ',id',
            'logo' => 'image|mimes:jpeg,jpg,png,gif|max:10000',

            'zone_ids' => 'required|array',
            'zone_ids.*' => 'uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $provider->company_name = $request->company_name;
        $provider->company_phone = $request->company_phone;
        $provider->company_email = $request->company_email;
        if ($request->has('logo')) {
            $provider->logo = file_uploader('provider/logo/', 'png', $request->file('logo'));
        }
        $provider->company_address = $request->company_address;
        $provider->contact_person_name = $request->contact_person_name;
        $provider->contact_person_phone = $request->contact_person_phone;
        $provider->contact_person_email = $request->contact_person_email;
        $provider->zone_id = $request['zone_ids'][0];

        $owner = $provider->owner()->first();
        $owner->first_name = $request->account_first_name;
        $owner->last_name = $request->account_last_name;
        $owner->phone = $request->account_phone;
        if ($request->has('password')) {
            $owner->password = bcrypt($request->password);
        }
        $owner->user_type = 'provider-admin';

        DB::transaction(function () use ($provider, $owner, $request) {
            $owner->save();
            $owner->zones()->sync($request->zone_ids);
            $provider->save();
        });

        return response()->json(response_formatter(PROVIDER_STORE_200), 200);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $provider_id
     * @return JsonResponse
     */
    public function update_subscription(Request $request, string $provider_id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'sub_category_ids' => 'required|array',
            'sub_category_ids.*' => 'uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($request['sub_category_ids'] as $id) {
            $subscribedService = $this->subscribedService::where('sub_category_id', $id)->where('provider_id', $provider_id)->first();
            if (!isset($subscribedService)) {
                $subscribedService = $this->subscribedService;
            }
            $subscribedService->provider_id = $provider_id;
            $subscribedService->sub_category_id = $id;
            $parent = $this->category->where('id', $id)->first();
            if (isset($parent)) {
                $subscribedService->category_id = $parent->parent_id;
            }
            $subscribedService->is_subscribed = $subscribedService ? !$subscribedService->is_subscribed : 1;
            $subscribedService->save();
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param string $provider_id
     * @return JsonResponse
     */
    public function serviceman_list(Request $request, string $provider_id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:active,inactive,all'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $serviceman = $this->serviceman->with('user')
            ->where('provider_id', $provider_id)
            ->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $serviceman), 200);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function settings_update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'commission_percentage' => 'required|numeric|min:1|max:100',
            'commission_settings' => 'required|in:default,custom'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $provider = $this->provider->find($id);
        $provider->commission_percentage = $request->commission_percentage;
        $provider->commission_status = $request->commission_settings == 'default' ? 0 : 1;
        $provider->save();

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'provider_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $providers = $this->provider->whereIn('id', $request['provider_ids']);
        if ($providers->count() > 0) {
            foreach ($providers->get() as $provider) {
                file_remover('provider/logo/', $provider->logo);
                if (!empty($provider->owner->identification_image)) {
                    foreach ($provider->owner->identification_image as $image) {
                        file_remover('provider/identity/', $image);
                    }
                }
                $provider->owner()->delete();
            }
            $providers->delete();
            return response()->json(response_formatter(DEFAULT_DELETE_200), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function remove_image(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|uuid',
            'image_name' => 'required|string',
            'image_type' => 'required|in:logo,identity_image'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $provider = $this->provider->with('owner')->where('id', $request['provider_id'])->first();
        if ($request['image_type'] == 'identity_image') {
            file_remover('provider/identity/', $request['image_name']);
            $provider->owner()->identification_image = array_diff($provider->owner()->identification_image, $request['image_name']);
            $provider->owner()->save();
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function status_update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:1,0',
            'provider_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->provider->whereIn('id', $request['provider_ids'])->update(['is_active' => $request['status'], 'is_approved' => $request['status']]);
        $providers = $this->provider->whereIn('id', $request['provider_ids'])->get();
        foreach ($providers as $provider) {
            $provider->owner()->update(['is_active' => $request['status']]);
        }

        return response()->json(response_formatter(DEFAULT_STATUS_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function provider_request(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'request_status' => 'required|in:pending,denied,all'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $providers = $this->provider->with(['owner', 'zone'])
            ->when($request->has('request_status') && $request['request_status'] != 'all', function ($query) use ($request) {
                return $query->ofApproval(($request['request_status'] == 'pending') ? 2 : 0);
            })->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $onboarding_count = $this->provider->ofApproval(2)->count();
        $denied_count = $this->provider->ofApproval(0)->count();

        return response()->json(response_formatter(DEFAULT_200, [
            'providers' => $providers,
            'onboarding_count' => $onboarding_count,
            'denied_count' => $denied_count
        ]), 200);
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function search_request(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'string' => 'required',
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:onboarding,denied,all'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $keys = explode(' ', base64_decode($request['string']));
        $providers = $this->provider->with(['owner', 'owner.zones'])
            ->where(function ($query) use ($keys) {
                foreach ($keys as $key) {
                    $query->orWhere('company_phone', 'LIKE', '%' . $key . '%')
                        ->orWhere('company_email', 'LIKE', '%' . $key . '%')
                        ->orWhere('company_name', 'LIKE', '%' . $key . '%');
                }
            })->when($request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'onboarding') ? 0 : 2);
            })->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        if ($providers->count() > 0) {
            return response()->json(response_formatter(DEFAULT_200, $providers), 200);
        }
        return response()->json(response_formatter(DEFAULT_204, $providers), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $provider_user_id
     * @return JsonResponse
     */
    public function collect_cash(Request $request, string $provider_user_id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'collected_amount' => 'required|numeric|min:1|max:1000000000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 403);
        }

        $account = $this->account->where('user_id', $provider_user_id)->first();

        if ($account->account_payable < $request['collected_amount']) {
            return response()->json(response_formatter(DEFAULT_FAIL_200), 200);
        }

        $account->account_payable -= $request['collected_amount'];
        $account->save();

        $admin = $this->user->where('user_type', 'super-admin')->first();

        $admin_account = $this->account->where('user_id', $admin->id)->first();
        $admin_account->received_balance += $request['collected_amount'];
        $admin_account->save();

        //make transaction
        $transaction = $this->transaction::create([
            'ref_trx_id' => null,
            'booking_id' => null,
            'trx_type' => null,
            'debit' => 0,
            'credit' => $request['collected_amount'],
            'balance' => 0,
            'from_user_id' => $provider_user_id,
            'to_user_id' => $admin->id
        ]);

        //second transactions
        $transaction::create([
            'ref_trx_id' => $transaction['trx_id'],
            'booking_id' => null,
            'trx_type' => null,
            'debit' => $request['collected_amount'],
            'credit' => 0,
            'balance' => 0,
            'from_user_id' => $provider_user_id,
            'to_user_id' => $admin->id
        ]);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

}
