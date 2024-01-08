<?php

namespace Modules\PromotionManagement\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\PromotionManagement\Entities\Discount;

class DiscountController extends Controller
{

    protected $discount;

    public function __construct(Discount $discount)
    {
        $this->discountQuery = $discount->ofPromotionTypes('discount');
        $this->discount = $discount;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'string' => 'string',
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:active,inactive,all',
            'discount_type' => 'required|in:category,service,zone,mixed,all',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $discounts = $this->discountQuery->with(['category_types', 'service_types', 'zone_types'])
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('discount_title', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request->has('discount_type') && $request['discount_type'] != 'all', function ($query) use ($request) {
                return $query->where(['discount_type' => $request['discount_type']]);
            })->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->orderBy('created_at', 'desc')->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $discounts), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'discount_type' => 'required|in:category,service,zone,mixed',
            'discount_amount' => 'required|numeric',
            'discount_title' => 'required|string',
            'discount_amount_type' => 'required|in:percent,amount',
            'min_purchase' => 'required|numeric|min:0',
            'max_discount_amount' => $request['discount_amount_type'] == 'amount' ? '' : 'required' . '|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'category_ids' => 'array',
            'service_ids' => 'array',
            'zone_ids' => 'array',
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
            $discount->promotion_type = 'discount';
            $discount->start_date = $request['start_date'];
            $discount->end_date = $request['end_date'];
            $discount->is_active = 1;
            $discount->save();

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
        $discount = $this->discountQuery->with(['category_types', 'service_types', 'zone_types'])->where('id', $id)->first();
        if (isset($discount)) {
            return response()->json(response_formatter(DEFAULT_200, $discount), 200);
        }
        return response()->json(response_formatter(DEFAULT_204, $discount), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'discount_type' => 'required|in:category,service,zone,mixed',
            'discount_amount' => 'required|numeric',
            'discount_title' => 'required|string',
            'discount_amount_type' => 'required|in:percent,amount',
            'min_purchase' => 'required|numeric',
            'max_discount_amount' => $request['discount_amount_type'] == 'amount' ? '' : 'required' . '|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'category_ids' => 'array',
            'service_ids' => 'array',
            'zone_ids' => 'array',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $discount = $this->discountQuery->where(['id' => $id])->first();
        if (isset($discount)) {
            DB::transaction(function () use ($request, $id, $discount) {

                $discount->discount_type = $request['discount_type'];
                $discount->discount_title = $request['discount_title'];
                $discount->discount_amount = $request['discount_amount'];
                $discount->discount_amount_type = $request['discount_amount_type'];
                $discount->min_purchase = $request['min_purchase'];
                $discount->max_discount_amount = !is_null($request['max_discount_amount']) ? $request['max_discount_amount'] : 0;
                $discount->promotion_type = 'discount';
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
        } else {
            return response()->json(response_formatter(DEFAULT_404), 404);
        }

        return response()->json(response_formatter(DISCOUNT_UPDATE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'discount_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $discounts = $this->discountQuery->whereIn('id', $request['discount_ids']);

        if ($discounts->count() > 0) {
            $discounts->chunk(10, function ($queries) {
                foreach ($queries as $query) {
                    $query->discount_types()->delete();
                }
            });
            $discounts->delete();
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
            'discount_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->discountQuery->whereIn('id', $request['discount_ids'])->update(['is_active' => $request['status']]);

        return response()->json(response_formatter(DEFAULT_STATUS_UPDATE_200), 200);
    }
}
