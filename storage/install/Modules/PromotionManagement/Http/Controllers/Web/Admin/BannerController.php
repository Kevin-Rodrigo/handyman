<?php

namespace Modules\PromotionManagement\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CategoryManagement\Entities\Category;
use Modules\PromotionManagement\Entities\Banner;
use Modules\ServiceManagement\Entities\Service;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BannerController extends Controller
{
    private Banner $banner;
    private Category $category;
    private Service $service;

    public function __construct(Banner $banner, Category $category, Service $service)
    {
        $this->banner = $banner;
        $this->category = $category;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request): View|Factory|Application
    {
        $search = $request->has('search') ? $request['search'] : '';
        $resource_type = $request->has('resource_type') ? $request['resource_type'] : 'all';
        $query_param = ['search' => $search, 'resource_type' => $resource_type];

        $categories = $this->category->ofStatus(1)->ofType('main')->latest()->get();
        $services = $this->service->active()->latest()->get();

        $banners = $this->banner->with(['service', 'category'])
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('banner_title', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request->has('resource_type') && $request['resource_type'] != 'all', function ($query) use ($request) {
                return $query->where(['resource_type' => $request['resource_type']]);
            })->latest()->paginate(pagination_limit())->appends($query_param);

        return view('promotionmanagement::admin.promotional-banners.create', compact('banners', 'services', 'categories','resource_type','search'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'banner_title' => 'required',
            'service_id' => 'uuid',
            'category_id' => 'uuid',
            'resource_type' => 'required|in:service,category,link',
            'banner_image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        $banner = $this->banner;
        $banner->banner_title = $request['banner_title'];
        $banner->redirect_link = $request['redirect_link'];
        $banner->resource_type = $request['resource_type'];
        if ($request['resource_type'] != 'link') {
            $resource_id = $request['resource_type'] == 'service' ? $request['service_id'] : $request['category_id'];
        } else {
            $resource_id = null;
        }
        $banner->resource_id = $resource_id;
        $banner->banner_image = file_uploader('banner/', 'png', $request->file('banner_image'));
        $banner->is_active = 1;
        $banner->save();

        Toastr::success(BANNER_CREATE_200['message']);
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return Application|Factory|View
     */
    public function edit(string $id): View|Factory|Application
    {
        $banner = $this->banner->with(['service', 'category'])->where('id', $id)->first();
        $categories = $this->category->ofStatus(1)->ofType('main')->latest()->get();
        $services = $this->service->active()->latest()->get();

        return view('promotionmanagement::admin.promotional-banners.edit', compact('categories', 'services', 'banner'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'banner_title' => 'required',
            'resource_type' => 'required|in:service,category,link',
            'service_id' => 'uuid',
            'category_id' => 'uuid',
            'banner_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        $banner = $this->banner->where(['id' => $id])->first();
        $banner->banner_title = $request['banner_title'];
        $banner->redirect_link = $request['redirect_link'];
        $banner->resource_type = $request['resource_type'];
        if ($request['resource_type'] != 'link') {
            $resource_id = $request['resource_type'] == 'service' ? $request['service_id'] : $request['category_id'];
        } else {
            $resource_id = null;
        }
        $banner->resource_id = $resource_id;
        $banner->banner_image = file_uploader('banner/', 'png', $request->file('banner_image'), $banner->banner_image);
        $banner->save();

        Toastr::success(BANNER_UPDATE_200['message']);
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
        $banner = $this->banner->where('id', $id)->first();

        if (isset($banner)){
            file_remover('banner/', $banner['banner_image']);
            $this->banner->where('id', $id)->delete();
        }
        Toastr::success(DEFAULT_DELETE_200['message']);
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
        $banner = $this->banner->where('id', $id)->first();
        $this->banner->where('id', $id)->update(['is_active' => !$banner->is_active]);

        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     */
    public function download(Request $request): string|StreamedResponse
    {
        $items = $this->banner->with(['service', 'category'])
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('banner_title', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request->has('resource_type') && $request['resource_type'] != 'all', function ($query) use ($request) {
                return $query->where(['resource_type' => $request['resource_type']]);
            })->latest()->get();

        return (new FastExcel($items))->download(time().'-file.xlsx');
    }
}
