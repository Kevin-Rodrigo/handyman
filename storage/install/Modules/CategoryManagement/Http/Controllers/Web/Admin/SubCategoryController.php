<?php

namespace Modules\CategoryManagement\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\SubscribedService;
use Modules\ZoneManagement\Entities\Zone;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
{

    private $category;
    private $subscribed_service;

    public function __construct(Category $category, SubscribedService $subscribed_service)
    {
        $this->category = $category;
        $this->subscribed_service = $subscribed_service;
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return Renderable
     */
    public function create(Request $request): Renderable
    {
        $search = $request->has('search') ? $request['search'] : '';
        $status = $request->has('status') ? $request['status'] : 'all';
        $query_param = ['search' => $search, 'status' => $status];

        $sub_categories = $this->category->withCount('services')->with(['parent'])
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($status != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })
            ->ofType('sub')->latest()->paginate(pagination_limit())->appends($query_param);

        $main_categories = $this->category->ofType('main')->orderBy('name')->get(['id', 'name']);

        return view('categorymanagement::admin.sub-category.create', compact('sub_categories', 'main_categories', 'status', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:categories',
            'parent_id' => 'required|uuid',
            'short_description' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10240',
        ]);

        $category = $this->category;
        $category->name = $request->name;
        $category->image = file_uploader('category/', 'png', $request->file('image'));
        $category->parent_id = $request['parent_id'];
        $category->position = 2;
        $category->description = $request['short_description'];
        $category->save();

        Toastr::success(CATEGORY_STORE_200['message']);
        return back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('categorymanagement::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit(string $id): View|Factory|RedirectResponse|Application
    {
        $sub_category = $this->category->ofType('sub')->where('id', $id)->first();
        if (isset($sub_category)) {
            $main_categories = $this->category->ofType('main')->orderBy('name')->get(['id', 'name']);
            return view('categorymanagement::admin.sub-category.edit', compact('sub_category', 'main_categories'));
        }
        Toastr::error(DEFAULT_204['message']);
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
            'name' => 'required|unique:categories,name,' . $id,
            'parent_id' => 'required|uuid',
            'short_description' => 'required',
        ]);

        $category = $this->category->ofType('sub')->where('id', $id)->first();
        if (!$category) {
            return response()->json(response_formatter(CATEGORY_204), 204);
        }
        $category->name = $request->name;
        if ($request->has('image')) {
            $category->image = file_uploader('category/', 'png', $request->file('image'), $category->image);
        }
        $category->parent_id = $request['parent_id'];
        $category->position = 2;
        $category->description = $request['short_description'];
        $category->save();

        Toastr::success(CATEGORY_UPDATE_200['message']);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Request $request,$id): RedirectResponse
    {
        $category = $this->category->where('id', $id)->ofType($this)->first();
        if ($category) {
            file_remover('category/', $category->image);
            DB::transaction(function () use ($category, $id) {
                $category->delete();
                $this->subscribed_service->where('sub_category_id', $id)->delete();
            });

            Toastr::success(CATEGORY_DESTROY_200['message']);
            return back();
        }
        Toastr::success(CATEGORY_204['message']);
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
        $category = $this->category->ofType('sub')->where('id', $id)->first();
        $this->category->where('id', $id)->update(['is_active' => !$category->is_active]);

        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     */
    public function download(Request $request): string|StreamedResponse
    {
        $items = $this->category->withCount('services')->with(['parent'])
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->ofType('sub')->latest()->get();

        return (new FastExcel($items))->download(time().'-file.xlsx');
    }

}
