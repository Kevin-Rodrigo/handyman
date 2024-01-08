<?php

namespace Modules\BookingModule\Http\Controllers\Web\Provider;

use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\BookingModule\Entities\Booking;
use Modules\BookingModule\Entities\BookingDetail;
use Modules\BookingModule\Entities\BookingScheduleHistory;
use Illuminate\Http\RedirectResponse;
use Modules\BookingModule\Entities\BookingStatusHistory;
use Modules\BookingModule\Http\Traits\BookingTrait;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ProviderManagement\Entities\SubscribedService;
use Modules\ServiceManagement\Entities\Service;
use Modules\ServiceManagement\Entities\Variation;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserAddress;
use Modules\ZoneManagement\Entities\Zone;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingController extends Controller
{

    private Booking $booking;
    private BookingDetail $booking_detail;
    private BookingStatusHistory $booking_status_history;
    private BookingScheduleHistory $booking_schedule_history;
    private $subscribed_sub_categories;
    private Category $category;
    private Zone $zone;
    private Serviceman $serviceman;
    private Provider $provider;
    private SubscribedService $subscribed_service;
    private User $user;
    private UserAddress $user_address;

    use BookingTrait;

    public function __construct(Booking $booking, BookingStatusHistory $booking_status_history, BookingScheduleHistory $booking_schedule_history, SubscribedService $subscribedService, Category $category, Zone $zone, Serviceman $serviceman, Provider $provider, SubscribedService $subscribed_service, User $user, UserAddress $user_address, BookingDetail $booking_detail)
    {
        $this->booking = $booking;
        $this->booking_status_history = $booking_status_history;
        $this->booking_schedule_history = $booking_schedule_history;
        $this->category = $category;
        $this->zone = $zone;
        $this->serviceman = $serviceman;
        $this->provider = $provider;
        $this->subscribed_service = $subscribed_service;
        $this->user = $user;
        $this->user_address = $user_address;
        $this->booking_detail = $booking_detail;

        try {
            $this->subscribed_sub_categories = $subscribedService->where(['provider_id' => auth('api')->user()->provider->id])
                ->where(['is_subscribed' => 1])->pluck('sub_category_id')->toArray();
        } catch (\Exception $exception) {
            $this->subscribed_sub_categories = $subscribedService->pluck('sub_category_id')->toArray();
        }
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $request->validate([
            'booking_status' => 'in:' . implode(',', array_column(BOOKING_STATUSES, 'key')) . ',all',
        ]);

        $query_param = [];
        $filter_counter = 0;

        if ($request->has('category_ids')) {
            $category_ids = $request['category_ids'];
            $query_param['category_ids'] = $category_ids;
            $filter_counter += count($category_ids);
        }

        if ($request->has('sub_category_ids')) {
            $sub_category_ids = $request['sub_category_ids'];
            $query_param['sub_category_ids'] = $sub_category_ids;
            $filter_counter += count($sub_category_ids);
        }

        if ($request->has('start_date')) {
            $start_date = $request['start_date'];
            $query_param['start_date'] = $start_date;
            if (!is_null($request['start_date'])) $filter_counter++;
        } else {
            $query_param['start_date'] = null;
        }

        if ($request->has('end_date')) {
            $end_date = $request['end_date'];
            $query_param['end_date'] = $end_date;
            if (!is_null($request['end_date'])) $filter_counter++;
        } else {
            $query_param['end_date'] = null;
        }

        if ($request->has('search')) {
            $search = $request['search'];
            $query_param['search'] = $search;
        }

        if ($request->has('booking_status')) {
            $booking_status = $request['booking_status'];
            $query_param['booking_status'] = $booking_status;
        } else {
            $query_param['booking_status'] = 'pending';
        }

        $sub_category_ids = $this->subscribed_service->where('provider_id', $request->user()->provider->id)->ofSubscription(1)->pluck('sub_category_id')->toArray();

        $max_booking_amount = (business_config('max_booking_amount', 'booking_setup'))->live_values;

        $bookings = $this->booking->with(['customer'])
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $keys = explode(' ', $request['search']);
                    foreach ($keys as $key) {
                        $query->orWhere('readable_id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when(!in_array($request['booking_status'], ['pending', 'all']), function ($query) use ($request) {
                $query->ofBookingStatus($request['booking_status'])
                    ->where('provider_id', $request->user()->provider->id);
            })
            ->when(in_array($request['booking_status'], ['pending', 'all']), function ($query) use ($request, $sub_category_ids) {
                $query->whereIn('sub_category_id', $sub_category_ids)->where('zone_id', $request->user()->provider->zone_id);
            })
            ->when($request['booking_status'] == 'all', function ($query) use ($request) {
                $query->where('provider_id', $request->user()->provider->id);
            })
            ->when($request['booking_status'] == 'pending', function ($query) use ($request, $max_booking_amount) {
                $query->ofBookingStatus($request['booking_status'])->whereIn('sub_category_id', $this->subscribed_sub_categories)
                ->when($max_booking_amount > 0, function($query) use ($max_booking_amount) {
                    $query->where(function ($query) use ($max_booking_amount) {
                        $query->where('payment_method', 'cash_after_service')
                            ->where(function ($query) use ($max_booking_amount) {
                                $query->where('is_verified', 1)
                                    ->orWhere('total_booking_amount', '<=', $max_booking_amount);
                            })
                            ->orWhere('payment_method', '<>', 'cash_after_service');
                    });
                });
            })
            ->when($booking_status == 'accepted', function ($query) use ($booking_status, $max_booking_amount) {
                $query->when($max_booking_amount > 0, function($query) use ($max_booking_amount) {
                    $query->where(function ($query) use ($max_booking_amount) {
                        $query->where('payment_method', '!=', 'cash_after_service')
                            ->orWhere(function ($query) use ($max_booking_amount) {
                                $query->where('payment_method', 'cash_after_service')
                                    ->where('total_booking_amount', '<=', $max_booking_amount)
                                    ->orWhere('is_verified', 1);
                            });
                    });
                })->where('booking_status', $booking_status);
            })
            ->when($query_param['start_date'] != null && $query_param['end_date'] != null, function ($query) use ($request) {
                if ($request['start_date'] == $request['end_date']) {
                    $query->whereDate('created_at', Carbon::parse($request['start_date'])->startOfDay());
                } else {
                    $query->whereBetween('created_at', [Carbon::parse($request['start_date'])->startOfDay(), Carbon::parse($request['end_date'])->endOfDay()]);
                }
            })->when($request->has('sub_category_ids'), function ($query) use ($request) {
                $query->whereIn('sub_category_id', $request['sub_category_ids']);
            })->when($request->has('category_ids'), function ($query) use ($request) {
                $query->whereIn('category_id', $request['category_ids']);
            })
            ->latest()->paginate(pagination_limit())->appends($query_param);

        //for filter
        $categories = $this->category->select('id', 'parent_id', 'name')->where('position', 1)->get();
        $sub_categories = $this->category->select('id', 'parent_id', 'name')->where('position', 2)->get();
        return view('bookingmodule::provider.booking.list', compact('bookings', 'categories', 'sub_categories', 'query_param', 'filter_counter'));
    }

    /**
     * Display a listing of the resource.
     * @return void
     */
    public function check_booking($id)
    {
        $this->booking->where('id', $id)->whereIn('sub_category_id', $this->subscribed_sub_categories)
            ->where('is_checked', 0)->update(['is_checked' => 1]); //update the unseen bookings
    }

    /**
     * Display a listing of the resource.
     * @param $id
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function details($id, Request $request)
    {
        Validator::make($request->all(), [
            'web_page' => 'required|in:details,status',
        ]);

        $web_page = $request->has('web_page') ? $request['web_page'] : 'details';
        $booking = $this->booking->with(['detail.service' => fn ($query) => $query->withTrashed(), 'detail.service.category', 'detail.service.subCategory', 'detail.variation', 'customer', 'provider', 'service_address', 'serviceman', 'service_address', 'status_histories.user'])->find($id);

        if ($booking['booking_status'] != 'pending' && $booking['provider_id'] != $request->user()->provider->id) {
            Toastr::error(ACCESS_DENIED['message']);
            return redirect(route('provider.booking.list'));
        }

        if ($request->web_page == 'details') {
            $servicemen = $this->serviceman->with(['user'])
                ->whereHas('user', function($q){
                    $q->ofStatus(1);
                })
                ->where('provider_id', $this->provider->where('user_id', $request->user()->id)->first()->id)
                ->latest()
                ->get();

            //service address
            $customer_addresses = $this->user_address->where(['user_id' => $booking?->customer?->id])->get();

            $category = $booking?->detail?->first()?->service?->category;
            $sub_category = $booking?->detail?->first()?->service?->subCategory;
            $services = Service::select('id', 'name')->where('category_id', $category->id)->where('sub_category_id', $sub_category->id)->get();

            $customer_address = $this->user_address->find($booking['service_address_id']);
            $zones = Zone::ofStatus(1)->get();

            return view('bookingmodule::provider.booking.details', compact('booking', 'servicemen', 'web_page', 'customer_addresses', 'category', 'sub_category', 'services', 'customer_address', 'zones'));

        } elseif ($request->web_page == 'status') {
            return view('bookingmodule::provider.booking.status', compact('booking', 'web_page'));
        }
    }

    /**
     * Display a listing of the resource.
     * @param $booking_id
     * @param Request $request
     * @return JsonResponse
     */
    public function status_update($booking_id, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_status' => 'required|in:' . implode(',', array_column(BOOKING_STATUSES, 'key')),
            'otp_field' => ((business_config('booking_otp', 'booking_setup'))->live_values == 1 && $request->booking_status == 'completed') ? 'required' : 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking->where('id', $booking_id)->where(function ($query) use ($request) {
            return $query->where('provider_id', $request->user()->provider->id)->orWhereNull('provider_id');
        })->first();

        if (isset($booking)) {
            //OTP
            if ($request->booking_status == 'completed' && (business_config('booking_otp', 'booking_setup'))?->live_values == 1) {
                $otp_number = implode('', $request->otp_field);
                if ($booking->booking_otp != $otp_number) {
                    return response()->json(response_formatter(OTP_VERIFICATION_FAIL_403), 200);
                }
            }

            $booking->booking_status = $request['booking_status'];
            $booking->provider_id = $request->user()->provider->id;

            $booking_status_history = $this->booking_status_history;
            $booking_status_history->booking_id = $booking_id;
            $booking_status_history->changed_by = $request->user()->id;
            $booking_status_history->booking_status = $request['booking_status'];

            if ($booking->isDirty('booking_status')) {
                DB::transaction(function () use ($booking_status_history, $booking) {
                    $booking->save();
                    $booking_status_history->save();
                });

                self::check_booking($booking->id);

                return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
            }
            return response()->json(NO_CHANGES_FOUND, 200);
        }
        return response()->json(DEFAULT_204, 200);
    }

        /**
     * Display a listing of the resource.
     * @param $booking_id
     * @param Request $request
     * @return JsonResponse
     */
    public function evidence_photos_upload($booking_id, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'evidence_photos' => 'nullable|array',
        ]);

        $booking = $this->booking->where('id', $booking_id)->where(function ($query) use ($request) {
            return $query->where('provider_id', $request->user()->provider->id)->orWhereNull('provider_id');
        })->first();
        $booking_status = $request->booking_status;

        if (isset($booking)) {

            $evidence_photos = [];

            if ($request->has('evidence_photos')) {
                foreach ($request->evidence_photos as $image) {
                    $evidence_photos[] = file_uploader('booking/evidence/', 'png', $image);
                }
            }

            $booking->evidence_photos = $evidence_photos;
            $booking->save();

            return response()->json(DEFAULT_UPDATE_200, 200);
        }
        return response()->json(DEFAULT_204, 200);
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function resend_otp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking
            ->with(['customer'])
            ->where('id', $request['booking_id'])
            ->where(function ($query) use ($request) {
                return $query->where('provider_id', $request->user()->provider->id);
            })
            ->first();

        if (!isset($booking)) {
            return response()->json(response_formatter(DEFAULT_404), 404);
        }

        $fcm_token = $booking?->customer?->fcm_token;
        $title = translate('Your booking verification OTP is') . ' ' . $booking->booking_otp;

        if ($fcm_token) {
            device_notification($fcm_token, $title, null, null, $booking->id, 'booking', null, $booking?->customer?->id);
            return response()->json(response_formatter(NOTIFICATION_SEND_SUCCESSFULLY_200), 200);

        } else {
            return response()->json(response_formatter(NOTIFICATION_SEND_FAILED_200), 200);
        }
    }

    /**
     * Display a listing of the resource.
     * @param $booking_id
     * @param Request $request
     * @return JsonResponse
     */
    public function payment_update($booking_id, Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        $booking = $this->booking->where('id', $booking_id)->where(function ($query) use ($request) {
            return $query->where('provider_id', $request->user()->provider->id)->orWhereNull('provider_id');
        })->first();

        if (isset($booking)) {
            $booking->is_paid = $request->payment_status == 'paid' ? 1 : 0;

            if ($booking->isDirty('is_paid')) {
                $booking->save();
                return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
            }
            return response()->json(NO_CHANGES_FOUND, 200);
        }
        return response()->json(DEFAULT_204, 200);
    }

    /**
     * Display a listing of the resource.
     * @param $booking_id
     * @param Request $request
     * @return JsonResponse
     */
    public function schedule_upadte($booking_id, Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'service_schedule' => 'required',
        ]);

        $booking = $this->booking->where('id', $booking_id)->where(function ($query) use ($request) {
            return $query->where('provider_id', $request->user()->provider->id)->orWhereNull('provider_id');
        })->first();

        if (isset($booking)) {
            $booking->service_schedule = Carbon::parse($request->service_schedule)->toDateTimeString();

            //history
            $booking_schedule_history = $this->booking_schedule_history;
            $booking_schedule_history->booking_id = $booking_id;
            $booking_schedule_history->changed_by = $request->user()->id;
            $booking_schedule_history->schedule = $request['service_schedule'];

            if ($booking->isDirty('service_schedule')) {
                $booking->save();
                $booking_schedule_history->save();
                return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
            }
            return response()->json(NO_CHANGES_FOUND, 200);
        }
        return response()->json(DEFAULT_204, 200);
    }

    /**
     * Display a listing of the resource.
     * @param $booking_id
     * @param Request $request
     * @return JsonResponse
     */
    public function serviceman_update($booking_id, Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'serviceman_id' => 'required|uuid',
        ]);

        $booking = $this->booking->where('id', $booking_id)->where(function ($query) use ($request) {
            return $query->where('provider_id', $request->user()->provider->id)->orWhereNull('provider_id');
        })->first();

        if (isset($booking)) {
            $booking->serviceman_id = $request->serviceman_id;

            if ($booking->isDirty('serviceman_id')) {
                $booking->save();
                return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
            }
            return response()->json(NO_CHANGES_FOUND, 200);
        }
        return response()->json(DEFAULT_204, 200);
    }

    /**
     * Display a listing of the resource.
     * @param $booking_id
     * @param Request $request
     * @return RedirectResponse
     */
    public function service_address_update($booking_id, Request $request): RedirectResponse
    {
        Validator::make($request->all(), [
            'service_address_id' => 'required',
        ]);

        $booking = $this->booking->where('id', $booking_id)->first();

        if (isset($booking)) {
            $booking->service_address_id = $request->service_address_id;

            if ($booking->isDirty('service_address_id')) {
                $booking->save();

                Toastr::success(DEFAULT_STATUS_UPDATE_200['message']);
                return back();
            }
            Toastr::info(NO_CHANGES_FOUND['message']);
            return back();
        }
        Toastr::success(DEFAULT_204['message']);
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     */
    public function download(Request $request): string|StreamedResponse
    {
        $request->validate([
            'booking_status' => 'in:' . implode(',', array_column(BOOKING_STATUSES, 'key')) . ',all',
        ]);

        $query_param = [];

        if ($request->has('category_ids')) {
            $category_ids = $request['category_ids'];
            $query_param['category_ids'] = $category_ids;
        }

        if ($request->has('sub_category_ids')) {
            $sub_category_ids = $request['sub_category_ids'];
            $query_param['sub_category_ids'] = $sub_category_ids;
        }

        if ($request->has('start_date')) {
            $start_date = $request['start_date'];
            $query_param['start_date'] = $start_date;
        } else {
            $query_param['start_date'] = null;
        }

        if ($request->has('end_date')) {
            $end_date = $request['end_date'];
            $query_param['end_date'] = $end_date;
        } else {
            $query_param['end_date'] = null;
        }

        if ($request->has('search')) {
            $search = $request['search'];
            $query_param['search'] = $search;
        }

        if ($request->has('booking_status')) {
            $booking_status = $request['booking_status'];
            $query_param['booking_status'] = $booking_status;
        } else {
            $query_param['booking_status'] = 'pending';
        }

        $sub_category_ids = $this->subscribed_service->where('provider_id', $request->user()->provider->id)->ofSubscription(1)->pluck('sub_category_id')->toArray();

        $items = $this->booking->with(['customer'])
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $keys = explode(' ', $request['search']);
                    foreach ($keys as $key) {
                        $query->orWhere('readable_id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when(!in_array($request['booking_status'], ['pending', 'all']), function ($query) use ($request) {
                $query->ofBookingStatus($request['booking_status'])
                    ->where('provider_id', $request->user()->provider->id);
            })
            ->when(in_array($request['booking_status'], ['pending', 'all']), function ($query) use ($request, $sub_category_ids) {
                $query->whereIn('sub_category_id', $sub_category_ids);
            })
            ->when($request['booking_status'] == 'all', function ($query) use ($request) {
                $query->where('provider_id', $request->user()->provider->id);
            })->when($request['booking_status'] == 'pending', function ($query) use ($request) {
                $query->ofBookingStatus($request['booking_status'])->whereIn('sub_category_id', $this->subscribed_sub_categories);
            })->when($query_param['start_date'] != null && $query_param['end_date'] != null, function ($query) use ($request) {
                $query->whereBetween('created_at', [$request['start_date'], $request['end_date']]);
            })->when($request->has('sub_category_ids'), function ($query) use ($request) {
                $query->whereIn('sub_category_id', $request['sub_category_ids']);
            })->when($request->has('category_ids'), function ($query) use ($request) {
                $query->whereIn('category_id', $request['category_ids']);
            })->latest()->get();


        return (new FastExcel($items))->download(time() . '-file.xlsx');
    }


    /**
     * Display a listing of the resource.
     * @param $id
     * @param Request $request
     * @return Renderable
     */
    public function invoice($id, Request $request): Renderable
    {
        $booking = $this->booking->with(['detail.service'=>function ($query) {
            $query->withTrashed();
        }, 'customer', 'provider', 'service_address', 'serviceman', 'service_address', 'status_histories.user'])->find($id);
        return view('bookingmodule::provider.booking.invoice', compact('booking'));
    }


    //=========== BOOKING EDIT ===========

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function ajax_get_service_info(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'zone_id' => 'required|uuid',
            'service_id' => 'required|uuid',
            'variant_key' => 'required',
            'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 200);
        }

        $service = Service::active()
            ->with(['category.category_discount', 'category.campaign_discount', 'service_discount'])
            ->where('id', $request['service_id'])
            ->with(['variations' => fn($query) => $query->where('variant_key', $request['variant_key'])->where('zone_id', $request['zone_id'])])
            ->first();

        //calculation
        $quantity = $request['quantity'];
        $variation_price = $service?->variations[0]?->price;

        $basic_discount = basic_discount_calculation($service, $variation_price * $quantity);
        $campaign_discount = campaign_discount_calculation($service, $variation_price * $quantity);
        $subtotal = round($variation_price * $quantity, 2);

        $applicable_discount = ($campaign_discount >= $basic_discount) ? $campaign_discount : $basic_discount;

        $tax = round((($variation_price * $quantity - $applicable_discount) * $service['tax']) / 100, 2);

        //between normal discount & campaign discount, greater one will be calculated
        $basic_discount = $basic_discount > $campaign_discount ? $basic_discount : 0;
        $campaign_discount = $campaign_discount >= $basic_discount ? $campaign_discount : 0;

        $data = collect([
            'service_id' => $service->id,
            'service_name' => $service->name,
            'variant_key' => $service?->variations[0]?->variant_key,
            'quantity' => $request['quantity'],
            'service_cost' => $variation_price,
            'total_discount_amount' => $basic_discount + $campaign_discount,
            'coupon_code' => null,
            'tax_amount' => round($tax, 2),
            'total_cost' => round($subtotal - $basic_discount - $campaign_discount + $tax, 2),
            'zone_id' => $request['zone_id']
        ]);

        return response()->json([
            'view' => view('bookingmodule::admin.booking.partials.details.table-row', compact('data'))->render()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function ajax_get_variant(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'zone_id' => 'required|uuid',
            'service_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 200);
        }

        $variations = Variation::where('service_id', $request['service_id'])
            ->where('zone_id', $request['zone_id'])
            ->where('price', '>', 0)
            ->get();
        return response()->json(response_formatter(DEFAULT_200, $variations, null), 200);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update_booking_service(Request $request): RedirectResponse
    {
        Validator::make($request->all(), [
            'qty' => 'required|array',
            'qty.*' => 'int',
            'service_ids' => 'required|array',
            'service_ids.*' => 'uuid',
            'variant_keys' => 'required|array',
            'variant_keys.*' => 'string',
            'zone_id' => 'required|uuid',
            'booking_id' => 'required|uuid',
        ])->validate();

        $service_info = [];
        foreach ($request['service_ids'] as $key => $service_id) {
            $variant_key = $request['variant_keys'][$key] ?? null;
            $quantity = $request['qty'][$key] ?? 0;

            $service_info[] = [
                'service_id' => $service_id,
                'variant_key' => $variant_key,
                'quantity' => $quantity,
            ];
        }
        $request->merge(['service_info' => collect($service_info)]);

        //service format for add service
        $existing_services = $this->booking_detail->where('booking_id', $request['booking_id'])->get();
        foreach ($existing_services as $item) {
            if(!$request['service_info']->where('service_id', $item->service_id)->where('variant_key', $item->variant_key)->first()) {
                $request['service_info']->push([
                    'service_id' => $item->service_id,
                    'variant_key' => $item->variant_key,
                    'quantity' => 0,
                ]);
            }
        }

        //update
        foreach ($request['service_info'] as $key=>$item) {
            $existing_service = $this->booking_detail
                ->where('booking_id', $request['booking_id'])
                ->where('service_id', $item['service_id'])
                ->where('variant_key', $item['variant_key'])
                ->first();

            if (!$existing_service) {
                //dd('add new service');
                //add new service
                $request['service_id'] = $item['service_id'];
                $request['variant_key'] = $item['variant_key'];
                $request['quantity'] = $item['quantity'];
                $this->add_new_booking_service($request);

            } else if ($existing_service && $item['quantity'] == 0) {
                //dd('remove [existing service]');
                //remove [existing service]
                $request['service_id'] = $item['service_id'];
                $request['variant_key'] = $item['variant_key'];
                $request['quantity'] = $item['quantity'];

                $this->remove_service_from_booking($request);

            } else if($existing_service && $existing_service->quantity < $item['quantity']) {
                //dd('add quantity [to existing service]');
                //add quantity [to existing service]
                $request['service_id'] = $item['service_id'];
                $request['variant_key'] = $item['variant_key'];
                $request['old_quantity'] = $existing_service->quantity;
                $request['new_quantity'] = (int)$item['quantity'];
                $this->increase_service_quantity_from_booking($request);

            } else if ($existing_service && $existing_service->quantity > $item['quantity']) {
                //dd('remove quantity [from existing service]');
                //remove quantity [from existing service]
                $request['service_id'] = $item['service_id'];
                $request['variant_key'] = $item['variant_key'];
                $request['old_quantity'] = $existing_service->quantity;
                $request['new_quantity'] = (int)$item['quantity'];

                $this->decrease_service_quantity_from_booking($request);

            }
        }

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }
}
