<?php

namespace Modules\ServiceManagement\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\BookingModule\Entities\Booking;
use Modules\CategoryManagement\Entities\Category;
use Modules\ReviewModule\Entities\Review;
use Modules\ServiceManagement\Entities\Faq;
use Modules\ServiceManagement\Entities\Service;
use Modules\ServiceManagement\Entities\Tag;
use Modules\ServiceManagement\Entities\Variation;
use Modules\ZoneManagement\Entities\Zone;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServiceController extends Controller
{
    private $service, $booking, $category, $zone, $variation, $faq, $review;

    public function __construct(Service $service, Booking $booking, Category $category, Zone $zone, Variation $variation, Faq $faq, Review $review)
    {
        $this->service = $service;
        $this->booking = $booking;
        $this->category = $category;
        $this->zone = $zone;
        $this->variation = $variation;
        $this->faq = $faq;
        $this->review = $review;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request): View|Factory|Application
    {
        $categories = $this->category->ofStatus(1)->ofType('main')->latest()->get();
        $zones = $this->zone->ofStatus(1)->latest()->get();

        return view('servicemanagement::admin.create', compact('categories', 'zones'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $request->validate([
            'status' => 'in:active,inactive,all',
            'zone_id' => 'uuid'
        ]);

        $search = $request->has('search') ? $request['search'] : '';
        $status = $request->has('status') ? $request['status'] : 'all';
        $query_param = ['search' => $search, 'status' => $status];

        $services = $this->service->with(['category.zonesBasicInfo'])->latest()
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
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
            })->paginate(pagination_limit())->appends($query_param);

        return view('servicemanagement::admin.list', compact('services', 'search', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $variations = session('variations');
        session()->forget('variations');

        $request->validate([
            'name' => 'required|max:191',
            'category_id' => 'required|uuid',
            'sub_category_id' => 'required|uuid',
            'cover_image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000',
            'description' => 'required',
            'short_description' => 'required',
            'thumbnail' => 'required',
            'tax' => 'required|numeric|min:0|max:100',
            'min_bidding_price' => 'required|numeric|min:0|not_in:0',
        ]);


        $tag_ids = [];
        if ($request->tags != null) {
            $tags = explode(",", $request->tags);
        }
        if(isset($tags)){
            foreach ($tags as $key => $value) {
                $tag = Tag::firstOrNew(['tag' => $value]);
                $tag->save();
                $tag_ids[] = $tag->id;
            }
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
        $service->min_bidding_price = $request->min_bidding_price;
        $service->save();
        $service->tags()->sync($tag_ids);

        //decoding url encoded keys
        $data = $request->all();
        $data = collect($data)->map(function ($value, $key) {
            $key = urldecode($key);
            return [$key => $value];
        })->collapse()->all();

        $variation_format = [];
        if ($variations) {
            $zones = $this->zone->ofStatus(1)->latest()->get();
            foreach ($variations as $item) {
                foreach ($zones as $zone) {
                    $variation_format[] = [
                        'variant' => $item['variant'],
                        'variant_key' => $item['variant_key'],
                        'zone_id' => $zone->id,
                        'price' => $data[$item['variant_key'] . '_' . $zone->id . '_price'] ?? 0,
                        'service_id' => $service->id
                    ];
                }
            }
        }

        $service->variations()->createMany($variation_format);

        Toastr::success(SERVICE_STORE_200['message']);

        return back();
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @param string $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function show(Request $request, string $id)
    {
        $service = $this->service->where('id', $id)->with(['category.zones', 'category.children', 'variations.zone', 'reviews'])->withCount(['bookings'])->first();

        $service->total_review_count = $service->reviews->avg('review_rating');;

        $ongoing = $this->booking->whereHas('detail', function ($query) use ($id) {
            return $query->where('service_id', $id);
        })->where(['booking_status' => 'ongoing'])->count();
        $canceled = $this->booking->whereHas('detail', function ($query) use ($id) {
            return $query->where('service_id', $id);
        })->where(['booking_status' => 'canceled'])->count();

        $faqs = $this->faq->latest()->where('service_id', $id)->get();

        $search = $request->has('search') ? $request['search'] : '';
        $web_page = $request->has('review_page') || $request->has('search') ? 'review' : 'general';
        $query_param = ['search' => $search, 'web_page' => $web_page];

        $reviews = $this->review->with(['customer', 'booking'])
            ->where('service_id', $id)
            ->when(!is_null($request->search), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                foreach ($keys as $key) {
                    $query->orWhere('review_comment', 'LIKE', '%' . $key . '%');
                }
            })
            ->latest()->paginate(pagination_limit(), ['*'], 'review_page')->appends($query_param);

        $rating_group_count = DB::table('reviews')
            ->select('review_rating', DB::raw('count(*) as total'))
            ->groupBy('review_rating')
            ->get();

        if (isset($service)) {
            $service['ongoing_count'] = $ongoing;
            $service['canceled_count'] = $canceled;
            return view('servicemanagement::admin.detail', compact('service', 'faqs', 'reviews', 'rating_group_count', 'web_page', 'search'));
        }

        Toastr::error(DEFAULT_204['message']);
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(string $id): View|Factory|RedirectResponse|Application
    {
        $service = $this->service->where('id', $id)->with(['category.children', 'category.zones', 'variations'])->first();
        if (isset($service)) {
            $editing_variants = $service->variations->pluck('variant_key')->unique()->toArray();
            session()->put('editing_variants', $editing_variants);
            $categories = $this->category->ofStatus(1)->ofType('main')->latest()->get();

            $category = $this->category->where('id', $service->category_id)->with(['zones'])->first();
            $zones = $category->zones ?? [];
            session()->put('category_wise_zones', $zones);

            $tag_names = [];
            if($service->tags) {
                foreach ($service->tags as $tag) {
                    $tag_names[] = $tag['tag'];
                }
            }

            return view('servicemanagement::admin.edit', compact('categories', 'zones', 'service', 'tag_names'));
        }

        Toastr::info(DEFAULT_204['message']);
        return back();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse|RedirectResponse
     */
    public function update(Request $request, string $id): JsonResponse|RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|uuid',
            'sub_category_id' => 'required|uuid',
            'description' => 'required',
            'tax' => 'required|numeric|min:0',
            'variants' => 'required|array',
            'min_bidding_price' => 'required|numeric|min:0|not_in:0',
        ]);

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
        $service->min_bidding_price = $request->min_bidding_price;
        $service->save();

        $service->variations()->delete();

        //decoding url encoded keys
        $data = $request->all();
        $data = collect($data)->map(function ($value, $key) {
            $key = urldecode($key);
            return [$key => $value];
        })->collapse()->all();

        $variation_format = [];
        $zones = $this->zone->ofStatus(1)->latest()->get();
        foreach ($data['variants'] as $item) {
            foreach ($zones as $zone) {
                $variation_format[] = [
                    'variant' => str_replace('_', ' ', $item),
                    'variant_key' => $item,
                    'zone_id' => $zone->id,
                    'price' => $data[ $item. '_' . $zone->id . '_price'] ?? 0,
                    'service_id' => $service->id
                ];
            }
        }

        $service->variations()->createMany($variation_format);
        session()->forget('variations');
        session()->forget('editing_variants');

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $service = $this->service->where('id', $id)->first();
        if (isset($service)) {
            foreach (['thumbnail', 'cover_image'] as $item) {
                file_remover('service/', $service[$item]);
            }
            $service->variations()->delete();
            $service->delete();

            Toastr::success(DEFAULT_DELETE_200['message']);
            return back();
        }
        Toastr::success(DEFAULT_204['message']);
        return back();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function status_update(Request $request, $id): JsonResponse
    {
        $service = $this->service->where('id', $id)->first();
        $this->service->where('id', $id)->update(['is_active' => !$service->is_active]);

        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }


    public function ajax_add_variant(Request $request)
    {
        $variation = [
            'variant' => $request['name'],
            'variant_key' => str_replace(' ', '-', $request['name']),
            'price' => $request['price']
        ];

        $zones = session()->has('category_wise_zones') ? session('category_wise_zones') : [];
        $existing_data = session()->has('variations') ? session('variations') : [];
        $editing_variants = session()->has('editing_variants') ? session('editing_variants') : [];

        if (!self::searchForKey($request['name'], $existing_data) && !in_array(str_replace(' ', '-', $request['name']), $editing_variants)) {
            $existing_data[] = $variation;
            session()->put('variations', $existing_data);
        } else {
            return response()->json(['flag' => 0, 'message' => translate('already_exist')]);
        }

        return response()->json(['flag' => 1, 'template' => view('servicemanagement::admin.partials._variant-data', compact('zones'))->render()]);
    }

    public function ajax_remove_variant($variant_key)
    {
        $zones = session()->has('category_wise_zones') ? session('category_wise_zones') : [];
        $existing_data = session()->has('variations') ? session('variations') : [];

        $filtered = collect($existing_data)->filter(function ($values) use ($variant_key) {
            return $values['variant_key'] != $variant_key;
        })->values()->toArray();

        session()->put('variations', $filtered);

        return response()->json(['flag' => 1, 'template' => view('servicemanagement::admin.partials._variant-data', compact('zones'))->render()]);
    }

    public function ajax_delete_db_variant($variant_key, $service_id)
    {
        $zones = session()->has('category_wise_zones') ? session('category_wise_zones') : $this->zone->ofStatus(1)->latest()->get();
        $this->variation->where(['variant_key' => $variant_key, 'service_id' => $service_id])->delete();
        $variants = $this->variation->where(['service_id' => $service_id])->get();

        return response()->json(['flag' => 1, 'template' => view('servicemanagement::admin.partials._update-variant-data', compact('zones', 'variants'))->render()]);
    }

    function searchForKey($variant, $array): int|string|null
    {
        foreach ($array as $key => $val) {
            if ($val['variant'] === $variant) {
                return true;
            }
        }
        return false;
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     */
    public function download(Request $request): string|StreamedResponse
    {
        $items = $this->service->with(['category.zonesBasicInfo'])->latest()
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%');
                }
            })
            ->when($request->has('category_id'), function ($query) use ($request) {
                return $query->where('category_id', $request->category_id);
            })->when($request->has('sub_category_id'), function ($query) use ($request) {
                return $query->where('sub_category_id', $request->sub_category_id);
            })->when($request->has('zone_id'), function ($query) use ($request) {
                return $query->whereHas('category.zonesBasicInfo', function ($queryZone) use ($request) {
                    $queryZone->where('zone_id', $request['zone_id']);
                });
            })->latest()->get();

        return (new FastExcel($items))->download(time() . '-file.xlsx');
    }
}
