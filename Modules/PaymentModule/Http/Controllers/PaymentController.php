<?php

namespace Modules\PaymentModule\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Modules\CustomerModule\Traits\CustomerAddressTrait;
use Illuminate\Support\Facades\Validator;
use Modules\PaymentModule\Traits\PaymentHelperTrait;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserAddress;
use Modules\PaymentModule\Traits\Payment as PaymentTrait;
use Modules\PaymentModule\Library\Payment as Payment;
use Modules\PaymentModule\Library\Payer;
use Modules\PaymentModule\Library\Receiver;


class PaymentController extends Controller
{
    use CustomerAddressTrait, PaymentHelperTrait;

    /**
     * @param Request $request
     * @return JsonResponse|Redirector|RedirectResponse|Application
     * @throws ValidationException
     */
    public function index(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        if(is_null($request['is_add_fund'])) {
            $validator = Validator::make($request->all(), [
                'access_token' => '',
                'zone_id' => 'required|uuid',
                'service_schedule' => 'required|date',
                'service_address_id' => is_null($request['service_address']) ? 'required' : 'nullable',
                'service_address' => is_null($request['service_address_id']) ? 'required' : 'nullable',
                'payment_method' => 'required|in:' . implode(',', array_column(GATEWAYS_PAYMENT_METHODS, 'key')),
                'callback' => 'nullable',
                //For bidding
                'post_id' => 'nullable|uuid',
                'provider_id' => 'nullable|uuid',
                'is_partial' => 'nullable:in:0,1',
                'payment_platform' => 'nullable|in:web,app'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'access_token' => '',
                'amount' => 'required|numeric',
                'payment_method' => 'required|in:' . implode(',', array_column(PAYMENT_METHODS, 'key')),
                'payment_platform' => 'nullable|in:web,app'
            ]);
        }

        if ($validator->fails()) {
            if ($request->has('callback')) return redirect($request['callback'] . '?payment_status=fail');
            else return response()->json(response_formatter(DEFAULT_400), 400);
        }

        //customer user
        $customer_user_id = base64_decode($request['access_token']);
        $is_guest = !User::where('id', $customer_user_id)->exists();
        $is_add_fund = $request['is_add_fund'] == 1 ? 1 : 0;

        //==========>>>>>> IF ADD FUND <<<<<<<==============

        //add fund
        if ($is_add_fund) {
            $customer = User::find($customer_user_id);
            $payer = new Payer($customer['first_name'].' '.$customer['last_name'], $customer['email'], $customer['phone'], '');
            $payment_info = new Payment(
                success_hook: 'add_fund_success',
                failure_hook: 'add_fund_fail',
                currency_code: currency_code(),
                payment_method: $request['payment_method'],
                payment_platform: $request['payment_platform'],
                payer_id: $customer_user_id,
                receiver_id: null,
                additional_data: $validator->validated(),
                payment_amount: $request['amount'],
                external_redirect_link: $request['callback'] ?? null,
                attribute: null,
                attribute_id: null
            );

            $receiver_info = new Receiver('receiver_name', 'example.png');
            $redirect_link = PaymentTrait::generate_link($payer, $payment_info, $receiver_info);
            return redirect($redirect_link);
        }

        //==========>>>>>> IF Booking <<<<<<<==============

        //service address create (if no saved address)
        $service_address = json_decode(base64_decode($request['service_address']));
        if (!collect($service_address)->has(['lat', 'lon', 'address', 'contact_person_name', 'contact_person_number', 'address_label'])) {
            if ($request->has('callback')) return redirect($request['callback'] . '?payment_status=fail');
            else return response()->json(response_formatter(DEFAULT_400), 400);
        }
        if (is_null($request['service_address_id'])) {
            $request['service_address_id'] = $this->add_address($service_address, null, $is_guest);
        }
        if (is_null($request['service_address_id'])) {
            if ($request->has('callback')) return redirect($request['callback'] . '?payment_status=fail');
            else return response()->json(response_formatter(DEFAULT_400), 400);
        }
        $query_params = array_merge($validator->validated(), ['service_address_id' => $request['service_address_id']]);

        //guest user check
        if ($is_guest) {
            $address = UserAddress::find($request['service_address_id']);
            $customer = collect([
                'first_name' => $address['contact_person_name'],
                'last_name' => '',
                'phone' => $address['contact_person_number'],
                'email' => '',
            ]);

        } else {
            $customer = User::find($customer_user_id);
            $customer = collect([
                'first_name' => $customer['first_name'],
                'last_name' => $customer['last_name'],
                'phone' => $customer['phone'],
                'email' => $customer['email'],
            ]);
        }
        $query_params['customer'] = base64_encode($customer);

        $total_booking_amount = $this->find_total_Booking_amount($customer_user_id, $request['post_id'], $request['provider_id']);
        $customer_wallet_balance = User::find($customer_user_id)?->wallet_balance;
        $amount_to_pay = $request['is_partial'] ? ($total_booking_amount - $customer_wallet_balance) : $total_booking_amount;

        //partial validation
        if (!$is_guest && $request['is_partial'] && ($customer_wallet_balance <= 0 || $customer_wallet_balance >= $total_booking_amount)) {
            return response()->json(response_formatter(DEFAULT_400), 400);
        }

        //make payment
        $payer = new Payer($customer['first_name'].' '.$customer['last_name'], $customer['email'], $customer['phone'], '');
        $payment_info = new Payment(
            success_hook: 'digital_payment_success',
            failure_hook: 'digital_payment_fail',
            currency_code: currency_code(),
            payment_method: $request->payment_method,
            payment_platform: $request->payment_platform,
            payer_id: $customer_user_id,
            receiver_id: null,
            additional_data: $query_params,
            payment_amount: $amount_to_pay,
            external_redirect_link: $request['callback'] ?? null,
            attribute: null,
            attribute_id: null
        );

        $receiver_info = new Receiver('receiver_name', 'example.png');
        $redirect_link = PaymentTrait::generate_link($payer, $payment_info, $receiver_info);
        return redirect($redirect_link);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Redirector|RedirectResponse|Application
     */
    public function success(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        if (isset($request['callback'])) return redirect($request['callback'] . '?flag=success');
        else return response()->json(response_formatter(DEFAULT_200), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse|Redirector|RedirectResponse|Application
     */
    public function fail(Request $request): JsonResponse|Redirector|RedirectResponse|Application
    {
        if ($request->has('callback')) return redirect($request['callback'] . '?flag=fail');
        else return response()->json(response_formatter(DEFAULT_400), 400);
    }
}
