<?php

namespace Modules\CategoryManagement\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CategoryManagement\Entities\Category;
use Illuminate\Support\Facades\Validator;
use Modules\ZoneManagement\Entities\Zone;


class CategoryController extends Controller
{

    private $category, $zone;

    public function __construct(Category $category, Zone $zone)
    {
        $this->category = $category;
        $this->zone = $zone;
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
            'status' => 'required|in:all,active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $categories = $this->category->with(['zones','children'])->when($request['status'] != 'all', function ($query) use ($request) {
            return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
        })->ofType('main')->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $zones = $this->zone->orderBy('name')->get(['id', 'name']);

        return response()->json(response_formatter(DEFAULT_200, ['categories' => $categories, 'zones' => $zones]), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        if (!is_array($request['zone_ids'])) {
            $request['zone_ids'] = $request->has('zone_ids') ? json_decode($request['zone_ids']) : '';
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories',
            'zone_ids' => 'required|array',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $category = $this->category;
        $category->name = $request->name;
        $category->image = file_uploader('category/', 'png', $request->file('image'));
        $category->parent_id = 0;
        $category->position = 1;
        $category->description = null;
        $category->save();
        $category->zones()->sync($request->zone_ids);

        return response()->json(response_formatter(CATEGORY_STORE_200), 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function edit(string $id): JsonResponse
    {
        $category = $this->category->with(['zones'])->ofType('main')->where('id', $id)->first();

        if (isset($category)) {
            $zones = $this->zone->orderBy('name')->get(['id', 'name']);
            return response()->json(response_formatter(DEFAULT_200, ['category' => $category, 'zones' => $zones]), 200);
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
        if (!is_array($request['zone_ids'])) {
            $request['zone_ids'] = $request->has('zone_ids') ? json_decode($request['zone_ids']) : '';
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,' . $id,
            'zone_ids' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $category = $this->category->ofType('main')->where('id', $id)->first();
        if (!$category) {
            return response()->json(response_formatter(CATEGORY_204), 204);
        }
        $category->name = $request->name;
        if ($request->has('image')) {
            $category->image = file_uploader('category/', 'png', $request->file('image'), $category->image);
        }
        $category->parent_id = 0;
        $category->position = 1;
        $category->description = null;
        $category->save();
        $category->zones()->sync($request->zone_ids);

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
            'category_ids' => 'required|array'
        ]);
        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }
        $categories = $this->category->ofType('main')->whereIn('id', $request['category_ids'])->get();
        if (!$categories) {
            return response()->json(response_formatter(CATEGORY_204), 200);
        }
        foreach ($categories as $category) {
            file_remover('category/', $category->image);
            $category->zones()->sync([]);
            $category->delete();
        }
        return response()->json(response_formatter(CATEGORY_DESTROY_200), 200);
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
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:all,active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $categories = $this->category->with(['zones','children'])
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%');
                }
            })->when($request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->ofType('main')->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        if ($categories->count() > 0) {
            return response()->json(response_formatter(DEFAULT_200, $categories), 200);
        }
        return response()->json(response_formatter(DEFAULT_204, $categories), 200);
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
            'category_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->category->ofType('main')->whereIn('id', $request['category_ids'])->update(['is_active' => $request['status']]);

        return response()->json(response_formatter(DEFAULT_STATUS_UPDATE_200), 200);
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
            'status' => 'required|in:all,active,inactive',
            'id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $childes = $this->category->when($request['status'] != 'all', function ($query) use ($request) {
            return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
        })->ofType('sub')->with(['zones'])->where('parent_id', $request['id'])->orderBY('name', 'asc')
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $childes), 200);
    }

}
