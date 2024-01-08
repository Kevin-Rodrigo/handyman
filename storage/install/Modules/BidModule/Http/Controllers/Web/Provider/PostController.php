<?php

namespace Modules\BidModule\Http\Controllers\Web\Provider;

use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\BidModule\Entities\IgnoredPost;
use Modules\BidModule\Entities\Post;
use Modules\BidModule\Entities\PostBid;
use Modules\ProviderManagement\Entities\SubscribedService;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostController extends Controller
{
    public function __construct(
        private Post              $post,
        private SubscribedService $subscribed_service,
        private IgnoredPost       $ignored_post,
        private PostBid           $post_bid,
    )
    {
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse|Renderable
     * @throws ValidationException
     */
    public function index(Request $request)
    {
        Validator::make($request->all(), [
            'type' => 'required|in:all,new_booking_request,placed_offer'
        ])->validate();

        $query_param = ['type' => $request['type'], 'search' => $request['search']];

        $subscribed_sub_categories = $this->subscribed_service
            ->where(['provider_id' => $request->user()->provider->id])
            ->where(['is_subscribed' => 1])->pluck('sub_category_id')->toArray();

        $ignored_posts = $this->ignored_post->where('provider_id', $request->user()->provider->id)->pluck('post_id')->toArray();
        $bidding_post_validity = (int)(business_config('bidding_post_validity', 'bidding_system'))->live_values;
        $posts = $this->post
            ->with(['bids.provider', 'addition_instructions', 'service', 'category', 'sub_category', 'booking', 'customer'])
            ->where('is_booked', 0)
            ->whereNotIn('id', $ignored_posts)
            ->whereIn('sub_category_id', $subscribed_sub_categories)
            ->where('zone_id', $request->user()->provider->zone_id)
            ->whereBetween('created_at', [Carbon::now()->subDays($bidding_post_validity), Carbon::now()])
            ->when($request['type'] != 'all' && $request['type'] != 'new_booking_request', function ($query) use ($request) {
                $query->whereHas('bids', function ($query) use ($request) {
                    if ($request['type'] == 'placed_offer') {
                        $query->where('status', 'pending')->where('provider_id', $request->user()->provider->id);
                    } else if ($request['type'] == 'booking_placed') {
                        $query->where('status', 'accepted');
                    }
                });
            })
            ->when($request['type'] != 'all' && $request['type'] == 'new_booking_request', function ($query) use ($request) {
                $query->whereDoesntHave('bids', function ($query) use ($request) {
                    $query->where('provider_id', $request->user()->provider->id);
                });
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

        if ($request['type'] == 'all') {
            foreach ($posts as $key=>$post) {
                if ($post->bids) {
                    foreach ($post->bids as $bid) {
                        if ($bid->status == 'denied') unset($posts[$key]);
                    }
                }
            }
        }

        //find distance
        $coordinates = auth()->user()->provider->coordinates ?? null;
        foreach ($posts as $post) {
            $distance = null;
            if(!is_null($coordinates) && $post->service_address) {
                $distance = get_distance(
                    [$coordinates['latitude']??null, $coordinates['longitude']??null],
                    [$post->service_address?->lat, $post->service_address?->lon]
                );
                $distance = ($distance) ? number_format($distance, 2) .' km' : null;
            }
            $post->distance = $distance;
        }

        $bid_offers_visibility_for_providers = business_config('bid_offers_visibility_for_providers', 'bidding_system')?->live_values;
        $type = $request['type'];
        $search = $request['search'];

        //check posts
        $this->post->where('is_checked', 0)->update(['is_checked' => 1]);
        return view('bidmodule::provider.customize-list', compact('posts', 'bid_offers_visibility_for_providers', 'type', 'search'));
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

        $subscribed_sub_categories = $this->subscribed_service
            ->where(['provider_id' => $request->user()->provider->id])
            ->where(['is_subscribed' => 1])->pluck('sub_category_id')->toArray();

        $ignored_posts = $this->ignored_post->where('provider_id', $request->user()->provider->id)->pluck('post_id')->toArray();
        $bidding_post_validity = (int)(business_config('bidding_post_validity', 'bidding_system'))->live_values;
        $posts = $this->post
            ->with(['bids', 'addition_instructions', 'service', 'category', 'sub_category', 'booking', 'customer'])
            ->whereNotIn('id', $ignored_posts)
            ->whereIn('sub_category_id', $subscribed_sub_categories)
            ->whereBetween('created_at', [Carbon::now()->subDays($bidding_post_validity), Carbon::now()])
            ->when($request['type'] != 'all', function ($query) use ($request) {
                $query->where('is_booked', ($request['type'] == 'placed_offer' ? 1 : 0));
            })
            ->get();

        return (new FastExcel($posts))->download(time() . '-file.xlsx');
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse|Renderable
     * @throws ValidationException
     */
    public function details(Request $request, $post_id): Renderable|RedirectResponse
    {
        $post = $this->post
            ->with(['bids', 'addition_instructions', 'service', 'category', 'sub_category', 'booking', 'customer', 'service_address'])
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

        $bid_offers_visibility_for_providers = business_config('bid_offers_visibility_for_providers', 'bidding_system')?->live_values;
        return view('bidmodule::provider.details', compact('post', 'bid_offers_visibility_for_providers', 'distance'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Application|Redirector|RedirectResponse
     * @throws ValidationException
     */
    public function update_status(Request $request, $id): Redirector|RedirectResponse|Application
    {
        Validator::make($request->all(), [
            'status' => 'in:accept,ignore',

            'offered_price' => $request['status'] == 'accept' ? 'required' : '',
            'provider_note' => '',
        ])->validate();

        if ($request['status'] == 'accept') {
            $post_bid = $this->post_bid;
            $post_bid->offered_price = $request['offered_price'];
            $post_bid->provider_note = $request['provider_note'];
            $post_bid->status = 'pending';
            $post_bid->post_id = $id;
            $post_bid->provider_id = $request->user()->provider->id;
            $post_bid->save();

            Toastr::success(DEFAULT_UPDATE_200['message']);
            return back();

        } else if ($request['status'] == 'ignore') {
            $this->ignored_post->updateOrCreate(
                ['post_id' => $id, 'provider_id' => $request->user()->provider->id], [
                'post_id' => $id
            ]);
        }

        return redirect(route('provider.booking.post.list', ['type' => 'all']));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function multi_ignore(Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'post_ids' => 'required|array',
            'post_ids.*' => 'uuid',
        ])->validate();

        foreach ($request->post_ids as $id) {
            IgnoredPost::updateOrCreate(
                ['post_id' => $id, 'provider_id' => auth()->user()->provider->id], [
                'post_id' => $id
            ]);
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    public function withdraw($id, Request $request)
    {
        $post_bids = $this->post_bid
            ->where('status', 'pending')
            ->where('post_id', $id)
            ->where('provider_id', auth()->user()->provider->id);

        if ($post_bids->count() < 1) {
            Toastr::success(DEFAULT_404['message']);
            return back();
        }

        $post_bids->delete();

        Toastr::success(DEFAULT_DELETE_200['message']);
        return back();
    }

    /**
     * @return void
     */
    public function check_all(): void
    {
        $this->post->where('is_checked', 0)->update(['is_checked' => 1]);
    }
}
