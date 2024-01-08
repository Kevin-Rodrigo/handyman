<?php

namespace Modules\PromotionManagement\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\PromotionManagement\Entities\Campaign;
use Modules\PromotionManagement\Entities\Discount;
use Modules\PromotionManagement\Entities\DiscountType;

class CampaignController extends Controller
{
    protected $discount, $campaign, $discountType;

    public function __construct(Campaign $campaign, Discount $discount, DiscountType $discountType)
    {
        $this->discount = $discount;
        $this->campaign = $campaign;
        $this->discountType = $discountType;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:active,inactive,all',
            'string' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $campaigns = $this->campaign->with(['discount', 'discount.category_types', 'discount.service_types', 'discount.zone_types'])
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('campaign_name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $campaigns), 200);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request = $this->getRequest($request);

        $validator = Validator::make($request->all(), [
            'campaign_name' => 'required',
            'cover_image' => 'required|image|mimes:jpeg,jpg,png,gif|required|max:10000',
            'thumbnail' => 'required|image|mimes:jpeg,jpg,png,gif|required|max:10000',
            'discount_type' => 'required',
            'discount_title' => 'required',
            'discount_amount' => 'required|numeric',
            'discount_amount_type' => 'required|in:percent,amount',
            'min_purchase' => 'required|numeric',
            'max_discount_amount' => $request['discount_amount_type'] == 'amount' ? '' : 'required' . '|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'limit_per_user' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        DB::transaction(function () use ($request) {
            $discount = $this->discount;
            $discount->discount_type = $request['discount_type'];
            $discount->discount_title = $request['discount_title'];
            $discount->discount_amount = $request['discount_amount'];
            $discount->discount_amount_type = $request['discount_amount_type'];
            $discount->min_purchase = $request['min_purchase'];
            $discount->max_discount_amount = !is_null($request['max_discount_amount']) ? $request['max_discount_amount'] : 0;
            $discount->limit_per_user = $request['limit_per_user'];
            $discount->promotion_type = 'campaign';
            $discount->start_date = $request['start_date'];
            $discount->end_date = $request['end_date'];
            $discount->is_active = 1;
            $discount->save();

            $campaign = $this->campaign;
            $campaign->thumbnail = file_uploader('campaign/', 'png', $request->file('thumbnail'));
            $campaign->cover_image = file_uploader('campaign/', 'png', $request->file('cover_image'));
            $campaign->campaign_name = $request['campaign_name'];
            $campaign->discount_id = $discount['id'];
            $campaign->is_active = 1;
            $campaign->save();

            $dis_types = ['category', 'service', 'zone'];
            foreach ((array)$dis_types as $dis_type) {
                $types = [];
                foreach ((array)$request[$dis_type . '_ids'] as $id) {
                    $types[] = [
                        'discount_id' => $discount['id'],
                        'discount_type' => $dis_type,
                        'type_wise_id' => $id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                $discount->discount_types()->createMany($types);
            }
        });

        return response()->json(response_formatter(DISCOUNT_CREATE_200), 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function edit(string $id): JsonResponse
    {
        $campaign = $this->campaign->with(['discount', 'discount.category_types', 'discount.service_types', 'discount.zone_types'])->where('id', $id)->first();
        if (isset($campaign)) {
            return response()->json(response_formatter(DEFAULT_200, $campaign), 200);
        }
        return response()->json(response_formatter(DEFAULT_204, $campaign), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request = $this->getRequest($request);

        $validator = Validator::make($request->all(), [
            'campaign_name' => 'required',
            'cover_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'thumbnail' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'discount_type' => 'required',
            'discount_title' => 'required',
            'discount_amount' => 'required|numeric',
            'discount_amount_type' => 'required|in:percent,amount',
            'min_purchase' => 'required|numeric',
            'max_discount_amount' => $request['discount_amount_type'] == 'amount' ? '' : 'required' . '|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'limit_per_user' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        DB::transaction(function () use ($request, $id) {
            $campaign = $this->campaign->where(['id' => $id])->first();
            if ($request->has('thumbnail')) {
                $campaign->thumbnail = file_uploader('campaign/', 'png', $request->file('thumbnail'), $campaign->thumbnail);
            }
            if ($request->has('cover_image')) {
                $campaign->cover_image = file_uploader('campaign/', 'png', $request->file('cover_image'), $campaign->cover_image);
            }
            $campaign->campaign_name = $request['campaign_name'];
            $campaign->save();

            $discount = $this->discount->find($campaign['discount_id']);
            $discount->discount_type = $request['discount_type'];
            $discount->discount_title = $request['discount_title'];
            $discount->discount_amount = $request['discount_amount'];
            $discount->discount_amount_type = $request['discount_amount_type'];
            $discount->min_purchase = $request['min_purchase'];
            $discount->max_discount_amount = !is_null($request['max_discount_amount']) ? $request['max_discount_amount'] : 0;
            $discount->limit_per_user = $request['limit_per_user'];
            $discount->start_date = $request['start_date'];
            $discount->end_date = $request['end_date'];
            $discount->is_active = 1;
            $discount->save();

            $discount->discount_types()->delete();

            $dis_types = ['category', 'service', 'zone'];
            foreach ((array)$dis_types as $dis_type) {
                $types = [];
                foreach ((array)$request[$dis_type . '_ids'] as $id) {
                    $types[] = [
                        'discount_id' => $discount['id'],
                        'discount_type' => $dis_type,
                        'type_wise_id' => $id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                $discount->discount_types()->createMany($types);
            }
        });

        return response()->json(response_formatter(CAMPAIGN_UPDATE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'campaign_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $campaigns = $this->campaign->whereIn('id', $request['campaign_ids']);
        if ($campaigns->count() > 0) {
            foreach ($campaigns->get() as $campaign) {
                file_remover('campaign/', $campaign['thumbnail']);
                file_remover('campaign/', $campaign['cover_image']);
                $this->discount->where('id', $campaign['discount_id'])->delete();
                $this->discountType->where('discount_id', $campaign['discount_id'])->delete();
            }
            $campaigns->delete();
            return response()->json(response_formatter(DEFAULT_DELETE_200), 200);
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
            'campaign_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->campaign->whereIn('id', $request['campaign_ids'])->update(['is_active' => $request['status']]);
        $discount_ids = $this->campaign->whereIn('id', $request['campaign_ids'])->pluck('discount_id')->toArray();
        $this->discount->whereIn('id', $discount_ids)->update(['is_active' => $request['status']]);

        return response()->json(response_formatter(DEFAULT_STATUS_UPDATE_200), 200);
    }

    /**
     * @param Request $request
     * @return Request
     */
    public function getRequest(Request $request): Request
    {
        if (!is_array($request['zone_ids'])) {
            $request['zone_ids'] = $request->has('zone_ids') ? json_decode($request['zone_ids']) : '';
        }

        if (!is_array($request['category_ids'])) {
            $request['category_ids'] = $request->has('category_ids') ? json_decode($request['category_ids']) : '';
        }

        if (!is_array($request['service_ids'])) {
            $request['service_ids'] = $request->has('service_ids') ? json_decode($request['service_ids']) : '';
        }

        return $request;
    }
}
