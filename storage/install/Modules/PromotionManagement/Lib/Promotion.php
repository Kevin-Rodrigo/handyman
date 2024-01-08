<?php

if (!function_exists('device_notification')) {
    function device_notification($fcm_token, $title, $description, $image, $booking_id, $type='status', $channel_id = null, $user_id = null): bool|string
    {
        $config = business_config('push_notification', 'third_party');
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array("authorization: key=" . $config->live_values['server_key'],
            "content-type: application/json"
        );

        $image = asset('storage/app/public/push-notification') . '/' . $image;

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "notification" : {
                    "title":"' . $title . '",
                    "body" : "' . $description . '",
                    "booking_id": "' . $booking_id . '",
                    "channel_id": "' . $channel_id . '",
                    "user_id": "' . $user_id . '",
                    "type": "' . $type . '",
                    "title_loc_key": "' . $booking_id . '",
                    "body_loc_key": "status",
                    "image": "' . $image . '",
                    "sound": "notification.wav",
                    "android_channel_id": "ondemand"
                },
                "data": {
                    "title":"' . $title . '",
                    "body" : "' . $description . '",
                    "booking_id": "' . $booking_id . '",
                    "channel_id": "' . $channel_id . '",
                    "user_id": "' . $user_id . '",
                    "type": "' . $type . '",
                    "image": "' . $image . '",
                }
             }';

        return send_curl_request($url, $postdata, $header);
    }
}

if (!function_exists('topic_notification')) {
    function topic_notification($topic, $title, $description, $image, $booking_id, $type='status'): bool|string
    {
        $config = business_config('push_notification', 'third_party');

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = ["authorization: key=" . $config->live_values['server_key'],
            "content-type: application/json",
        ];

        $image = asset('storage/app/public/push-notification') . '/' . $image;
        $topic_str = "/topics/" . $topic;

        $postdata = '{
             "to":"' . $topic_str . '",
             "notification" : {
                    "title":"' . $title . '",
                    "body" : "' . $description . '",
                    "booking_id": "' . $booking_id . '",
                    "type": "' . $type . '",
                    "title_loc_key": "' . $booking_id . '",
                    "body_loc_key": "status",
                    "image": "' . $image . '",
                    "sound": "notification.wav",
                    "android_channel_id": "ondemand"
                },
                "data": {
                    "title":"' . $title . '",
                    "body" : "' . $description . '",
                    "booking_id": "' . $booking_id . '",
                    "type": "' . $type . '",
                    "image": "' . $image . '",
                }
              }';

        return send_curl_request($url, $postdata, $header);
    }
}

//bidding notification
if (!function_exists('device_notification_for_bidding')) {
    function device_notification_for_bidding($fcm_token, $title, $description, $image, $type='bidding', $booking_id = null, $post_id = null, $provider_id = null): bool|string
    {
        $config = business_config('push_notification', 'third_party');
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array("authorization: key=" . $config->live_values['server_key'],
            "content-type: application/json"
        );

        $image = asset('storage/app/public/push-notification') . '/' . $image;

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "notification" : {
                    "title":"' . $title . '",
                    "body" : "' . $description . '",
                    "booking_id": "' . $booking_id . '",
                    "post_id": "' . $post_id . '",
                    "provider_id": "' . $provider_id . '",
                    "type": "' . $type . '",
                    "title_loc_key": "' . $booking_id . '",
                    "body_loc_key": "status",
                    "image": "' . $image . '",
                    "sound": "notification.wav",
                    "android_channel_id": "ondemand"
                },
                "data": {
                    "title":"' . $title . '",
                    "body" : "' . $description . '",
                    "booking_id": "' . $booking_id . '",
                    "post_id": "' . $post_id . '",
                    "provider_id": "' . $provider_id . '",
                    "type": "' . $type . '",
                    "image": "' . $image . '",
                }
             }';

        return send_curl_request($url, $postdata, $header);
    }
}

//chatting notification

if (!function_exists('device_notification_for_chatting')) {
    function device_notification_for_chatting($fcm_token, $title, $description, $image, $channel_id, $user_name, $user_image, $user_phone, $user_type, $type = 'status'): bool|string
    {
        $config = business_config('push_notification', 'third_party');
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array("authorization: key=" . $config->live_values['server_key'],
            "content-type: application/json"
        );

        $image = asset('storage/app/public/push-notification') . '/' . $image;

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "notification" : {
                    "title":"' . $title . '",
                    "body" : "' . $description . '",
                    "image": "' . $image . '",
                    "type": "' . $type . '",
                    "channel_id": "' . $channel_id . '",
                    "user_name": "' . $user_name . '",
                    "user_image": "' . $user_image . '",
                    "user_phone": "' . $user_phone . '",
                    "user_type": "' . $user_type . '",
                    "title_loc_key": "",
                    "body_loc_key": "status",
                    "sound": "notification.wav",
                    "android_channel_id": "ondemand"
                },
                "data": {
                    "title":"' . $title . '",
                    "body" : "' . $description . '",
                    "image": "' . $image . '",
                    "type": "' . $type . '",
                    "channel_id": "' . $channel_id . '",
                    "user_name": "' . $user_name . '",
                    "user_image": "' . $user_image . '",
                    "user_phone": "' . $user_phone . '",
                    "user_type": "' . $user_type . '",
                }
             }';

        return send_curl_request($url, $postdata, $header);
    }
}
if (!function_exists('basic_discount_calculation')) {
    function basic_discount_calculation($service, $total_purchase_amount): float
    {
        $keeper = null;
        if ($service->service_discount->count() > 0) {
            $keeper = $service->service_discount[0]->discount;
        } elseif ($service->category->category_discount->count() > 0) {
            $keeper = $service->category->category_discount[0]->discount;
        }

        return booking_discount_calculator($keeper, $total_purchase_amount);
    }
}

if (!function_exists('campaign_discount_calculation')) {
    function campaign_discount_calculation($service, $total_purchase_amount): float
    {
        $keeper = null;
        if ($service->campaign_discount->count() > 0) {
            $keeper = $service->campaign_discount[0]->discount;
        }elseif($service->category->campaign_discount->count() > 0){
            $keeper = $service->category->campaign_discount[0]->discount;
        }

        return booking_discount_calculator($keeper, $total_purchase_amount);
    }
}

/**
 * @param string $url
 * @param string $postdata
 * @param array $header
 * @return bool|string
 */
function send_curl_request(string $url, string $postdata, array $header): string|bool
{
    $ch = curl_init();
    $timeout = 120;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    // Get URL content
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

/**
 * @param mixed $keeper
 * @param $total_purchase_amount
 * @return mixed
 */
function booking_discount_calculator(mixed $keeper, $total_purchase_amount): float
{
    $amount = 0;

    if ($keeper != null && $total_purchase_amount >= $keeper->min_purchase) {
        if ($keeper->discount_amount_type == 'percent') {
            $amount = ($total_purchase_amount / 100) * $keeper->discount_amount;

            if ($amount > $keeper->max_discount_amount) {
                $amount = $keeper->max_discount_amount;
            }

        } else {
            $amount = $keeper->discount_amount;
        }
    }

    return $amount;
}
