<?php

namespace Modules\ServiceManagement\Http\Controllers\Api\V1\Customer;

use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\CustomerModule\Traits\CustomerSearchTrait;
use Modules\ReviewModule\Entities\Review;
use Modules\ServiceManagement\Entities\RecentSearch;
use Modules\ServiceManagement\Entities\RecentView;
use Modules\ServiceManagement\Entities\Service;
use Modules\ServiceManagement\Entities\ServiceRequest;
use Modules\ServiceManagement\Traits\VisitedServiceTrait;

class ServiceController extends Controller
{
    use VisitedServiceTrait;
    use CustomerSearchTrait;

    private $service;
    private Review $review;
    private RecentView $recent_view;
    private RecentSearch $recent_search;
    private Booking $booking;

    public function __construct(Service $service, Review $review, RecentView $recent_view, RecentSearch $recent_search, Booking $booking)
    {
        $this->service = $service;
        $this->review = $review;
        $this->recent_view = $recent_view;
        $this->recent_search = $recent_search;
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
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $services = $this->service->with(['category.zonesBasicInfo', 'variations'])
            ->active()->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, self::variation_mapper($services)), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'string' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $auth_user = auth('api')->user();
        if ($auth_user) {
            //recent search log
            $this->recent_search->Create(['user_id' => $auth_user->id, 'keyword' => base64_decode($request['string'])]);
        }

        $keys = explode(' ', base64_decode($request['string']));
        $services = $this->service->with(['category.zonesBasicInfo', 'variations'])
            ->where(function ($query) use ($keys) {
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%')
                        ->orWhereHas('tags',function($query) use ($key) {
                            $query->where('tag', 'like', "%{$key}%");
                        });
                }
            })
            ->active()->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        if ($auth_user) {
            //search log
            $recent_search = RecentSearch::where('keyword', base64_decode($request['string']))->oldest() ->first();
            $this->Searched_data_log($auth_user->id, 'search', $recent_search->id, count($services));
        }

        return response()->json(response_formatter(DEFAULT_200, self::variation_mapper($services)), 200);
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function popular(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        if($this->booking->count() > 0) {
            $services = $this->service->with(['category.zonesBasicInfo', 'variations'])
                ->has('bookings')
                ->withCount('bookings')
                ->orderBy('bookings_count', 'desc')
                ->active()
                ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        } else {
            $services = $this->service->with(['category.zonesBasicInfo', 'variations'])
                ->withCount('bookings')
                ->orderBy('bookings_count', 'desc')
                ->active()
                ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');
        }

        return response()->json(response_formatter(DEFAULT_200, self::variation_mapper($services)), 200);
    }

    /**
     * Display a listing of the resource.
     *  #   if not authenticated > Random Services
     *  #   if authenticated
     *      ##  has booking > Services of booked category
     *      ##  no booking > Random services
     * @param Request $request
     * @return JsonResponse
     */
    public function recommended(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $services = $this->service->with(['category.zonesBasicInfo', 'variations']);
        if (auth('api')->user()) {
            $category_ids = $this->booking->where('customer_id', auth('api')->user()->id)->get()->pluck('category_id');

            if (count($category_ids) > 0) {
                $services = $services
                    ->whereHas('category', function ($query) use($category_ids) {
                        $query->whereIn('category_id', $category_ids);
                    })
                    ->active()
                    ->latest()
                    ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');
            } else {
                $services = $services->active()->inRandomOrder()->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');
            }

        } else {
            $services = $services->active()->inRandomOrder()->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');
        }


        return response()->json(response_formatter(DEFAULT_200, self::variation_mapper($services)), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function search_recommended(Request $request): JsonResponse
    {
        $services = $this->service->select('id', 'name')
            ->active()
            ->inRandomOrder()
            ->take(5)->get();

        return response()->json(response_formatter(DEFAULT_200, self::variation_mapper($services)), 200);
    }

    /**
     * Trending products (Last 30days order based)
     * @param Request $request
     * @return JsonResponse
     */
    public function trending(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        if($this->booking->count() > 0) {
            $services = $this->service->with(['category.zonesBasicInfo', 'variations'])
                ->whereHas('bookings', function ($query) {
                    $query->where('created_at', '>', now()->subDays(30)->endOfDay());
                })
                ->withCount('bookings')
                ->orderBy('bookings_count', 'desc')
                ->active()
                ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        } else {
            $services = $this->service->with(['category.zonesBasicInfo', 'variations'])
                ->withCount(['bookings' => function($query) {
                    $query->where('created_at', '>', now()->subDays(30)->endOfDay());
                }])
                ->orderBy('bookings_count', 'desc')
                ->active()
                ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');
        }

        return response()->json(response_formatter(DEFAULT_200, self::variation_mapper($services)), 200);
    }

    /**
     * Recently viewed by customer (service view based)
     * @param Request $request
     * @return JsonResponse
     */
    public function recently_viewed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $service_ids = $this->recent_view
            ->where('user_id', $request->user()->id)
            ->select(
                DB::raw('count(total_service_view) as total_service_view'),
                DB::raw('service_id as service_id')
            )
            ->groupBy('total_service_view', 'service_id')
            ->pluck('service_id')
            ->toArray();

        $services = $this->service->with(['category.zonesBasicInfo', 'variations'])
            ->whereIn('id', $service_ids)
            ->active()
            ->orderBy('avg_rating', 'DESC')
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, self::variation_mapper($services)), 200);
    }

    /**
     * Recently searched keywords by customer
     * @param Request $request
     * @return JsonResponse
     */
    public function recently_searched_keywords(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $searched_keywords = $this->recent_search
            ->where('user_id', $request->user()->id)
            ->select('id', 'keyword')
            ->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        if (count($searched_keywords) > 0) {
            return response()->json(response_formatter(DEFAULT_200, $searched_keywords), 200);
        }

        return response()->json(response_formatter(DEFAULT_404), 404);
    }

    /**
     * Remove searched keywords by customer
     * @param Request $request
     * @return JsonResponse
     */
    public function remove_searched_keywords(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'array',
            'id.*' => 'uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->recent_search
            ->where('user_id', $request->user()->id)
            ->when($request->has('id'), function ($query) use ($request) {
                $query->whereIn('id', $request->id);
            })
            ->delete();

        return response()->json(response_formatter(DEFAULT_DELETE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function offers(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $services = $this->service->with(['category.zonesBasicInfo', 'variations'])
            ->whereHas('service_discount')->orWhereHas('category.category_discount')->active()
            ->orderBy('avg_rating', 'DESC')
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, self::variation_mapper($services)), 200);
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

    /**
     * Show the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $service = $this->service->where('id', $id)
            ->with(['category.children', 'variations', 'faqs' => function ($query) {
                return $query->where('is_active', 1);
            }])
            ->ofStatus(1)
            ->first();

        if (isset($service)) {
            //log for services (that are found by search)
            if($request->has('attribute') && $request->attribute == 'service' && auth('api')->user()) {
                $this->Searched_data_log(auth('api')->user()->id, 'service', $service->id, null);
            }


            //visited service log
            if (auth('api')->user()) {
                $this->visited_service_update(auth('api')->user()->id, $id);

                //search log volume update
                if($request->has('attribute') && $request->attribute != 'service') {
                    $this->search_log_volume_update(auth('api')->user()->id, $service->id);
                }
            }


            //update service view count
            $auth_user = auth('api')->user();
            if ($auth_user) {
                $recent_view = $this->recent_view->firstOrNew(['service_id' =>  $service->id, 'user_id' => $auth_user->id]);
                $recent_view->total_service_view += 1;
                $recent_view->save();
            }

            $service['variations_app_format'] = self::variations_app_format($service);
            return response()->json(response_formatter(DEFAULT_200, $service), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param string $service_id
     * @return JsonResponse
     */
    public function review(Request $request, string $service_id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $reviews = $this->review->with(['provider', 'customer'])->where('service_id', $service_id)->ofStatus(1)->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $rating_group_count = DB::table('reviews')->where('service_id', $service_id)
            ->select('review_rating', DB::raw('count(*) as total'))
            ->groupBy('review_rating')
            ->get();

        $total_rating = 0;
        $rating_count = 0;
        foreach ($rating_group_count as $count) {
            $total_rating += round($count->review_rating * $count->total, 2);
            $rating_count += $count->total;
        }

        $rating_info = [
            'rating_count' => $rating_count,
            'average_rating' => round(divnum($total_rating, $rating_count), 2),
            'rating_group_count' => $rating_group_count,
        ];

        if ($reviews->count() > 0) {
            return response()->json(response_formatter(DEFAULT_200, ['reviews' => $reviews, 'rating' => $rating_info]), 200);
        }

        return response()->json(response_formatter(DEFAULT_404), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param string $sub_category_id
     * @return JsonResponse
     */
    public function services_by_subcategory(Request $request, string $sub_category_id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $services = $this->service->with(['category.zonesBasicInfo', 'variations'])
            ->where(['sub_category_id' => $sub_category_id])
            ->latest()->where(['is_active' => 1])
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        if (count($services) > 0) {
            //update sub-category view count
            $auth_user = auth('api')->user();
            if ($auth_user) {
                $recent_view = $this->recent_view->firstOrNew(['sub_category_id' =>  $sub_category_id, 'user_id' => $auth_user->id]);
                $recent_view->total_sub_category_view += 1;
                $recent_view->save();
            }

            return response()->json(response_formatter(DEFAULT_200, self::variation_mapper($services)), 200);
        }

        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function make_request(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|uuid',
            'service_name' => 'required|max:255',
            'service_description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        ServiceRequest::create([
            'category_id' => $request['category_id'],
            'service_name' => $request['service_name'],
            'service_description' => $request['service_description'],
            'status' => 'pending',
            'user_id' => $request->user()->id,
        ]);

        return response()->json(response_formatter(DEFAULT_STORE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function request_list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $requests = ServiceRequest::with(['category'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        if ($requests->count() > 0) {
            return response()->json(response_formatter(DEFAULT_200, $requests), 200);
        }
        return response()->json(response_formatter(DEFAULT_204, $requests), 200);
    }
}
