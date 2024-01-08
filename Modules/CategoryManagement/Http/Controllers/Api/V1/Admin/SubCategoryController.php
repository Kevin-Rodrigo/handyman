<?php

namespace Modules\CategoryManagement\Http\Controllers\Api\V1\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\CategoryManagement\Entities\Category;
use Modules\ZoneManagement\Entities\Zone;

class SubCategoryController extends Controller
{

    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'string' => 'string',
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:all,active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $sub_categories = $this->category->withCount('services')->with(['parent'])
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })
            ->ofType('sub')->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $main_categories = $this->category->ofType('main')->orderBy('name')->get(['id', 'name']);

        return response()->json(response_formatter(DEFAULT_200, ['sub_categories' => $sub_categories, 'main_categories' => $main_categories]), 200);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('categorymanagement::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories',
            'parent_id' => 'required|uuid',
            'short_description' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $category = $this->category;
        $category->name = $request->name;
        $category->image = file_uploader('category/', 'png', $request->file('image'));
        $category->parent_id = $request['parent_id'];
        $category->position = 2;
        $category->description = $request['short_description'];
        $category->save();

        return response()->json(response_formatter(CATEGORY_STORE_200), 200);
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
     * @return JsonResponse
     */
    public function edit(string $id): JsonResponse
    {
        $sub_category = $this->category->ofType('sub')->where('id', $id)->first();
        if (isset($sub_category)) {
            $main_categories = $this->category->ofType('main')->orderBy('name')->get(['id', 'name']);
            return response()->json(response_formatter(DEFAULT_200, ['sub_category' => $sub_category, 'main_categories' => $main_categories]), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
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
            'name' => 'required|unique:categories,name,' . $id,
            'parent_id' => 'required|uuid',
            'short_description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

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

        return response()->json(response_formatter(CATEGORY_UPDATE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'sub_category_ids' => 'required|array'
        ]);
        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }
        $categories = $this->category->whereIn('id', $request['sub_category_ids'])->ofType($this)->get();
        if (!$categories) {
            return response()->json(response_formatter(CATEGORY_204), 200);
        }
        foreach ($categories as $category) {
            file_remover('category/', $category->image);
            $category->delete();
        }
        return response()->json(response_formatter(CATEGORY_DESTROY_200), 200);
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
            'sub_category_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->category->whereIn('id', $request['sub_category_ids'])->ofType('sub')->update(['is_active' => $request['status']]);

        return response()->json(response_formatter(DEFAULT_STATUS_UPDATE_200), 200);
    }

}
