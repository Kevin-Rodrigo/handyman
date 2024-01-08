<?php

namespace Modules\ServiceManagement\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\BookingModule\Entities\Booking;
use Modules\ServiceManagement\Entities\Service;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    private $service, $booking;

    public function __construct(Service $service, Booking $booking)
    {
        $this->service = $service;
        $this->booking = $booking;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:active,inactive,all',
            'zone_id' => 'uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $services = $this->service->with(['category.zonesBasicInfo'])->latest()
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%');
                }
            })
            ->when($request->has('category_id'), function ($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })->when($request->has('sub_category_id'), function ($query) use ($request) {
                return $query->where('sub_category_id', $request->sub_category_id);
            })->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                if ($request['status'] == 'active') {
                    return $query->where(['is_active' => 1]);
                } else {
                    return $query->where(['is_active' => 0]);
                }
            })->when($request->has('zone_id'), function ($query) use ($request) {
                return $query->whereHas('category.zonesBasicInfo', function ($queryZone) use ($request) {
                    $queryZone->where('zone_id', $request['zone_id']);
                });
            })->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $services), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request['variations'] = ($request->has('variations')) ? json_decode($request['variations']) : '';

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required|uuid',
            'sub_category_id' => 'required|uuid',
            'cover_image' => 'required',
            'description' => 'required',
            'short_description' => 'required',
            'thumbnail' => 'required',
            'tax' => 'required|numeric|min:0',
            'variations' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $service = $this->service;
        $service->name = $request->name;
        $service->category_id = $request->category_id;
        $service->sub_category_id = $request->sub_category_id;
        $service->short_description = $request->short_description;
        $service->description = $request->description;
        $service->cover_image = file_uploader('service/', 'png', $request->file('cover_image'));
        $service->thumbnail = file_uploader('service/', 'png', $request->file('thumbnail'));
        $service->tax = $request->tax;
        $service->save();

        $variation_format = [];
        foreach ($request['variations'] as $variant) {
            foreach ($variant->zoneWiseVariations as $zone_wise_info) {
                $variation_format[] = [
                    'variant' => $variant->variationName,
                    'variant_key' => Str::slug($variant->variationName),
                    'zone_id' => $zone_wise_info->id,
                    'price' => $zone_wise_info->price,
                    'service_id' => $service->id
                ];
            }
        }

        $service->variations()->createMany($variation_format);

        return response()->json(response_formatter(SERVICE_STORE_200), 200);
    }

    /**
     * Show the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $service = $this->service->where('id', $id)->with(['category.children', 'variations'])->withCount(['bookings'])->first();
        $ongoing = $this->booking->whereHas('detail', function ($query) use ($id) {
            $query->where('service_id', $id);
        })->where(['booking_status' => 'ongoing'])->count();
        $canceled = $this->booking->whereHas('detail', function ($query) use ($id) {
            $query->where('service_id', $id);
        })->where(['booking_status' => 'canceled'])->count();

        if (isset($service)) {
            $service = self::variations_react_format($service);
            $service['ongoing_count'] = $ongoing;
            $service['cancelled_count'] = $canceled;
            return response()->json(response_formatter(DEFAULT_200, $service), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function edit(string $id): JsonResponse
    {
        $service = $this->service->where('id', $id)->with(['category.children', 'category.zones', 'variations'])->first();
        if (isset($service)) {
            $service = self::variations_react_format($service);
            return response()->json(response_formatter(DEFAULT_200, $service), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    private function variations_react_format($service)
    {
        $variants = collect($service['variations'])->pluck('variant_key')->unique();
        $storage = [];
        foreach ($variants as $variant) {
            $formatting = [];
            $filtered = $service['variations']->where('variant_key', $variant);
            $formatting['variationName'] = $variant;
            $formatting['variationPrice'] = $filtered->first()->price;
            foreach ($filtered as $single_variant) {
                $formatting['zoneWiseVariations'][] = [
                    'id' => $single_variant['zone_id'],
                    'price' => $single_variant['price']
                ];
            }
            $storage[] = $formatting;
        }
        $service['variations_react_format'] = $storage;
        return $service;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request['variations'] = ($request->has('variations')) ? json_decode($request['variations']) : '';

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required|uuid',
            'sub_category_id' => 'required|uuid',
            'description' => 'required',
            'tax' => 'required|numeric|min:0',
            'variations' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $service = $this->service->find($id);
        if (!isset($service)) {
            return response()->json(response_formatter(DEFAULT_204), 200);
        }
        $service->name = $request->name;
        $service->category_id = $request->category_id;
        $service->sub_category_id = $request->sub_category_id;
        $service->short_description = $request->short_description;
        $service->description = $request->description;

        if ($request->has('cover_image')) {
            $service->cover_image = file_uploader('service/', 'png', $request->file('cover_image'));
        }

        if ($request->has('thumbnail')) {
            $service->thumbnail = file_uploader('service/', 'png', $request->file('thumbnail'));
        }

        $service->tax = $request->tax;
        $service->save();

        $service->variations()->delete();

        $variation_format = [];
        foreach ($request['variations'] as $variant) {
            foreach ($variant->zoneWiseVariations as $zone_wise_info) {
                $variation_format[] = [
                    'variant' => $variant->variationName,
                    'variant_key' => Str::slug($variant->variationName),
                    'zone_id' => $zone_wise_info->id,
                    'price' => $zone_wise_info->price,
                    'service_id' => $service->id
                ];
            }
        }
        $service->variations()->createMany($variation_format);

        return response()->json(response_formatter(DEFAULT_STATUS_UPDATE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'service_ids' => 'required|array'
        ]);
        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }
        $services = $this->service->whereIn('id', $request['service_ids'])->get();
        if (isset($services)) {
            foreach ($services as $service) {
                foreach (['thumbnail','cover_image'] as $item){
                    file_remover('service/', $service[$item]);
                }
                $service->variations()->delete();
                $service->delete();
            }
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
            'service_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->service->whereIn('id', $request['service_ids'])->update(['is_active' => $request['status']]);

        return response()->json(response_formatter(DEFAULT_STATUS_UPDATE_200), 200);
    }
}
