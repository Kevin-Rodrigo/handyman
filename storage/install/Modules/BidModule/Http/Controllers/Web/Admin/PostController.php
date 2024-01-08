<?php

namespace Modules\BidModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\BidModule\Entities\Post;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostController extends Controller
{
    public function __construct(
        private Post $post,
    )
    {
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse|Renderable
     * @throws ValidationException
     */
    public function index(Request $request): Renderable|RedirectResponse
    {
        Validator::make($request->all(), [
            'type' => 'in:all,new_booking_request,placed_offer'
        ])->validate();


        $query_param = [
            'type' => $request['type'],
            'search' => $request['search'],
        ];
        $posts = $this->post
            ->with(['bids.provider', 'addition_instructions', 'service', 'category', 'sub_category', 'booking', 'customer'])
            ->where('is_booked', 0)
            ->when($request->has('type') && $request['type'] != 'new_booking_request' && $request['type'] != 'all', function ($query) use ($request) {
                $query->whereHas('bids', function ($query) use ($request) {
                    if ($request['type'] == 'placed_offer') {
                        $query->where('status', 'pending');
                    } else if ($request['type'] == 'booking_placed') {
                        $query->where('status', 'accepted');
                    }
                });
            })
            ->when($request->has('type') && $request['type'] == 'new_booking_request', function ($query) use ($request) {
                $query->whereDoesntHave('bids');
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->whereHas('customer', function ($query) use ($request, $keys) {
                    foreach ($keys as $key) {
                        $query->where('first_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('phone', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()
            ->paginate(pagination_limit())
            ->appends($query_param);

        $type = $request['type'];
        $search = $request['search'];

        //check posts
        $this->post->where('is_checked', 0)->update(['is_checked' => 1]);
        return view('bidmodule::admin.customize-list', compact('posts', 'type', 'search'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     * @throws ValidationException
     */
    public function export(Request $request): string|StreamedResponse
    {
        Validator::make($request->all(), [
            'type' => 'in:all,new_booking_request,placed_offer',
            'search' => 'max:255'
        ])->validate();

        $posts = $this->post
            ->with(['bids', 'addition_instructions', 'service', 'category', 'sub_category', 'booking', 'customer'])
            ->when($request['type'] != 'all', function ($query) use ($request) {
                $query->where('is_booked', ($request['type'] == 'placed_offer' ? 1 : 0));
            })
            ->latest()
            ->get();

        return (new FastExcel($posts))->download(time() . '-file.xlsx');
    }

    /**
     * Display a listing of the resource.
     * @param $post_id
     * @return RedirectResponse|Renderable
     */
    public function details($post_id): Renderable|RedirectResponse
    {
        $post = $this->post
            ->with(['bids', 'addition_instructions', 'service', 'category', 'sub_category', 'booking', 'customer'])
            ->where('id', $post_id)
            ->first();

        //find distance
        $coordinates = auth()->user()->provider->coordinates ?? null;
        $distance = null;
        if(!is_null($coordinates) && $post->service_address) {
            $distance = get_distance(
                [$coordinates['latitude']??null, $coordinates['longitude']??null],
                [$post->service_address?->lat, $post->service_address?->lon]
            );
            $distance = ($distance) ? number_format($distance, 2) .' km' : null;
        }

        if (!isset($post)) {
            Toastr::success(DEFAULT_404['message']);
            return back();
        }

        return view('bidmodule::admin.details', compact('post', 'distance'));
    }

    /**
     * Display a listing of the resource.
     * @param $post_id
     * @return RedirectResponse|Renderable
     */
    public function delete($post_id): Renderable|RedirectResponse
    {
        $post = $this->post->where('id', $post_id)->first();

        if (!isset($post)) {
            Toastr::success(DEFAULT_404['message']);
            return back();
        }

        $post->delete();
        Toastr::success(DEFAULT_DELETE_200['message']);
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function multi_delete(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'post_ids' => 'required|array',
            'post_ids.*' => 'uuid',
        ])->validate();

        $this->post->whereIn('id', $request['post_ids'])->delete();
        return response()->json(response_formatter(DEFAULT_DELETE_200), 200);
    }

}
