<?php

namespace Modules\ProviderManagement\Http\Controllers\Api\V1\Provider;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\SubscribedService;

class ServiceController extends Controller
{
    private $subscribedService, $category;

    public function __construct(SubscribedService $subscribedService, Category $category)
    {
        $this->subscribedService = $subscribedService;
        $this->category = $category;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function update_subscription(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'sub_category_id' => 'required|array',
            'sub_category_id.*' => 'uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($request['sub_category_id'] as $id) {
            $subscribedService = $this->subscribedService::where('sub_category_id', $id)->where('provider_id', $request->user()->provider->id)->first();
            if (!isset($subscribedService)) {
                $subscribedService = $this->subscribedService;
            }
            $subscribedService->provider_id = $request->user()->provider->id;
            $subscribedService->sub_category_id = $id;

            $parent = $this->category->where('id', $id)->first();
            if ($parent) {
                $subscribedService->category_id = $parent->parent_id;
            }

            $subscribedService->is_subscribed = !$subscribedService->is_subscribed;
            $subscribedService->save();
        }

        return response()->json(response_formatter(DEFAULT_200), 200);
    }
}
