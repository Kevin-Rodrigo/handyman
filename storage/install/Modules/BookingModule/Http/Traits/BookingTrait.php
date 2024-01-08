<?php

namespace Modules\BookingModule\Http\Traits;

use Illuminate\Support\Facades\DB;
use Modules\BookingModule\Entities\BookingPartialPayment;
use Modules\CartModule\Entities\Cart;
use Modules\UserManagement\Entities\User;
use Modules\BookingModule\Entities\Booking;
use Modules\ServiceManagement\Entities\Service;
use Modules\BookingModule\Entities\BookingDetail;
use Modules\ProviderManagement\Entities\Provider;
use Modules\BookingModule\Events\BookingRequested;
use Modules\BookingModule\Entities\BookingDetailsAmount;
use Modules\BookingModule\Entities\BookingOfflinePayment;
use Modules\BookingModule\Entities\BookingStatusHistory;
use Modules\BookingModule\Entities\BookingScheduleHistory;
use Modules\ProviderManagement\Entities\SubscribedService;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

trait BookingTrait
{
    //=============== PLACE BOOKING ===============

    /**
     * @param $user_id
     * @param $request
     * @param $transaction_id
     * @param $is_guest
     * @return array|string[]
     */
    public function place_booking_request($user_id, $request, $transaction_id, $is_guest = 0): array
    {
        $cart_data = Cart::where(['customer_id' => $user_id])->get();

        if ($cart_data->count() == 0) {
            return ['flag' => 'failed', 'message' => 'no data found'];
        }

        //partial booking validation
        $is_partial = $request['is_partial'] ? 1 : 0;
        $customer_wallet_balance = User::find($user_id)?->wallet_balance;
        if ($is_partial && $is_guest && ($customer_wallet_balance <= 0 || $customer_wallet_balance >= $cart_data->sum('total_cost'))) {
            return ['flag' => 'failed', 'message' => 'Invalid data'];
        }

        $booking_ids = [];
        foreach ($cart_data->pluck('sub_category_id')->unique() as $sub_category) {

            $booking = new Booking();

            DB::transaction(function () use ($sub_category, $booking, $transaction_id, $request, $cart_data, $user_id, $is_guest, $is_partial, $customer_wallet_balance) {
                $cart_data = $cart_data->where('sub_category_id', $sub_category);

                if ($request->has('payment_method') && $request['payment_method'] == 'cash_after_service') {
                    $transaction_id = 'cash-payment';

                } else if ($request->has('payment_method') && $request['payment_method'] == 'wallet_payment') {
                    $transaction_id = 'wallet-payment';
                }

                $total_booking_amount = $cart_data->sum('total_cost');

                $booking_additional_charge_status = business_config('booking_additional_charge', 'booking_setup')->live_values ?? 0;
                $extra_fee = 0;
                if ($booking_additional_charge_status) {
                    $extra_fee = business_config('additional_charge_fee_amount', 'booking_setup')->live_values ?? 0;
                }
                $total_booking_amount += $extra_fee;

                //bookings
                $booking->customer_id = $user_id;
                $booking->provider_id = $cart_data->first()->provider_id;
                $booking->category_id = $cart_data->first()->category_id;
                $booking->sub_category_id = $sub_category;
                $booking->zone_id = config('zone_id') == null ? $request['zone_id'] : config('zone_id');
                $booking->booking_status = isset($booking->provider_id) ? 'accepted' : 'pending';
                $booking->is_paid = $request['payment_method'] == 'cash_after_service' || $request['payment_method'] == 'offline_payment' ? 0 : 1;
                $booking->payment_method = $request['payment_method'];
                $booking->transaction_id = $transaction_id;
                $booking->total_booking_amount = $total_booking_amount ;
                $booking->total_tax_amount = $cart_data->sum('tax_amount');
                $booking->total_discount_amount = $cart_data->sum('discount_amount');
                $booking->total_campaign_discount_amount = $cart_data->sum('campaign_discount');
                $booking->total_coupon_discount_amount = $cart_data->sum('coupon_discount');
                $booking->coupon_code = $cart_data->first()->coupon_code;
                $booking->service_schedule = date('Y-m-d H:i:s', strtotime($request['service_schedule'])) ?? now()->addHours(5);
                $booking->service_address_id = $request['service_address_id'] ?? '';
                $booking->booking_otp = rand(100000, 999999);
                $booking->is_guest = $is_guest;
                $booking->extra_fee = $extra_fee;
                $booking->save();

                //booking_partial_payments
                if ($is_partial) {
                    $paid_amount = $customer_wallet_balance;
                    $due_amount = $total_booking_amount - $paid_amount;

                    $booking_partial_payment = new BookingPartialPayment;
                    $booking_partial_payment->booking_id = $booking->id;
                    $booking_partial_payment->paid_with = 'wallet';
                    $booking_partial_payment->paid_amount = $paid_amount;
                    $booking_partial_payment->due_amount = $due_amount;
                    $booking_partial_payment->save();

                    if ($request['payment_method'] != 'cash_after_service') {
                        $booking_partial_payment = new BookingPartialPayment;
                        $booking_partial_payment->booking_id = $booking->id;
                        $booking_partial_payment->paid_with = $request['payment_method'];
                        $booking_partial_payment->paid_amount = $due_amount;
                        $booking_partial_payment->due_amount = 0;
                        $booking_partial_payment->save();
                    }
                }

                foreach ($cart_data->all() as $datum) {
                    //booking_details
                    $detail = new BookingDetail();
                    $detail->booking_id = $booking->id;
                    $detail->service_id = $datum['service_id'];
                    $detail->service_name = Service::find($datum['service_id'])->name ?? 'service-not-found';
                    $detail->variant_key = $datum['variant_key'];
                    $detail->quantity = $datum['quantity'];
                    $detail->service_cost = $datum['service_cost'];
                    $detail->discount_amount = $datum['discount_amount'];
                    $detail->campaign_discount_amount = $datum['campaign_discount'];
                    $detail->overall_coupon_discount_amount = $datum['coupon_discount'];
                    $detail->tax_amount = $datum['tax_amount'];
                    $detail->total_cost = $datum['total_cost'];
                    $detail->save();

                    //booking_details_amount
                    $booking_details_amount = new BookingDetailsAmount();
                    $booking_details_amount->booking_details_id = $detail->id;
                    $booking_details_amount->booking_id = $booking->id;
                    $booking_details_amount->service_unit_cost = $datum['service_cost'];
                    $booking_details_amount->service_quantity = $datum['quantity'];
                    $booking_details_amount->service_tax = $datum['tax_amount'];
                    $booking_details_amount->discount_by_admin = $this->calculate_discount_cost($datum['discount_amount'])['admin'];
                    $booking_details_amount->discount_by_provider = $this->calculate_discount_cost($datum['discount_amount'])['provider'];
                    $booking_details_amount->campaign_discount_by_admin = $this->calculate_campaign_cost($datum['campaign_discount'])['admin'];
                    $booking_details_amount->campaign_discount_by_provider = $this->calculate_campaign_cost($datum['campaign_discount'])['provider'];
                    $booking_details_amount->coupon_discount_by_admin = $this->calculate_coupon_cost($datum['coupon_discount'])['admin'];
                    $booking_details_amount->coupon_discount_by_provider = $this->calculate_coupon_cost($datum['coupon_discount'])['provider'];
                    //admin commission will update after complete the service
                    $booking_details_amount->save();
                }

                //booking_schedule_histories
                $schedule = new BookingScheduleHistory();
                $schedule->booking_id = $booking->id;
                $schedule->changed_by = $user_id;
                $schedule->is_guest = $is_guest;
                $schedule->schedule = date('Y-m-d H:i:s', strtotime($request['service_schedule'])) ?? now()->addHours(5);
                $schedule->save();

                //booking_status_histories
                $status_history = new BookingStatusHistory();
                $status_history->changed_by = $booking->id;
                $status_history->booking_id = $user_id;
                $status_history->is_guest = $is_guest;
                $status_history->booking_status = isset($booking->provider_id) ? 'accepted' : 'pending';
                $status_history->save();

                //transactions
                if ($booking->booking_partial_payments->isNotEmpty()) {
                    //if partial
                    if ($booking['payment_method'] == 'cash_after_service') {
                        place_booking_transaction_for_partial_cas($booking);  // waller + CAS payment
                    } elseif ($booking['payment_method'] != 'wallet_payment') {
                        place_booking_transaction_for_partial_digital($booking);  //wallet + digital payment
                    }
                } elseif ($booking['payment_method'] == 'offline_payment') {
                    //offline
                    $customer_information = (array)json_decode(base64_decode($request['customer_information']))[0];
                    $booking_offline_payment = new BookingOfflinePayment();
                    $booking_offline_payment->booking_id = $booking->id;
                    $booking_offline_payment->customer_information = $customer_information;
                    $booking_offline_payment->save();
                } elseif ($booking['payment_method'] != 'cash_after_service' && $booking['payment_method'] != 'wallet_payment') {
                    place_booking_transaction_for_digital_payment($booking);  //digital payment
                } elseif ($booking['payment_method'] != 'cash_after_service') {
                    place_booking_transaction_for_wallet_payment($booking);   //wallet payment
                }

                $max_booking_amount = (business_config('max_booking_amount', 'booking_setup'))?->live_values;

                //provider notification
                if ($booking->payment_method == 'cash_after_service') {
                    if ($max_booking_amount > 0 && $booking->total_booking_amount < $max_booking_amount) {
                        if (isset($booking->provider_id)) {
                            $fcm_token = Provider::with('owner')->whereId($booking->provider_id)->first()->owner->fcm_token ?? null;
                            if (!is_null($fcm_token)) {
                                device_notification($fcm_token, translate('New booking has arrived'), null, null, $booking->id, 'booking');
                            }
                        } else {
                            $provider_ids = SubscribedService::where('sub_category_id', $sub_category)->ofSubscription(1)->pluck('provider_id')->toArray();
                            $providers = Provider::with('owner')->whereIn('id', $provider_ids)->where('zone_id', $booking->zone_id)->get();
                            foreach ($providers as $provider) {
                                $fcm_token = $provider->owner->fcm_token ?? null;
                                if (!is_null($fcm_token)) device_notification($fcm_token, translate('New booking has arrived'), null, null, $booking->id, 'booking');
                            }
                        }
                    }
                } else {
                    if (isset($booking->provider_id)) {
                        $fcm_token = Provider::with('owner')->whereId($booking->provider_id)->first()->owner->fcm_token ?? null;
                        if (!is_null($fcm_token)) {
                            device_notification($fcm_token, translate('New booking has arrived'), null, null, $booking->id, 'booking');
                        }
                    } else {
                        $provider_ids = SubscribedService::where('sub_category_id', $sub_category)->ofSubscription(1)->pluck('provider_id')->toArray();
                        $providers = Provider::with('owner')->whereIn('id', $provider_ids)->where('zone_id', $booking->zone_id)->get();
                        foreach ($providers as $provider) {
                            $fcm_token = $provider->owner->fcm_token ?? null;
                            if (!is_null($fcm_token)) device_notification($fcm_token, translate('New booking has arrived'), null, null, $booking->id, 'booking');
                        }
                    }
                }
            });
            $booking_ids[] = $booking->id;
        }

        cart_clean($user_id);
        event(new BookingRequested($booking));

        return [
            'flag' => 'success',
            'booking_id' => $booking_ids,
            'readable_id' => $booking->readable_id
        ];
    }

    /**
     * @param $user_id
     * @param $request
     * @param $transaction_id
     * @param $data
     * @return array
     */
    protected function place_booking_request_for_bidding($customer_user_id, $request, $transaction_id, $data): array
    {
        $booking = new Booking();

        DB::transaction(function () use ($booking, $transaction_id, $request, $customer_user_id, $data) {

            if ($request->has('payment_method') && $request['payment_method'] == 'cash_after_service') {
                $transaction_id = 'cash-payment';

            } else if ($request->has('payment_method') && $request['payment_method'] == 'wallet_payment') {
                $transaction_id = 'wallet-payment';
            }

            //$tax = round(( (($variation->price-$applicable_discount)*$service['tax'])/100 ) * $quantity, 2);
            $tax = !is_null($data['service_tax']) ? round((($data['price'] * $data['service_tax']) / 100) * 1, 2) : 0; //
            $total_booking_amount = $data['price'] + $tax;

            //partial booking validation
            $is_partial = $data['is_partial'] ? 1 : 0;
            $customer_wallet_balance = User::find($customer_user_id)?->wallet_balance;
            if ($is_partial && ($customer_wallet_balance <= 0 || $customer_wallet_balance >= $total_booking_amount)) {
                return ['flag' => 'failed', 'message' => 'Invalid data'];
            }

            //extra fee
            $booking_additional_charge_status = business_config('booking_additional_charge', 'booking_setup')->live_values ?? 0;
            $extra_fee = 0;
            if ($booking_additional_charge_status) {
                $extra_fee = business_config('additional_charge_fee_amount', 'booking_setup')->live_values ?? 0;
            }

            //bookings
            $booking->customer_id = $customer_user_id;
            $booking->provider_id = $data['provider_id'];
            $booking->category_id = $data['category_id'];
            $booking->sub_category_id = $data['sub_category_id'];
            $booking->zone_id = $data['zone_id'];
            $booking->booking_status = 'accepted';
            $booking->is_paid = $data['payment_method'] == 'cash_after_service' ? 0 : 1;
            $booking->payment_method = $data['payment_method'];
            $booking->transaction_id = $transaction_id;
            $booking->total_booking_amount = $total_booking_amount + $extra_fee;
            $booking->total_tax_amount = $tax;
            $booking->total_discount_amount = 0;
            $booking->total_campaign_discount_amount = 0;
            $booking->total_coupon_discount_amount = 0;
            $booking->service_schedule = date('Y-m-d H:i:s', strtotime($data['service_schedule'])) ?? now()->addHours(5);
            $booking->service_address_id = $data['service_address_id'] ?? '';
            $booking->booking_otp = rand(100000, 999999);
            $booking->is_guest = 0;
            $booking->extra_fee = $extra_fee;
            $booking->save();

            //booking_partial_payments
            if ($is_partial) {
                $paid_amount = $customer_wallet_balance;
                $due_amount = $total_booking_amount - $paid_amount;

                $booking_partial_payment = new BookingPartialPayment;
                $booking_partial_payment->booking_id = $booking->id;
                $booking_partial_payment->paid_with = 'wallet';
                $booking_partial_payment->paid_amount = $paid_amount;
                $booking_partial_payment->due_amount = $due_amount;
                $booking_partial_payment->save();

                if ($request['payment_method'] != 'cash_after_service') {
                    $booking_partial_payment = new BookingPartialPayment;
                    $booking_partial_payment->booking_id = $booking->id;
                    $booking_partial_payment->paid_with = 'digital';
                    $booking_partial_payment->paid_amount = $due_amount;
                    $booking_partial_payment->due_amount = 0;
                    $booking_partial_payment->save();
                }
            }

            //booking_details
            $detail = new BookingDetail();
            $detail->booking_id = $booking->id;
            $detail->service_id = $data['service_id'];
            $detail->service_name = Service::find($data['service_id'])->name ?? 'service-not-found';
            $detail->variant_key = null;
            $detail->quantity = 1;
            $detail->service_cost = $data['price'];
            $detail->discount_amount = 0;
            $detail->campaign_discount_amount = 0;
            $detail->overall_coupon_discount_amount = 0;
            $detail->tax_amount = $tax;
            $detail->total_cost = $total_booking_amount;
            $detail->save();

            //booking_details_amount
            $booking_details_amount = new BookingDetailsAmount();
            $booking_details_amount->booking_details_id = $detail->id;
            $booking_details_amount->booking_id = $booking->id;
            $booking_details_amount->service_unit_cost = $data['price'];
            $booking_details_amount->service_quantity = 1;
            $booking_details_amount->service_tax = $tax;
            $booking_details_amount->discount_by_admin = 0;
            $booking_details_amount->discount_by_provider = 0;
            $booking_details_amount->campaign_discount_by_admin = 0;
            $booking_details_amount->campaign_discount_by_provider = 0;
            $booking_details_amount->coupon_discount_by_admin = 0;
            $booking_details_amount->coupon_discount_by_provider = 0;
            $booking_details_amount->admin_commission = 0;
            $booking_details_amount->save();

            //booking_schedule_histories
            $schedule = new BookingScheduleHistory();
            $schedule->booking_id = $booking->id;
            $schedule->changed_by = $customer_user_id;
            $schedule->schedule = date('Y-m-d H:i:s', strtotime($data['service_schedule'])) ?? now()->addHours(5);
            $schedule->save();

            //booking_status_histories
            $status_history = new BookingStatusHistory();
            $status_history->changed_by = $booking->id;
            $status_history->booking_id = $customer_user_id;
            $status_history->booking_status = isset($booking->provider_id) ? 'accepted' : 'pending';
            $status_history->save();

            //transactions
            if ($booking->booking_partial_payments->isNotEmpty()) {
                //if partial
                if ($booking['payment_method'] == 'cash_after_service') {
                    place_booking_transaction_for_partial_cas($booking);  // waller + CAS payment
                } elseif ($booking['payment_method'] != 'wallet_payment') {
                    place_booking_transaction_for_partial_digital($booking);  //wallet + digital payment
                }
            } elseif ($booking['payment_method'] == 'offline_payment') {
                //offline
                $customer_information = (array)json_decode(base64_decode($request['customer_information']))[0];
                $booking_offline_payment = new BookingOfflinePayment();
                $booking_offline_payment->booking_id = $booking->id;
                $booking_offline_payment->customer_information = $customer_information;
                $booking_offline_payment->save();
            } elseif ($booking['payment_method'] != 'cash_after_service' && $booking['payment_method'] != 'wallet_payment') {
                place_booking_transaction_for_digital_payment($booking);  //digital payment
            } elseif ($booking['payment_method'] != 'cash_after_service') {
                place_booking_transaction_for_wallet_payment($booking);   //wallet payment
            }

            //provider notification
            $fcm_token = Provider::with('owner')->whereId($booking->provider_id)->first()->owner->fcm_token ?? null;
            if (!is_null($fcm_token)) {
                device_notification($fcm_token, translate('New booking has arrived'), null, null, $booking->id, 'booking');
            }
        });

        return [
            'flag' => 'success',
            'booking_id' => $booking->id,
            'readable_id' => $booking->readable_id,
        ];
    }


    //=============== EDIT BOOKING ===============

    /**
     * @param $user_id
     * @param $request
     * @return void
     */
    protected function add_new_booking_service($request): void
    {
        DB::transaction(function () use ($request) {
            $service = Service::with('variations')->find($request['service_id']);
            $variation = $service->variations->where('variant_key', $request['variant_key'])->where('zone_id', $request['zone_id'])->first();
            $quantity = $request['quantity'];
            $booking = Booking::with(['detail', 'details_amounts'])->find($request['booking_id']);

            //remove coupon(from existing booking)
            if ($booking->total_coupon_discount_amount > 0) {
                self::remove_coupon_from_booking($booking, $service);
            }


            // *** promotional discount calculation ***
            $basic_discount = basic_discount_calculation($service, $variation->price * $quantity);
            $campaign_discount = campaign_discount_calculation($service, $variation->price * $quantity);
            $subtotal = round($variation->price * $quantity, 2);

            $applicable_discount = ($campaign_discount >= $basic_discount) ? $campaign_discount : $basic_discount;
            $tax = round(((($variation->price * $quantity - $applicable_discount) * $service['tax']) / 100), 2);

            //between normal discount & campaign discount, greater one will be calculated
            $basic_discount = $basic_discount > $campaign_discount ? $basic_discount : 0;
            $campaign_discount = $campaign_discount >= $basic_discount ? $campaign_discount : 0;

            $new_total = round($subtotal - $basic_discount - $campaign_discount + $tax, 2);

            //bookings
            $booking = Booking::find($request['booking_id']);
            $booking->total_booking_amount += $new_total;
            $booking->total_tax_amount += $tax;
            $booking->total_discount_amount += $basic_discount;
            $booking->total_campaign_discount_amount += $campaign_discount;

            $booking->additional_charge += $new_total;
            $booking->additional_tax_amount += $tax;
            $booking->additional_discount_amount += $basic_discount;
            $booking->additional_campaign_discount_amount += $campaign_discount;
            $booking->save();

            //booking_details
            $detail = BookingDetail::where('booking_id', $booking->id)->where('variant_key', $request['variant_key'])->first();
            if (!$detail) $detail = new BookingDetail();
            $detail->booking_id = $booking->id;
            $detail->service_id = $request['service_id'];
            $detail->service_name = $service->name ?? 'service-not-found';
            $detail->variant_key = $request['variant_key'];
            $detail->quantity += $quantity;
            $detail->service_cost += $variation->price;
            $detail->discount_amount += $basic_discount;
            $detail->campaign_discount_amount += $campaign_discount;
            $detail->overall_coupon_discount_amount = 0;
            $detail->tax_amount += round($tax, 2);
            $detail->total_cost += $new_total;
            $detail->save();

            //booking_details_amount
            $booking_details_amount = BookingDetailsAmount::where('booking_id', $booking->id)->where('booking_details_id', $detail->id)->first();
            if (!$booking_details_amount) $booking_details_amount = new BookingDetailsAmount();
            $booking_details_amount->booking_details_id = $detail->id;
            $booking_details_amount->booking_id = $booking->id;
            $booking_details_amount->service_unit_cost += $detail['service_cost'];
            $booking_details_amount->service_quantity += $quantity;
            $booking_details_amount->service_tax += $detail['tax_amount'];
            $booking_details_amount->discount_by_admin += $this->calculate_discount_cost($detail['discount_amount'])['admin'];
            $booking_details_amount->discount_by_provider += $this->calculate_discount_cost($detail['discount_amount'])['provider'];
            $booking_details_amount->campaign_discount_by_admin += $this->calculate_campaign_cost($detail['campaign_discount_amount'])['admin'];
            $booking_details_amount->campaign_discount_by_provider += $this->calculate_campaign_cost($detail['campaign_discount_amount'])['provider'];
            $booking_details_amount->coupon_discount_by_admin += $this->calculate_coupon_cost($detail['overall_coupon_discount_amount'])['admin'];
            $booking_details_amount->coupon_discount_by_provider += $this->calculate_coupon_cost($detail['overall_coupon_discount_amount'])['provider'];
            //admin commission will update after complete the service
            $booking_details_amount->save();

            //transaction will be done for additional charges > after completing the service status

            //provider notification
            if (isset($booking->provider_id)) {
                $fcm_token = Provider::with('owner')->whereId($booking->provider_id)->first()->owner->fcm_token ?? null;
                if (!is_null($fcm_token)) {
                    device_notification($fcm_token, translate('New service added in the booking'), null, null, $booking->id, 'booking');
                }
            }
        });
    }

    protected function increase_service_quantity_from_booking($request): void
    {
        if (!$request->has('booking_id', 'service_id', 'variant_key', 'zone_id')) return;

        DB::transaction(function () use ($request) {
            $booking_detail = BookingDetail::whereHas('booking', fn($query) => $query->where('id', $request['booking_id']))->where('variant_key', $request['variant_key'])->first();
            $service = Service::with('variations')->find($request['service_id']);
            $variation = $service->variations->where('variant_key', $request['variant_key'])->where('zone_id', $request['zone_id'])->first();
            $booking = Booking::with(['detail', 'details_amounts'])->find($request['booking_id']);

            $old_quantity = $request['old_quantity'];
            $new_quantity = $request['new_quantity'];
            $quantity_to_add = abs($request['old_quantity'] - $request['new_quantity']);

            //remove coupon(from existing booking)
            if ($booking->total_coupon_discount_amount > 0) {
                self::remove_coupon_from_booking($booking, $service);
            }

            // *** promotional discount calculation ***
            $basic_discount = basic_discount_calculation($service, $variation->price * $old_quantity) - basic_discount_calculation($service, $variation->price * $new_quantity);
            $campaign_discount = campaign_discount_calculation($service, $variation->price * $old_quantity) - campaign_discount_calculation($service, $variation->price * $new_quantity);
            $subtotal = round($variation->price * $quantity_to_add, 2);

            $applicable_discount = max($campaign_discount, $basic_discount);
            $tax = round(((($variation->price*$quantity_to_add - $applicable_discount) * $service['tax']) / 100), 2);

            //between normal discount & campaign discount, greater one will be calculated
            $basic_discount = $basic_discount > $campaign_discount ? $basic_discount : 0;
            $campaign_discount = $campaign_discount >= $basic_discount ? $campaign_discount : 0;
            //*** end ***

            $sub_total = round($subtotal - $basic_discount - $campaign_discount + $tax, 2);

            $additional_total = $booking->total_booking_amount - $sub_total;

            //bookings
            $booking = Booking::find($request['booking_id']);
            $booking->additional_charge += $sub_total;
            $booking->additional_tax_amount += $tax;
            $booking->additional_discount_amount += $basic_discount;
            $booking->additional_campaign_discount_amount += $campaign_discount;
            //'removed_coupon_amount' has been updated in function 'remove_coupon_from_booking'
            $booking->total_booking_amount += $sub_total;
            $booking->total_tax_amount += $tax;
            $booking->total_discount_amount += $basic_discount;
            $booking->total_campaign_discount_amount += $campaign_discount;
            $booking->save();


            // *** promotional discount calculation ***
            $basic_discount = basic_discount_calculation($service, $variation->price * $new_quantity);
            $campaign_discount = campaign_discount_calculation($service, $variation->price * $new_quantity);
            $subtotal = round($variation->price * $new_quantity, 2);

            $applicable_discount = ($campaign_discount >= $basic_discount) ? $campaign_discount : $basic_discount;
            $tax = round(((($variation->price*$new_quantity - $applicable_discount) * $service['tax']) / 100), 2);

            //between normal discount & campaign discount, greater one will be calculated
            $basic_discount = $basic_discount > $campaign_discount ? $basic_discount : 0;
            $campaign_discount = $campaign_discount >= $basic_discount ? $campaign_discount : 0;
            //*** end ***

            $sub_total = round($subtotal - $basic_discount - $campaign_discount + $tax, 2);

            //booking_detail
            $booking_detail->quantity = $new_quantity;
            $booking_detail->tax_amount = $tax;
            $booking_detail->total_cost = $sub_total;
            $booking_detail->discount_amount = $basic_discount;
            $booking_detail->campaign_discount_amount = $campaign_discount;
            $booking_detail->overall_coupon_discount_amount = 0;
            $booking_detail->save();

            //booking_details_amount
            $booking_details_amount = BookingDetailsAmount::where('booking_id', $request['booking_id'])->where('booking_details_id', $booking_detail->id)->first();
            $booking_details_amount->service_quantity = $new_quantity;
            $booking_details_amount->service_tax = $tax;
            $booking_details_amount->coupon_discount_by_admin = 0;
            $booking_details_amount->coupon_discount_by_provider = 0;
            $booking_details_amount->discount_by_admin = $this->calculate_discount_cost($booking_detail->discount_amount)['admin'];
            $booking_details_amount->discount_by_provider = $this->calculate_discount_cost($booking_detail->discount_amount)['provider'];
            $booking_details_amount->campaign_discount_by_admin = $this->calculate_campaign_cost($booking_detail->campaign_discount_amount)['admin'];
            $booking_details_amount->campaign_discount_by_provider = $this->calculate_campaign_cost($booking_detail->campaign_discount_amount)['provider'];
            $booking_details_amount->save();

            //transaction will be done for additional charges > after completing the service status

            //provider notification
            if (isset($booking->provider_id)) {
                $fcm_token = Provider::with('owner')->whereId($booking->provider_id)->first()->owner->fcm_token ?? null;
                if (!is_null($fcm_token)) {
                    device_notification($fcm_token, translate('Booking modified'), null, null, $booking->id, 'booking');
                }
            }
        });
    }

    protected function remove_service_from_booking($request): void
    {
        if (!$request->has('booking_id', 'service_id', 'variant_key', 'zone_id')) return;

        DB::transaction(function () use ($request) {
            $booking_details = BookingDetail::whereHas('booking', fn($query) => $query->where('id', $request['booking_id']))->where('variant_key', $request['variant_key'])->first();
            $service = Service::with('variations')->find($request['service_id']);
            $variation = $service->variations->where('variant_key', $request['variant_key'])->where('zone_id', $request['zone_id'])->first();
            $quantity = $booking_details['quantity'];
            $booking = Booking::with(['detail', 'details_amounts'])->find($request['booking_id']);

            //remove coupon(from existing booking)
            if ($booking->total_coupon_discount_amount > 0) {
                self::remove_coupon_from_booking($booking, $service);
            }

            // *** promotional discount calculation ***
            $basic_discount = basic_discount_calculation($service, $variation->price * $quantity);
            $campaign_discount = campaign_discount_calculation($service, $variation->price * $quantity);
            $subtotal = round($variation->price * $quantity, 2);

            $applicable_discount = ($campaign_discount >= $basic_discount) ? $campaign_discount : $basic_discount;
            $tax = round(((($variation->price * $quantity - $applicable_discount) * $service['tax']) / 100), 2);

            //between normal discount & campaign discount, greater one will be calculated
            $basic_discount = $basic_discount > $campaign_discount ? $basic_discount : 0;
            $campaign_discount = $campaign_discount >= $basic_discount ? $campaign_discount : 0;
            //*** end ***

            $removed_total = round($subtotal - $basic_discount - $campaign_discount + $tax, 2);

            //refund
            $refund_amount = 0;
            if ((($booking->payment_method != 'cash_after_service' && $booking->payment_method != 'offline_payment') || ($booking->payment_method == 'offline_payment' && $booking->is_paid)) && $booking->additional_charge == 0) {
                $refund_amount = $removed_total;
            }

            //bookings
            $booking = Booking::find($request['booking_id']);
            $booking->total_booking_amount -= $removed_total;
            $booking->total_tax_amount -= $tax;
            $booking->total_discount_amount -= $basic_discount;
            $booking->total_campaign_discount_amount -= $campaign_discount;

            $booking->additional_charge -= $removed_total;
            $booking->additional_tax_amount -= $tax;
            $booking->additional_discount_amount -= $basic_discount;
            $booking->additional_campaign_discount_amount -= $campaign_discount;
            $booking->save();

            //booking_details_amount
            BookingDetailsAmount::where('booking_id', $request['booking_id'])->where('booking_details_id', $booking_details->id)->delete();

            //booking_details
            $booking_details->delete();

            //transaction
            if ($refund_amount > 0) {
                remove_booking_service_transaction_for_digital_payment($booking, $refund_amount);
            }

            //provider notification
            if (isset($booking->provider_id)) {
                $fcm_token = Provider::with('owner')->whereId($booking->provider_id)->first()->owner->fcm_token ?? null;
                if (!is_null($fcm_token)) {
                    device_notification($fcm_token, translate('Service has been removed from the booking'), null, null, $booking->id, 'booking');
                }
            }
        });
    }

    protected function decrease_service_quantity_from_booking($request): void
    {
        if (!$request->has('booking_id', 'service_id', 'variant_key', 'zone_id')) return;

        DB::transaction(function () use ($request) {
            $booking_detail = BookingDetail::whereHas('booking', fn($query) => $query->where('id', $request['booking_id']))->where('variant_key', $request['variant_key'])->first();
            $service = Service::with('variations')->find($request['service_id']);
            $variation = $service->variations->where('variant_key', $request['variant_key'])->where('zone_id', $request['zone_id'])->first();
            $booking = Booking::with(['detail', 'details_amounts'])->find($request['booking_id']);

            $old_quantity = $request['old_quantity'];
            $new_quantity = $request['new_quantity'];
            $quantity_to_remove = $request['old_quantity'] - $request['new_quantity'];

            //remove coupon(from existing booking)
            if ($booking->total_coupon_discount_amount > 0) {
                self::remove_coupon_from_booking($booking, $service);
            }

            // *** promotional discount calculation ***
            $basic_discount = basic_discount_calculation($service, $variation->price * $old_quantity) - basic_discount_calculation($service, $variation->price * $new_quantity);
            $campaign_discount = campaign_discount_calculation($service, $variation->price * $old_quantity) - campaign_discount_calculation($service, $variation->price * $new_quantity);
            $subtotal = round($variation->price * $quantity_to_remove, 2);

            $applicable_discount = max($campaign_discount, $basic_discount);
            $tax = round(((($variation->price*$quantity_to_remove - $applicable_discount) * $service['tax']) / 100), 2);

            //between normal discount & campaign discount, greater one will be calculated
            $basic_discount = $basic_discount > $campaign_discount ? $basic_discount : 0;
            $campaign_discount = $campaign_discount >= $basic_discount ? $campaign_discount : 0;
            //*** end ***

            $sub_total = round($subtotal - $basic_discount - $campaign_discount + $tax, 2);

            $removed_total = $booking->total_booking_amount - $sub_total;

            //refund
            $refund_amount = 0;
            if ((($booking->payment_method != 'cash_after_service' && $booking->payment_method != 'offline_payment') || ($booking->payment_method == 'offline_payment' && $booking->is_paid)) && $booking->additional_charge == 0) {
                $refund_amount = $removed_total;
            }

            //bookings
            $booking = Booking::find($request['booking_id']);
            $booking->additional_charge -= $sub_total;
            $booking->additional_tax_amount -= $tax;
            $booking->additional_discount_amount -= $basic_discount;
            $booking->additional_campaign_discount_amount -= $campaign_discount;
            //'removed_coupon_amount' has been updated in function 'remove_coupon_from_booking'
            $booking->total_booking_amount -= $sub_total;
            $booking->total_tax_amount -= $tax;
            $booking->total_discount_amount -= $basic_discount;
            $booking->total_campaign_discount_amount -= $campaign_discount;
            $booking->save();


            // *** promotional discount calculation ***
            $basic_discount = basic_discount_calculation($service, $variation->price * $new_quantity);
            $campaign_discount = campaign_discount_calculation($service, $variation->price * $new_quantity);
            $subtotal = round($variation->price * $new_quantity, 2);

            $applicable_discount = ($campaign_discount >= $basic_discount) ? $campaign_discount : $basic_discount;
            $tax = round(((($variation->price*$new_quantity - $applicable_discount) * $service['tax']) / 100), 2);

            //between normal discount & campaign discount, greater one will be calculated
            $basic_discount = $basic_discount > $campaign_discount ? $basic_discount : 0;
            $campaign_discount = $campaign_discount >= $basic_discount ? $campaign_discount : 0;
            //*** end ***

            $sub_total = round($subtotal - $basic_discount - $campaign_discount + $tax, 2);

            //booking_detail
            $booking_detail->quantity = $new_quantity;
            $booking_detail->tax_amount = $tax;
            $booking_detail->total_cost = $sub_total;
            $booking_detail->discount_amount = $basic_discount;
            $booking_detail->campaign_discount_amount = $campaign_discount;
            $booking_detail->overall_coupon_discount_amount = 0;
            $booking_detail->save();

            //booking_details_amount
            $booking_details_amount = BookingDetailsAmount::where('booking_id', $request['booking_id'])->where('booking_details_id', $booking_detail->id)->first();
            $booking_details_amount->service_quantity = $new_quantity;
            $booking_details_amount->service_tax = $tax;
            $booking_details_amount->coupon_discount_by_admin = 0;
            $booking_details_amount->coupon_discount_by_provider = 0;
            $booking_details_amount->discount_by_admin = $this->calculate_discount_cost($booking_detail->discount_amount)['admin'];
            $booking_details_amount->discount_by_provider = $this->calculate_discount_cost($booking_detail->discount_amount)['provider'];
            $booking_details_amount->campaign_discount_by_admin = $this->calculate_campaign_cost($booking_detail->campaign_discount_amount)['admin'];
            $booking_details_amount->campaign_discount_by_provider = $this->calculate_campaign_cost($booking_detail->campaign_discount_amount)['provider'];
            $booking_details_amount->save();

            //transaction
            if ($refund_amount > 0) {
                remove_booking_service_transaction_for_digital_payment($booking, $removed_total);
            }

            //provider notification
            if (isset($booking->provider_id)) {
                $fcm_token = Provider::with('owner')->whereId($booking->provider_id)->first()->owner->fcm_token ?? null;
                if (!is_null($fcm_token)) {
                    device_notification($fcm_token, translate('Service has been removed from the booking'), null, null, $booking->id, 'booking');
                }
            }
        });
    }

    /**
     * @param $booking
     * @param $service
     * @return void
     */
    public function remove_coupon_from_booking($booking, $service): void
    {
        DB::transaction(function () use ($booking, $service) {
            $total_removed_coupon_amount = 0;
            $total_tax_amount = 0;
            $total_booking_amount = 0;

            foreach ($booking->detail as $detail) {
                $total_removed_coupon_amount += $detail['overall_coupon_discount_amount'];

                $service_cost = $detail['service_cost'];
                $basic_discount = $detail['discount_amount'];
                $campaign_discount = $detail['campaign_discount_amount'];
                $quantity = $detail['quantity'];

                $applicable_discount = max($campaign_discount, $basic_discount);
                $tax_percentage = $service['tax'];
                $tax = round(((($service_cost * $quantity - $applicable_discount) * $tax_percentage) / 100), 2);

                //update details
                $detail->tax_amount = $tax;
                $detail->total_cost = round(($service_cost * $quantity) - $applicable_discount + $tax, 2);
                $detail->overall_coupon_discount_amount = 0;
                $detail->save();

                $total_tax_amount += $tax;
                $total_booking_amount += $detail->total_cost;
            }

            foreach ($booking->details_amounts as $details_amount) {
                $details_amount->coupon_discount_by_admin = 0;
                $details_amount->coupon_discount_by_provider = 0;
                $details_amount->save();
            }

            $booking->total_booking_amount = $total_booking_amount;
            $booking->total_tax_amount = $total_tax_amount;
            $booking->total_coupon_discount_amount = 0;
            $booking->coupon_code = null;
            $booking->additional_charge += $total_removed_coupon_amount;
            $booking->removed_coupon_amount += $total_removed_coupon_amount;
            $booking->save();
        });
    }


    //=============== PROMOTIONAL COST CALCULATION ===============

    /**
     * @param float $discount_amount
     * @return array
     */
    private function calculate_discount_cost(float $discount_amount): array
    {
        $data = BusinessSettings::where('settings_type', 'promotional_setup')->where('key_name', 'discount_cost_bearer')->first();
        if (!isset($data)) return [];
        $data = $data->live_values;

        if ($data['admin_percentage'] == 0) {
            $admin_percentage = 0;
        } else {
            $admin_percentage = ($discount_amount * $data['admin_percentage']) / 100;
        }

        if ($data['provider_percentage'] == 0) {
            $provider_percentage = 0;
        } else {
            $provider_percentage = ($discount_amount * $data['provider_percentage']) / 100;
        }
        return [
            'admin' => $admin_percentage,
            'provider' => $provider_percentage
        ];
    }

    /**
     * @param float $campaign_amount
     * @return array
     */
    private function calculate_campaign_cost(float $campaign_amount): array
    {
        $data = BusinessSettings::where('settings_type', 'promotional_setup')->where('key_name', 'campaign_cost_bearer')->first();
        if (!isset($data)) return [];
        $data = $data->live_values;

        if ($data['admin_percentage'] == 0) {
            $admin_percentage = 0;
        } else {
            $admin_percentage = ($campaign_amount * $data['admin_percentage']) / 100;
        }

        if ($data['provider_percentage'] == 0) {
            $provider_percentage = 0;
        } else {
            $provider_percentage = ($campaign_amount * $data['provider_percentage']) / 100;
        }

        return [
            'admin' => $admin_percentage,
            'provider' => $provider_percentage
        ];
    }

    /**
     * @param float $coupon_amount
     * @return array
     */
    private function calculate_coupon_cost(float $coupon_amount): array
    {
        $data = BusinessSettings::where('settings_type', 'promotional_setup')->where('key_name', 'coupon_cost_bearer')->first();
        if (!isset($data)) return [];
        $data = $data->live_values;

        if ($data['admin_percentage'] == 0) {
            $admin_percentage = 0;
        } else {
            $admin_percentage = ($coupon_amount * $data['admin_percentage']) / 100;
        }

        if ($data['provider_percentage'] == 0) {
            $provider_percentage = 0;
        } else {
            $provider_percentage = ($coupon_amount * $data['provider_percentage']) / 100;
        }

        return [
            'admin' => $admin_percentage,
            'provider' => $provider_percentage
        ];
    }

    /**
     * @param $booking_id
     * @param float $booking_amount
     * @param $provider_id
     * @return void
     */
    private function update_admin_commission($booking, float $booking_amount, $provider_id)
    {
        $service_cost = $booking['total_booking_amount'] - $booking['total_tax_amount'] + $booking['total_discount_amount'] + $booking['total_campaign_discount_amount'] + $booking['total_coupon_discount_amount'];

        //cost bearing (promotional)
        $booking_details_amounts = BookingDetailsAmount::where('booking_id', $booking->id)->get();
        $promotional_cost_by_admin = 0;
        $promotional_cost_by_provider = 0;
        foreach ($booking_details_amounts as $booking_details_amount) {
            $promotional_cost_by_admin += $booking_details_amount['discount_by_admin'] + $booking_details_amount['coupon_discount_by_admin'] + $booking_details_amount['campaign_discount_by_admin'];
            $promotional_cost_by_provider += $booking_details_amount['discount_by_provider'] + $booking_details_amount['coupon_discount_by_provider'] + $booking_details_amount['campaign_discount_by_provider'];
        }

        //total booking amount (for provider)
        $provider_receivable_total_booking_amount = $service_cost - $promotional_cost_by_provider;

        //admin commission
        $provider = Provider::find($booking['provider_id']);
        $commission_percentage = $provider->commission_status == 1 ? $provider->commission_percentage : (business_config('default_commission', 'business_information'))->live_values;
        $admin_commission = ($provider_receivable_total_booking_amount * $commission_percentage) / 100;

        //admin promotional cost will be deducted from admin commission
        $admin_commission_without_cost = $admin_commission - $promotional_cost_by_admin;

        //total booking amount (without commission)
        $booking_amount_without_commission = $booking['total_booking_amount'] - $admin_commission_without_cost;

        $booking_amount_detail_amount = BookingDetailsAmount::where('booking_id', $booking->id)->first();
        $booking_amount_detail_amount->admin_commission = $admin_commission;
        $booking_amount_detail_amount->provider_earning = $booking_amount_without_commission;
        $booking_amount_detail_amount->save();
    }



    //=============== REFERRAL EARN & LOYALTY POINT ===============

    /**
     * @param $user_id
     * @return false|void
     */
    private function referral_earning_calculation($user_id)
    {
        $is_first_booking = Booking::where('customer_id', $user_id)->count('id');
        if ($is_first_booking > 1) return false;

        $referred_by_user = User::find($user_id)->referred_by_user ?? null;
        if (is_null($referred_by_user)) return false;

        $customer_referral_earning = business_config('customer_referral_earning', 'customer_config')->live_values ?? 0;
        $amount = business_config('referral_value_per_currency_unit', 'customer_config')->live_values ?? 0;

        if ($customer_referral_earning == 1) referral_earning_transaction_after_booking_complete($referred_by_user, $amount);
    }

    /**
     * @param $user_id
     * @param $booking_amount
     * @return false|void
     */
    private function loyalty_point_calculation($user_id, $booking_amount)
    {
        //active status check (if ON)
        $customer_loyalty_point = business_config('customer_loyalty_point', 'customer_config');
        if (isset($customer_loyalty_point) && $customer_loyalty_point->live_values != '1') return false;

        //point amount (against booking_amount)
        $percentage_per_booking = business_config('loyalty_point_percentage_per_booking', 'customer_config');
        $point_amount = ($percentage_per_booking->live_values * $booking_amount) / 100;

        //point rate (per currency unit)
        $point_per_currency_unit = business_config('loyalty_point_value_per_currency_unit', 'customer_config');

        //final point
        $point = $point_per_currency_unit->live_values * $point_amount;

        loyalty_point_transaction($user_id, $point);
    }


}
