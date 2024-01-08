<?php

namespace Modules\ServiceManagement\Http\Controllers\Web\Provider;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\SubscribedService;
use Modules\ReviewModule\Entities\Review;
use Modules\ServiceManagement\Entities\Faq;
use Modules\ServiceManagement\Entities\Service;
use Modules\ServiceManagement\Entities\ServiceRequest;
use Auth;

class ServiceController extends Controller
{
    private Service $service;
    private Review $review;
    private SubscribedService $subscribed_service;
    private Category $category;
    private Booking $booking;
    private Faq $faq;

    public function __construct(Service $service, Review $review, SubscribedService $subscribed_service, Category $category, Booking $booking, Faq $faq)
    {
        $this->service = $service;
        $this->review = $review;
        $this->subscribed_service = $subscribed_service;
        $this->category = $category;
        $this->booking = $booking;
        $this->faq = $faq;
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $active_category = $request->has('active_category') ? $request['active_category'] : 'all';

        $subscribed_ids = $this->subscribed_service->where('provider_id', $request->user()->provider->id)
            ->ofStatus(1)
            ->pluck('sub_category_id')
            ->toArray();

        $categories = $this->category->ofStatus(1)->ofType('main')
            ->whereHas('zones', function ($query) use ($request) {
                return $query->where('zone_id', $request->user()->provider->zone_id);
            })->latest()->get();

        $sub_categories = $this->category->with(['services'])
            ->with(['services' => function ($query) {
                $query->where(['is_active' => 1]);
            }])
            ->withCount(['services' => function ($query) {
                $query->where(['is_active' => 1]);
            }])
            ->when($active_category != 'all', function ($query) use ($active_category) {
                $query->where(['parent_id' => $active_category]);
            })
            ->when($request->has('category_id') && $request['category_id'] != 'all', function ($query) use ($request) {
                $query->where('parent_id', $request['category_id']);
            })
            ->whereHas('parent.zones', function ($query) use ($request) {
                $query->where('zone_id', $request->user()->provider->zone_id);
            })
            ->whereHas('parent', function ($query) {
                $query->where('is_active', 1);
            })
            ->ofStatus(1)->ofType('sub')
            ->latest()->get();

        return view('servicemanagement::provider.available-services', compact('categories', 'sub_categories', 'subscribed_ids', 'active_category'));
    }

    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function request_list(Request $request): View|Factory|Application
    {
        $search = $request['search'];
        $requests = ServiceRequest::with(['category'])
            ->where('user_id', Auth::id())
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->whereHas('category', function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->where('name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()
            ->paginate(pagination_limit());

        return view('servicemanagement::provider.service.request-list', compact('requests', 'search'));
    }

    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function make_request(): View|Factory|Application
    {
        $categories = $this->category->ofType('main')->select('id', 'name')->get();
        return view('servicemanagement::provider.service.make-request', compact('categories'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store_request(Request $request): RedirectResponse
    {
        Validator::make($request->all(), [
            'category_ids' => 'array',
            'category_ids.*' => 'uuid',
            'service_name' => 'required|max:255',
            'service_description' => 'required',
        ])->validate();

        ServiceRequest::create([
            'category_id' => $request['category_id'],
            'service_name' => $request['service_name'],
            'service_description' => $request['service_description'],
            'status' => 'pending',
            'user_id' => $request->user()->id,
        ]);

        Toastr::success(DEFAULT_STORE_200['message']);
        return back();
    }


    public function update_subscription(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'sub_category_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 200);
        }

        $subscribedService = $this->subscribed_service::where('sub_category_id', $request['sub_category_id'])->where('provider_id', $request->user()->provider->id)->first();
        if (!isset($subscribedService)) {
            $subscribedService = $this->subscribed_service;
        }
        $subscribedService->provider_id = $request->user()->provider->id;
        $subscribedService->sub_category_id = $request['sub_category_id'];

        $parent = $this->category->where('id', $request['sub_category_id'])->whereHas('parent.zones', function ($query) {
            $query->where('zone_id', auth()->user()->provider->zone_id);
        })->first();

        if ($parent) {
            $subscribedService->category_id = $parent->parent_id;
            $subscribedService->is_subscribed = !$subscribedService->is_subscribed;
            $subscribedService->save();
            return response()->json(response_formatter(DEFAULT_200), 200);
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
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:active,inactive,all'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $reviews = $this->review->where('provider_id', $request->user()->provider->id)->where('service_id', $service_id)
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $rating_group_count = DB::table('reviews')->where('provider_id', $request->user()->provider->id)
            ->where('service_id', $service_id)
            ->select('review_rating', DB::raw('count(*) as total'))
            ->groupBy('review_rating')
            ->get();

        $total_avg = 0;
        $main_divider = 0;
        foreach ($rating_group_count as $count) {
            $total_avg = round($count->review_rating / $count->total, 2);
            $main_divider += 1;
        }

        $rating_info = [
            'rating_count' => $rating_group_count->count(),
            'average_rating' => round($total_avg / ($main_divider == 0 ? $main_divider + 1 : $main_divider), 2),
            'rating_group_count' => $rating_group_count,
        ];

        if ($reviews->count() > 0) {
            return response()->json(response_formatter(DEFAULT_200, ['reviews' => $reviews, 'rating' => $rating_info]), 200);
        }

        return response()->json(response_formatter(DEFAULT_404), 200);
    }


    /**
     * Show the specified resource.
     * @param Request $request
     * @param string $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function show(Request $request, string $id): View|Factory|RedirectResponse|Application
    {
        $service = $this->service->where('id', $id)->with(['category.children', 'variations.zone', 'reviews'])->withCount(['bookings'])->first();
        $ongoing = $this->booking->whereHas('detail', function ($query) use ($id) {
            $query->where('service_id', $id);
        })->where(['booking_status' => 'ongoing'])->count();
        $canceled = $this->booking->whereHas('detail', function ($query) use ($id) {
            $query->where('service_id', $id);
        })->where('provider_id', $request->user()->provider->id)->where(['booking_status' => 'canceled'])->count();

        $faqs = $this->faq->latest()->where('service_id', $id)->get();

        $web_page = $request->has('review_page') ? 'review' : 'general';
        $query_param = ['web_page' => $web_page];

        $reviews = $this->review->with(['customer', 'booking'])
            ->where('service_id', $id)
            ->where('provider_id', $request->user()->provider->id)
            ->latest()->paginate(pagination_limit(), ['*'], 'review_page')->appends($query_param);

        $rating_group_count = DB::table('reviews')->where('provider_id', $request->user()->provider->id)
            ->select('review_rating', DB::raw('count(*) as total'))
            ->groupBy('review_rating')
            ->get();

        if (isset($service)) {
            $service['ongoing_count'] = $ongoing;
            $service['canceled_count'] = $canceled;
            return view('servicemanagement::provider.detail', compact('service', 'faqs', 'reviews', 'rating_group_count', 'web_page'));
        }

        Toastr::error(DEFAULT_204['message']);
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('servicemanagement::edit');
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
            'sub_category_ids' => 'required|array',
            'sub_category_ids.*' => 'uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->subscribed_service->whereIn('sub_category_id', $request['sub_category_ids'])->update(['is_subscribed' => $request['status']]);

        return response()->json(response_formatter(DEFAULT_STATUS_UPDATE_200), 200);
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'string' => 'required',
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $keys = explode(' ', base64_decode($request['string']));

        $service = $this->service->where(function ($query) use ($keys) {
            foreach ($keys as $key) {
                $query->orWhere('name', 'LIKE', '%' . $key . '%');
            }
        })->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
            if ($request['status'] == 'active') {
                return $query->where(['is_active' => 1]);
            } else {
                return $query->where(['is_active' => 0]);
            }
        })->with(['category.zonesBasicInfo'])->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        if (count($service) > 0) {
            return response()->json(response_formatter(DEFAULT_200, $service), 200);
        }
        return response()->json(response_formatter(DEFAULT_204, $service), 200);
    }
}
