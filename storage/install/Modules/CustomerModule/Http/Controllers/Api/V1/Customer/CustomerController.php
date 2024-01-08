<?php

namespace Modules\CustomerModule\Http\Controllers\Api\V1\Customer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\SMSModule\Lib\SMS_gateway;
use Modules\TransactionModule\Entities\LoyaltyPointTransaction;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserAddress;
use Illuminate\Support\Facades\Mail;
use Modules\PaymentModule\Traits\SmsGateway;

class CustomerController extends Controller
{

    private $customer;
    private $transaction;
    private $loyalty_point_transaction;

    public function __construct(User $user, Transaction $transaction, LoyaltyPointTransaction $loyalty_point_transaction)
    {
        $this->customer = $user;
        $this->transaction = $transaction;
        $this->loyalty_point_transaction = $loyalty_point_transaction;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        if (in_array($request->user()->user_type, CUSTOMER_USER_TYPES)) {
            $customer = $this->customer->withCount('bookings')->where('id', auth()->user()->id)->first();
            return response()->json(response_formatter(DEFAULT_200, $customer), 200);
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
        $customer = $this->customer::find($request->user()->id);
        if (!isset($customer)) {
            return response()->json(response_formatter(DEFAULT_400), 400);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => '',
            'password' => '',
            'profile_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->phone = $request->phone;

        if ($request->has('profile_image')) {
            $customer->profile_image = file_uploader('user/profile_image/', 'png', $request->file('profile_image'), $customer->profile_image);;
        }

        if (!is_null($request['password'])) {
            $customer->password = bcrypt($request->password);
        }
        $customer->save();

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }


    /**
     * Modify provider information
     * @param Request $request
     * @return JsonResponse
     */
    public function update_fcm_token(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $customer = $this->customer::find($request->user()->id);
        $customer->fcm_token = $request->fcm_token;
        $customer->save();

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function remove_account(Request $request): JsonResponse
    {
        $customer = $this->customer->whereIn('user_type', CUSTOMER_USER_TYPES)->find($request->user()->id);
        if (!isset($customer)) {
            return response()->json(response_formatter(DEFAULT_404), 200);
        }

        file_remover('user/profile_image/', $customer->profile_image);
        foreach ($customer->identification_image as $image_name){
            file_remover('user/identity/', $image_name);
        }
        $customer->forceDelete();

        return response()->json(response_formatter(DEFAULT_204), 200);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function transfer_loyalty_point_to_wallet(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'point' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }


        //user point check (if has sufficient amount)
        $user = $this->customer->find($request->user()->id);
        if($request['point'] > $user->loyalty_point) {
            return response()->json(response_formatter(DEFAULT_400, null, null), 400);
        }

        //minimum point check (for transferring)
        $min_point = business_config('min_loyalty_point_to_transfer', 'customer_config')->live_values;
        if ($request['point'] < $min_point ) {
            return response()->json(response_formatter(DEFAULT_400, null, null), 400);
        }

        $point_value_per_currency_unit = business_config('loyalty_point_value_per_currency_unit', 'customer_config')->live_values;
        $loyalty_amount = $request['point']/$point_value_per_currency_unit;

        //point transfer transaction
        loyalty_point_wallet_transfer_transaction($user->id, $request['point'], $loyalty_amount);

        return response()->json(response_formatter(DEFAULT_200), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function wallet_transaction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'type' => 'nullable|in:' . implode(',', array_keys(WALLET_TRX_TYPE)),
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $transactions = $this->transaction
            ->with(['booking', 'from_user', 'to_user'])
            ->where('to_user_id', $request->user()->id)
            ->when(!is_null($request['type']), fn($query) => $query->where('trx_type', $request['type']))
            ->when(is_null($request['type']), fn($query) => $query->whereIn('trx_type', WALLET_TRX_TYPE))
            ->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $user = $this->customer->find($request->user()->id);

        return response()->json(response_formatter(DEFAULT_204, [
            'wallet_balance' => with_decimal_point($user->wallet_balance),
            'transactions' => $transactions
        ]), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function loyalty_point_transaction(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $transactions = $this->loyalty_point_transaction
            ->with(['user'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $user = $this->customer->find($request->user()->id);

        return response()->json(response_formatter(DEFAULT_204, [
            'loyalty_point' => $user->loyalty_point,
            'loyalty_point_value_per_currency_unit' => business_config('loyalty_point_value_per_currency_unit', 'customer_config')->live_values,
            'min_loyalty_point_to_transfer' => business_config('min_loyalty_point_to_transfer', 'customer_config')->live_values,
            'transactions' => $transactions
        ]), 200);
    }

}
