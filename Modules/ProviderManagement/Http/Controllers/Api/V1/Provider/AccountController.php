<?php

namespace Modules\ProviderManagement\Http\Controllers\Api\V1\Provider;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Modules\ProviderManagement\Entities\Provider;
use Modules\TransactionModule\Entities\Account;

class AccountController extends Controller
{
    private Provider $provider;
    private Account $account;
    private BusinessSettings $business_settings;

    public function __construct(Provider $provider, Account $account, BusinessSettings $business_settings)
    {
        $this->provider = $provider;
        $this->account = $account;
        $this->business_settings = $business_settings;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function overview(Request $request): JsonResponse
    {
        $provider = $this->provider->with('owner.account')->where('user_id', $request->user()->id)->first();
        $booking_overview = DB::table('bookings')->where('provider_id', $request->user()->provider->id)
            ->select('booking_status', DB::raw('count(*) as total'))
            ->groupBy('booking_status')
            ->get();

        $promotional_costs = $this->business_settings->where('settings_type', 'promotional_setup')->get();
        $promotional_cost_percentage = [];

        $data = $promotional_costs->where('key_name', 'discount_cost_bearer')->first()->live_values;
        $promotional_cost_percentage['discount'] = $data['provider_percentage'];

        $data = $promotional_costs->where('key_name', 'campaign_cost_bearer')->first()->live_values;
        $promotional_cost_percentage['campaign'] = $data['provider_percentage'];

        $data = $promotional_costs->where('key_name', 'coupon_cost_bearer')->first()->live_values;
        $promotional_cost_percentage['coupon'] = $data['provider_percentage'];

        return response()->json(response_formatter(DEFAULT_200, ['provider_info' => $provider, 'booking_overview' => $booking_overview, 'promotional_cost_percentage' => $promotional_cost_percentage]), 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function account_edit(Request $request): JsonResponse
    {
        $provider = $this->provider->with('owner')->find($request->user()->id);
        if (isset($provider)) {
            return response()->json(response_formatter(DEFAULT_200, $provider), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function account_update(Request $request): JsonResponse
    {
        $provider = $this->provider->with('owner')->find($request->user()->id);
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
            'logo' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $provider->company_name = $request->company_name;
        $provider->company_phone = $request->company_phone;
        if ($request->has('logo')) {
            $provider->logo = file_uploader('provider/logo/', 'png', $request->file('logo'));
        }
        $provider->company_address = $request->company_address;
        $provider->contact_person_name = $request->contact_person_name;
        $provider->contact_person_phone = $request->contact_person_phone;
        $provider->contact_person_email = $request->contact_person_email;

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
            $provider->save();
        });

        return response()->json(response_formatter(PROVIDER_STORE_200), 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function commission_info(Request $request): JsonResponse
    {
        $provider = $this->provider->with('owner')->where('user_id',$request->user()->id)->first();
        if (isset($provider)) {
            return response()->json(response_formatter(DEFAULT_200, [
                'commission_status' => $provider['commission_status'],
                'commission_percentage' => $provider['commission_percentage']
            ]), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }
}
