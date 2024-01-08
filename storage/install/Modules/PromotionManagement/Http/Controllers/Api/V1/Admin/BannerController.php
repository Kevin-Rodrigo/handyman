<?php

namespace Modules\PromotionManagement\Http\Controllers\Api\V1\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\PromotionManagement\Entities\Banner;

class BannerController extends Controller
{
    private Banner $banner;

    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
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
            'resource_type' => 'required|in:category,service,link,all',
            'string' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $banner = $this->banner->with(['service', 'category'])
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('banner_title', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->when($request->has('resource_type') && $request['resource_type'] != 'all', function ($query) use ($request) {
                return $query->where(['resource_type' => $request['resource_type']]);
            })->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $banner), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'banner_title' => 'required',
            'resource_type' => 'required|in:service,category,link',
            'resource_id' => 'uuid',
            'banner_image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $banner = $this->banner;
        $banner->banner_title = $request['banner_title'];
        $banner->redirect_link = $request['redirect_link'];
        $banner->resource_type = $request['resource_type'];
        $banner->resource_id = $request['resource_id'];
        $banner->banner_image = file_uploader('banner/', 'png', $request->file('banner_image'));
        $banner->is_active = 1;
        $banner->save();

        return response()->json(response_formatter(BANNER_CREATE_200), 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function edit(string $id): JsonResponse
    {
        $banner = $this->banner->with(['service', 'category'])->where('id', $id)->first();
        if (isset($banner)) {
            return response()->json(response_formatter(DEFAULT_200, $banner), 200);
        }
        return response()->json(response_formatter(DEFAULT_204, $banner), 200);
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
            'banner_title' => 'required',
            'resource_type' => 'required|in:service,category,link',
            'resource_id' => 'uuid',
            'banner_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $banner = $this->banner->where(['id' => $id])->first();
        $banner->banner_title = $request['banner_title'];
        $banner->redirect_link = $request['redirect_link'];
        $banner->resource_type = $request['resource_type'];
        $banner->resource_id = $request['resource_id'];
        $banner->banner_image = file_uploader('banner/', 'png', $request->file('banner_image'), $banner->banner_image);
        $banner->save();

        return response()->json(response_formatter(BANNER_UPDATE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'banner_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $banners = $this->banner->whereIn('id', $request['banner_ids']);
        if ($banners->count() > 0) {
            foreach ($banners->get() as $banner) {
                file_remover('banner/', $banner['banner_image']);
            }
            $banners->delete();
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
            'banner_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->banner->whereIn('id', $request['banner_ids'])->update(['is_active' => $request['status']]);

        return response()->json(response_formatter(DEFAULT_STATUS_UPDATE_200), 200);
    }

}
