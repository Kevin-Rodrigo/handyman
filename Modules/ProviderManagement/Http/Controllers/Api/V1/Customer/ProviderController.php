<?php

namespace Modules\ProviderManagement\Http\Controllers\Api\V1\Customer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ProviderManagement\Entities\SubscribedService;

class ProviderController extends Controller
{
    private Provider $provider;
    private Category $category;
    private SubscribedService $subscribed_service;

    public function __construct(Provider $provider, Category $category, SubscribedService $subscribed_service)
    {
        $this->provider = $provider;
        $this->category = $category;
        $this->subscribed_service = $subscribed_service;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_provider_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'sort_by' => 'in:asc,desc',
            'category_ids' => 'array',
            'category_ids.*' => 'uuid',
            'rating' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $providers = $this->provider->with(['owner', 'subscribed_services.sub_category'=>function($query){
                $query->withoutGlobalScopes();
            }])
//            ->whereHas('zone', function ($query) {
//                $query->where('id', Config::get('zone_id'));
//            })
            ->where('zone_id', Config::get('zone_id'))
            ->ofStatus(1)
            ->when($request->has('category_ids'), function ($query) use($request) {
                $query->whereHas('subscribed_services', function ($query) use($request) {
                    if ($request->has('category_ids')) $query->whereIn('category_id', $request['category_ids']);
                });
            })
            ->when($request->has('rating'), function ($query) use($request) {
                $query->where('avg_rating', '>=', $request['rating']);
            })
            ->when($request->has('sort_by'), function ($query) use($request) {
                $query->orderBy('company_name', $request['sort_by']);
            })
            ->when(!$request->has('sort_by'), function ($query) use($request) {
                $query->latest();
            })
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');


        return response()->json(response_formatter(DEFAULT_200, $providers), 200);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_provider_details(Request $request)
    {
        $provider = $this->provider->with('owner')->with(['reviews.customer'])->find($request['id']);

        $subscribed_sub_category_ids = $this->subscribed_service->ofStatus(1)->where('provider_id', $provider->id)->pluck('sub_category_id')->toArray();
        $sub_categories = $this->category->withoutGlobalScopes()->with('services.variations')
            ->whereHas('services', function ($query) {
                $query->ofStatus(1);
            })
            ->whereIn('id', $subscribed_sub_category_ids)->get();

        foreach ($sub_categories as $item) {
            if ($item->services) {
                $item->services = self::variation_mapper($item->services);
            }
        }

        return response()->json(response_formatter(DEFAULT_200, ['provider'=>$provider, 'sub_categories'=>$sub_categories]), 200);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_provider_list_by_sub_category(Request $request)
    {
        $providers = $this->provider->with(['owner'])
            ->where('zone_id', Config::get('zone_id'))
            ->whereHas('subscribed_services', function ($query) use($request) {
                $query->where('sub_category_id', $request['sub_category_id']);
            })
            ->get();

        return response()->json(response_formatter(DEFAULT_200, $providers), 200);
    }

    private function variation_mapper($services)
    {
        $services->map(function ($service) {
            $service['variations_app_format'] = self::variations_app_format($service);
            return $service;
        });
        return $services;
    }

    private function variations_app_format($service): array
    {
        $formatting = [];
        $filtered = $service['variations']->where('zone_id', Config::get('zone_id'));
        $formatting['zone_id'] = Config::get('zone_id');
        $formatting['default_price'] = $filtered->first() ? $filtered->first()->price : 0;
        foreach ($filtered as $data) {
            $formatting['zone_wise_variations'][] = [
                'variant_key' => $data['variant_key'],
                'variant_name' => $data['variant'],
                'price' => $data['price']
            ];
        }
        return $formatting;
    }

}
