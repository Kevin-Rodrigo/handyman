<?php

namespace Modules\PromotionManagement\Http\Controllers\Web\Admin;

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
use Modules\PromotionManagement\Entities\Banner;
use Modules\PromotionManagement\Entities\PushNotification;
use Modules\ZoneManagement\Entities\Zone;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PushNotificationController extends Controller
{
    private PushNotification $pushNotification;
    private Zone $zone;

    public function __construct(PushNotification $pushNotification, Zone $zone)
    {
        $this->pushNotification = $pushNotification;
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
            'string' => 'string',
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:active,inactive,all',
            'to_user_type' => 'required|in:customer,provider,serviceman,all',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $pushNotification = $this->pushNotification
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('title', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->when($request->has('to_user_type') && $request['to_user_type'] != 'all', function ($query) use ($request) {
                return $query->whereJsonContains('to_users', $request['to_user_type']);
            })->orderBy('created_at', 'desc')->paginate(pagination_limit(), ['*'], 'offset', $request['offset'])->withPath('');

        $pushNotification->map(function ($query) {
            $query->zone_ids = $this->zone->select('id', 'name')->whereIn('id', $query->zone_ids)->get();
        });

        return response()->json(response_formatter(DEFAULT_200, $pushNotification), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request): View|Factory|Application
    {
        $search = $request->has('search') ? $request['search'] : '';
        $to_user_type = $request->has('to_user_type') ? $request['to_user_type'] : 'all';
        $query_param = ['search' => $search, 'to_user_type' => $to_user_type];

        $zones = $this->zone->ofStatus(1)->latest()->get();

        $pushNotification = $this->pushNotification
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('title', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request->has('to_user_type') && $request['to_user_type'] != 'all', function ($query) use ($request) {
                return $query->whereJsonContains('to_users', $request['to_user_type']);
            })->orderBy('created_at', 'desc')->paginate(pagination_limit())->appends($query_param);

        $pushNotification->map(function ($query) {
            $query->zone_ids = $this->zone->select('id', 'name')->whereIn('id', $query->zone_ids)->get();
        });

        return view('promotionmanagement::admin.push-notification.create', compact('zones', 'pushNotification', 'to_user_type', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'to_users' => 'required|array',
            'to_users.*' => 'in:customer,provider-admin,provider-serviceman,all',
            'zone_ids' => 'required|array',
            'zone_ids.*' => 'uuid',
            'cover_image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        $image_name = file_uploader('push-notification/', 'png', $request->file('cover_image'));

        foreach ($request['to_users'] as $to_user) {
            if($to_user == 'all') {
                $request['to_users'] = ['customer', 'provider-admin', 'provider-serviceman'];
            }
        }

        $pushNotification = $this->pushNotification;
        $pushNotification->title = $request['title'];
        $pushNotification->description = $request['description'];
        $pushNotification->to_users = $request['to_users'];
        $pushNotification->zone_ids = $request['zone_ids'] ?? [];
        $pushNotification->cover_image = $image_name;
        $pushNotification->is_active = 1;
        $pushNotification->save();

        foreach ($request['to_users'] as $user_type) {
            foreach ($request['zone_ids'] as $zone_id) {
                topic_notification($user_type . '-' . $zone_id, $request['title'], $request['description'], $image_name, null, 'general');
            }
        }

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
        $pushNotification = $this->pushNotification->where('id', $id)->first();
        $zones = $this->zone->ofStatus(1)->latest()->get();
        $zone_array = [];
        if ($pushNotification->zone_ids != null) {
            foreach ($pushNotification->zone_ids as $id) {
                $zone = $this->zone::select('id', 'name')->find($id);
                if (!is_null($zone)) {
                    $zone_array[] = $zone;
                }
            }
            $pushNotification->zone_ids = $zone_array;
        }
        return view('promotionmanagement::admin.push-notification.edit', compact('pushNotification','zones'));
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
            'title' => 'required',
            'description' => 'required',
            'to_users' => 'required|array',
            'to_users.*' => 'in:customer,provider-admin,provider-serviceman,all',
            'zone_ids' => 'required|array',
            'zone_ids.*' => 'uuid',
            'cover_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        $pushNotification = $this->pushNotification->where(['id' => $id])->first();
        $pushNotification->title = $request['title'];
        $pushNotification->description = $request['description'];
        $pushNotification->to_users = $request['to_users'];
        $pushNotification->zone_ids = $request['zone_ids'];
        if ($request->has('cover_image')) {
            $pushNotification->cover_image = file_uploader('push-notification/', 'png', $request->file('cover_image'), $pushNotification->cover_image);
        }
        $pushNotification->save();

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
        $pushNotification = $this->pushNotification->where('id', $id)->first();
        if (isset($pushNotification)){
            file_remover('push-notification/', $pushNotification['cover_image']);
            $this->pushNotification->where('id', $id)->delete();
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
        $pushNotification = $this->pushNotification->where('id', $id)->first();
        $this->pushNotification->where('id', $id)->update(['is_active' => !$pushNotification->is_active]);

        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     */
    public function download(Request $request): string|StreamedResponse
    {
        $items = $this->pushNotification
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('title', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->when($request->has('to_user_type') && $request['to_user_type'] != 'all', function ($query) use ($request) {
                return $query->whereJsonContains('to_users', $request['to_user_type']);
            })->latest()->get();

        return (new FastExcel($items))->download(time().'-file.xlsx');
    }
}
