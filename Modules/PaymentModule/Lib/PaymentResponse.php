<?php

namespace Modules\PaymentModule\Lib;

use Modules\BidModule\Entities\PostBid;
use Modules\BidModule\Http\Controllers\APi\V1\Customer\PostBidController;
use Modules\BookingModule\Http\Traits\BookingTrait;
use Modules\PaymentModule\Entities\PaymentRequest;
use Modules\UserManagement\Entities\User;

class PaymentResponse
{
    use BookingTrait;

    /**
     * @param $data
     * @return array
     */
    public static function success($data): array
    {
        $customer_user_id = $data['payer_id'];
        $tran_id = $data['transaction_id'];
        $payment_request_id = $data->id;

        $additional_data = json_decode($data['additional_data'], true);
        $request = collect([
            'access_token' => $additional_data['access_token'] ?? null,
            'zone_id' => $additional_data['zone_id'] ?? null,
            'service_schedule' => $additional_data['service_schedule'] ?? null,
            'service_address_id' => $additional_data['service_address_id'] ?? null,
            'service_address' => $additional_data['service_address'] ?? null,
            'payment_method' => $additional_data['payment_method'] ?? null,
            'callback' => $additional_data['callback'] ?? null,
            'is_partial' => $additional_data['is_partial'] ?? null,
            'post_id' => $additional_data['post_id'] ?? null,
            'provider_id' => $additional_data['provider_id'] ?? null,
        ]);

        if (!$request->has('post_id') || is_null($request['post_id'])) {
            $is_guest = !User::where('id', $customer_user_id)->exists();
            $response = (new PaymentResponse)->place_booking_request($customer_user_id, $request, $tran_id, $is_guest);

        } else {
            //for bidding
            $post_bid = PostBid::with(['post'])
                ->where('post_id', $request['post_id'])
                ->where('provider_id', $request['provider_id'])
                ->first();

            $data = [
                'payment_method' => $request['payment_method'],
                'zone_id' => $request['zone_id'],
                'service_tax' => $post_bid?->post?->service?->tax,
                'provider_id' => $post_bid?->provider_id,
                'price' => $post_bid?->offered_price,
                'service_schedule' => !is_null($request['service_schedule']) ? $request['service_schedule'] : $post_bid->post->booking_schedule,
                'service_id' => $post_bid->post->service_id,
                'category_id' => $post_bid->post->category_id,
                'sub_category_id' => $post_bid->post->category_id,
                'service_address_id' => !is_null($request['service_address_id']) ? $request['service_address_id'] : $post_bid->post->service_address_id,
                'is_partial' => $request['is_partial']
            ];

            $response = (new PaymentResponse)->place_booking_request_for_bidding(base64_decode($request['access_token']), $request, $tran_id, $data);
            if ($response['flag'] == 'success') {
                PostBidController::accept_post_bid_offer($post_bid->id, $response['booking_id']);
            }
        }

        //update payment request
        if ($response['flag'] == 'success' && $response['readable_id']) {
            $payment_request = PaymentRequest::find($payment_request_id);
            $payment_request->attribute = 'booking';
            $payment_request->attribute_id = $response['readable_id'];
            $payment_request->save();
        }

        $response['callback'] = $request['callback'];
        return $response;
    }

}
