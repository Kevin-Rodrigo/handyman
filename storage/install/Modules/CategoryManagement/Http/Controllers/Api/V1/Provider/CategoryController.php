<?php

namespace Modules\CategoryManagement\Http\Controllers\Api\V1\Provider;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\SubscribedService;

class CategoryController extends Controller
{

    private $category, $subscribedService;

    public function __construct(SubscribedService $subscribedService, Category $category)
    {
        $this->category = $category;
        $this->subscribedService = $subscribedService;
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
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $categories = $this->category->ofStatus(1)->ofType('main')
            ->whereHas('zones', function ($query) use ($request) {
                return $query->where('zone_id', $request->user()->provider->zone_id);
            })
            ->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $categories), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function childes(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $childes = $this->category->with('services')
            ->when($request['id'] != 'all', function ($query) use ($request) {
                return $query->where('parent_id', $request->id);
            })
            ->whereHas('parent.zones', function ($query) use ($request) {
                return $query->where('zone_id', $request->user()->provider->zone_id);
            })
            ->ofStatus(1)->ofType('sub')->orderBY('name', 'asc')
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $subscribed_subcategory_id = $this->subscribedService->where('provider_id', $request->user()->provider->id)
            ->where('is_subscribed', 1)->pluck('sub_category_id')->toArray();
        foreach ($childes as $child) {
            $child->is_subscribed = in_array($child->id, $subscribed_subcategory_id) ? 1 : 0;
        }

        return response()->json(response_formatter(DEFAULT_200, $childes), 200);
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function sub_category(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:subscribed,unsubscribed,all'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $subcategory_id = $this->subscribedService->where('provider_id', $request->user()->provider->id)
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->where('is_subscribed', ($request['status'] == 'subscribed') ? 1 : 0);
            })->pluck('sub_category_id')->toArray();

        $sub_categories = $this->category
            ->withCount('services')->with(['parent:id,name,image,description'])
            ->when($request->has('string'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $keys = explode(' ', base64_decode($request['string']));
                    foreach ($keys as $key) {
                        $query->orWhere('name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->whereIn('id', $subcategory_id)
            ->ofStatus(1)->ofType('sub')->orderBY('name', 'asc')
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $subscribed_subcategory_id = $this->subscribedService->where('provider_id', $request->user()->provider->id)->where('is_subscribed', 1)->pluck('sub_category_id')->toArray();
        foreach ($sub_categories as $sub_category) {
            $sub_category->is_subscribed = in_array($sub_category->id, $subscribed_subcategory_id) ? 1 : 0;
        }

        return response()->json(response_formatter(DEFAULT_200, $sub_categories), 200);
    }
}
