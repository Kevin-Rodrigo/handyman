<?php

namespace Modules\BidModule\Http\Controllers\APi\V1\Provider;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\BidModule\Entities\Post;
use Modules\BidModule\Entities\PostBid;
use Modules\BookingModule\Http\Traits\BookingTrait;
use function response;
use function response_formatter;

class PostBidController extends Controller
{
    use BookingTrait;

    public function __construct(
        private PostBid $post_bid,
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'post_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $bid_offers_visibility_for_providers = (business_config('bid_offers_visibility_for_providers', 'bidding_system'))->live_values ?? 0;

        if (!$bid_offers_visibility_for_providers) {
            return response()->json(response_formatter(DEFAULT_403, null), 403);
        }

        $post_bids = $this->post_bid
            ->with(['provider.owner.reviews'])
            ->where('post_id', $request['post_id'])
            ->where('provider_id', '!=', $request->user()->id)
            ->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])
            ->withPath('');

        if ($post_bids->count() < 1) {
            return response()->json(response_formatter(DEFAULT_404, null), 404);
        }

        return response()->json(response_formatter(DEFAULT_200, $post_bids), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|uuid',
            'offered_price' => 'required',
            'provider_note' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        if ($this->post_bid->where('post_id', $request['post_id'])->where('provider_id', $request->user()->provider->id)->first()) {
            return response()->json(response_formatter(DEFAULT_FAIL_200, null), 200);
        }

        $post_bid = $this->post_bid;
        $post_bid->offered_price = $request['offered_price'];
        $post_bid->provider_note = $request['provider_note'];
        $post_bid->status = 'pending';
        $post_bid->post_id = $request['post_id'];
        $post_bid->provider_id = $request->user()->provider->id;
        $post_bid->save();

        //notification to customer
        $customer = Post::with(['customer'])->find($request['post_id'])?->customer;
        if ($customer) {
            device_notification_for_bidding($customer->fcm_token, translate('One provider bid offer for your requested service'), null, null, 'bidding', null, $post_bid->post_id, $request->user()->provider->id);
        }

        return response()->json(response_formatter(DEFAULT_STORE_200, null), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function withdraw(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $post_bids = $this->post_bid
            ->where('status', 'pending')
            ->where('post_id', $request['post_id'])
            ->where('provider_id', $request->user()->provider->id);

        if ($post_bids->count() < 1) {
            return response()->json(response_formatter(DEFAULT_404, null), 404);
        }

        $post_bids->delete();
        return response()->json(response_formatter(DEFAULT_DELETE_200, null), 200);
    }
}
