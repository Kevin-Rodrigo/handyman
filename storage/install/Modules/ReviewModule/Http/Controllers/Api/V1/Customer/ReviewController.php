<?php

namespace Modules\ReviewModule\Http\Controllers\Api\V1\Customer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ReviewModule\Entities\Review;
use Modules\ServiceManagement\Entities\Service;

class ReviewController extends Controller
{
    private $review, $booking, $service, $provider;

    public function __construct(Review $review, Booking $booking, Service $service, Provider $provider)
    {
        $this->review = $review;
        $this->booking = $booking;
        $this->service = $service;
        $this->provider = $provider;
    }


    /**
     * Show resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|uuid',
            //'service_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $reviews = $this->review
            ->with(['booking.detail.variation'])
            ->with(['service'])
            ->where('booking_id', $request->booking_id)
            //->where('service_id', $request->service_id)
            ->where('customer_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('service_id');

        return response()->json(response_formatter(DEFAULT_STORE_200, $reviews), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|uuid',
            'service_id' => 'required|uuid',
            'review_rating' => 'required|numeric|min:1|max:5',
            'review_comment' => 'required',
            'review_images' => 'image',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking->find($request['booking_id']);
        if (!isset($booking)) {
            return response()->json(response_formatter(DEFAULT_404), 200);
        }

        $images = [];
        if ($request->has('images')) {
            foreach ($request->images as $image) {
                $images[] = file_uploader('review/', 'png', $image);
            }
        }
        $review = $this->review
            ->where('booking_id', $request->booking_id)
            ->where('service_id', $request->service_id)
            ->where('customer_id', $request->user()->id)
            ->first();

        if(!isset($review)) {
            $review = $this->review;
        }
        $review->booking_id = $request->booking_id;
        $review->service_id = $request->service_id;
        $review->customer_id = $request->user()->id;
        $review->review_rating = $request->review_rating;
        $review->review_comment = $request->review_comment;
        $review->provider_id = $booking->provider_id;
        $review->review_images = $images;
        $review->booking_date = $booking->created_at;
        $review->save();

        foreach (['service_id' => $request->service_id, 'provider_id' => $booking->provider_id] as $key => $value) {
            $rating_group_count = DB::table('reviews')->where($key, $value)
                ->select('review_rating', DB::raw('count(*) as total'))
                ->groupBy('review_rating')
                ->get();

            $total_rating = 0;
            $rating_count = 0;
            foreach ($rating_group_count as $count) {
                $total_rating += round($count->review_rating * $count->total, 2);
                $rating_count += $count->total;
            }

            $query = collect([]);
            if ($key == 'service_id') {
                $query = $this->service->where(['id' => $value]);
            } elseif ($key == 'provider_id') {
                $query = $this->provider->where(['id' => $value]);
            }

            $query->update([
                'rating_count' => $rating_count,
                'avg_rating' => round($total_rating / $rating_count, 2)
            ]);
        }


        return response()->json(response_formatter(DEFAULT_STORE_200), 200);
    }
}
