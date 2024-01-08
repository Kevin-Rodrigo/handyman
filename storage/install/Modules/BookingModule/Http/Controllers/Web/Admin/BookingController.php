<?php

namespace Modules\BookingModule\Http\Controllers\Web\Admin;

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
use Modules\CartModule\Entities\Cart;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ProviderManagement\Entities\SubscribedService;
use Modules\ServiceManagement\Entities\Service;
use Modules\ServiceManagement\Entities\Variation;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\UserAddress;
use Modules\ZoneManagement\Entities\Zone;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingController extends Controller
{

    private Booking $booking;
    private BookingStatusHistory $booking_status_history;
    private BookingScheduleHistory $booking_schedule_history;
    private $subscribed_sub_categories;
    private Category $category;
    private Zone $zone;
    private Serviceman $serviceman;
    private Provider $provider;
    private UserAddress $user_address;
    private BookingDetail $booking_detail;

    use BookingTrait;

    public function __construct(Booking $booking, BookingDetail $booking_detail, BookingStatusHistory $booking_status_history, BookingScheduleHistory $booking_schedule_history, SubscribedService $subscribedService, Category $category, Zone $zone, Serviceman $serviceman, Provider $provider, UserAddress $user_address)
    {
        $this->booking = $booking;
        $this->booking_detail = $booking_detail;
        $this->booking_status_history = $booking_status_history;
        $this->booking_schedule_history = $booking_schedule_history;
        $this->category = $category;
        $this->zone = $zone;
        $this->serviceman = $serviceman;
        $this->provider = $provider;
        $this->user_address = $user_address;
        try {
            $this->subscribed_sub_categories = $subscribedService->where(['is_subscribed' => 1])->pluck('sub_category_id')->toArray();
        } catch (\Exception $exception) {
            $this->subscribed_sub_categories = $subscribedService->pluck('sub_category_id')->toArray();
        }
    }

    public function custom_index()
    {
        return view('bookingmodule::admin.booking.custom-list');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        $request->validate([
            'booking_status' => 'in:' . implode(',', array_column(BOOKING_STATUSES, 'key')) . ',all',
        ]);
        $request['booking_status'] = $request['booking_status'] ?? 'pending';

        $query_param = [];
        $filter_counter = 0;

        if ($request->has('zone_ids')) {
            $zone_ids = $request['zone_ids'];
            $query_param['zone_ids'] = $zone_ids;
            $filter_counter += count($zone_ids);
        }

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

        if ($request->has('booking_type')) {
            $booking_type = $request['booking_type'];
            $query_param['booking_type'] = $booking_status;
        } else {
            $booking_type = '';
        }

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
            ->when($booking_status != 'all', function ($query) use ($booking_status, $max_booking_amount, $request) {
                $query->when($booking_status == 'pending', function ($query) use ($booking_status, $max_booking_amount) {
                    $query->where('booking_status', $booking_status)
                        ->where(function ($query) use ($max_booking_amount) {
                            $query->where('payment_method', '!=', 'cash_after_service')
                                ->orWhere(function ($query) use ($max_booking_amount) {
                                    $query->where('payment_method', 'cash_after_service')
                                        ->where('total_booking_amount', '<=', $max_booking_amount)
                                        ->orWhere('is_verified', 1);
                                });
                        });
                    })
                ->when($booking_status == 'accepted', function ($query) use ($booking_status, $max_booking_amount) {
                    $query->where('booking_status', $booking_status)
                        ->where(function ($query) use ($max_booking_amount) {
                            $query->where('payment_method', '!=', 'cash_after_service')
                                ->orWhere(function ($query) use ($max_booking_amount) {
                                    $query->where('payment_method', 'cash_after_service')
                                        ->where('total_booking_amount', '<=', $max_booking_amount)
                                        ->orWhere('is_verified', 1);
                                });
                        });
                })
                ->ofBookingStatus($request['booking_status']);
            })
            ->when($request->has('zone_ids'), function ($query) use ($request) {
                $query->whereIn('zone_id', $request['zone_ids']);
            })->when($query_param['start_date'] != null && $query_param['end_date'] != null, function ($query) use ($request) {
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
        $zones = $this->zone->select('id', 'name')->get();
        $categories = $this->category->select('id', 'parent_id', 'name')->where('position', 1)->get();
        $sub_categories = $this->category->select('id', 'parent_id', 'name')->where('position', 2)->get();

        return view('bookingmodule::admin.booking.list', compact('bookings', 'zones', 'categories', 'sub_categories', 'query_param', 'filter_counter'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function check_booking()
    {
        $this->booking->where('is_checked', 0)->update(['is_checked' => 1]); //update the unseen bookings
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function booking_verification_list(Request $request): Factory|View|Application
    {
        $request->validate([
            'booking_status' => 'in:' . implode(',', array_column(BOOKING_STATUSES, 'key')) . ',all',
        ]);
        $request['booking_status'] = $request['booking_status'] ?? 'pending';

        $query_param = [];
        $filter_counter = 0;

        if ($request->has('zone_ids')) {
            $zone_ids = $request['zone_ids'];
            $query_param['zone_ids'] = $zone_ids;
            $filter_counter += count($zone_ids);
        }

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
            ->when($booking_status == 'pending', function ($query) use ($max_booking_amount) {
                $query->where('is_verified', '0')
                    ->where('payment_method', 'cash_after_service')
                    ->Where('total_booking_amount', '>', $max_booking_amount)
                    ->whereIn('booking_status', ['pending', 'accepted']);
            })
            ->when($request->has('zone_ids'), function ($query) use ($request) {
                $query->whereIn('zone_id', $request['zone_ids']);
            })->when($query_param['start_date'] != null && $query_param['end_date'] != null, function ($query) use ($request) {
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
        $zones = $this->zone->select('id', 'name')->get();
        $categories = $this->category->select('id', 'parent_id', 'name')->where('position', 1)->get();
        $sub_categories = $this->category->select('id', 'parent_id', 'name')->where('position', 2)->get();

        return view('bookingmodule::admin.booking.verification-list', compact('bookings', 'zones', 'categories', 'sub_categories', 'query_param', 'filter_counter'));
    }

    /**
     * Display a listing of the resource.
     * @param $id
     * @param Request $request
     * @return Renderable
     */
    public function details($id, Request $request): Renderable|RedirectResponse
    {
        Validator::make($request->all(), [
            'web_page' => 'required|in:details,status',
        ]);
        $web_page = $request->has('web_page') ? $request['web_page'] : 'business_setup';

        if ($request->web_page == 'details') {

            $booking = $this->booking->with(['detail.service' => function ($query) {
                $query->withTrashed();
            }, 'detail.service.category', 'detail.service.subCategory', 'detail.variation', 'customer', 'provider', 'service_address', 'serviceman', 'service_address', 'status_histories.user'])->find($id);

            $servicemen = $this->serviceman->with(['user'])
                ->where('provider_id', $booking->provider_id)
                ->whereHas('user', function ($query) {
                    $query->ofStatus(1);
                })
                ->latest()
                ->get();

            $category = $booking?->detail?->first()?->service?->category;
            $sub_category = $booking?->detail?->first()?->service?->subCategory;
            $services = Service::select('id', 'name')->where('category_id', $category->id)->where('sub_category_id', $sub_category->id)->get();

            $customer_address = $this->user_address->find($booking['service_address_id']);
            $zones = Zone::ofStatus(1)->get();


            return view('bookingmodule::admin.booking.details', compact('booking', 'servicemen', 'web_page', 'customer_address', 'services', 'zones', 'category', 'sub_category'));

        } elseif ($request->web_page == 'status') {
            $booking = $this->booking->with(['detail.service', 'customer', 'provider', 'service_address', 'serviceman.user', 'service_address', 'status_histories.user'])->find($id);
            return view('bookingmodule::admin.booking.status', compact('booking', 'web_page'));
        }

        Toastr::success(ACCESS_DENIED['message']);
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param $booking_id
     * @param Request $request
     * @return JsonResponse
     */
    public function status_update($booking_id, Request $request): JsonResponse
    {
        Validator::make($request->all(), [
            'booking_status' => 'required|in:' . implode(',', array_column(BOOKING_STATUSES, 'key')),
        ]);

        $booking = $this->booking->where('id', $booking_id)->first();

        if (isset($booking)) {
            $booking->booking_status = $request['booking_status'];

            $booking_status_history = $this->booking_status_history;
            $booking_status_history->booking_id = $booking_id;
            $booking_status_history->changed_by = $request->user()->id;
            $booking_status_history->booking_status = $request['booking_status'];

            if ($booking->isDirty('booking_status')) {
                DB::transaction(function () use ($booking_status_history, $booking) {
                    $booking->save();
                    $booking_status_history->save();
                });

                return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
            }
            return response()->json(NO_CHANGES_FOUND, 200);
        }
        return response()->json(DEFAULT_204, 200);
    }

    public function verification_update($booking_id, Request $request): JsonResponse
    {
        $booking = $this->booking->where('id', $booking_id)->first();
        if (isset($booking)) {
            $booking->is_verified = 1;
            $booking->save();

            if (isset($booking->provider_id)) {
                $fcm_token = Provider::with('owner')->whereId($booking->provider_id)->first()->owner->fcm_token ?? null;
                if (!is_null($fcm_token)) {
                    device_notification($fcm_token, translate('New booking has arrived'), null, null, $booking->id, 'booking');
                }
            } else {
                $provider_ids = SubscribedService::where('sub_category_id', $booking->sub_category_id)->ofSubscription(1)->pluck('provider_id')->toArray();
                $providers = Provider::with('owner')->whereIn('id', $provider_ids)->where('zone_id', $booking->zone_id)->get();
                foreach ($providers as $provider) {
                    $fcm_token = $provider->owner->fcm_token ?? null;
                    if (!is_null($fcm_token)) device_notification($fcm_token, translate('New booking has arrived'), null, null, $booking->id, 'booking');
                }
            }
            return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
        }
        return response()->json(DEFAULT_204, 200);
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
            'payment_status' => 'required|in:1,0',
        ]);

        $booking = $this->booking->where('id', $booking_id)->first();
        if (isset($booking)) {
            $booking->is_paid = $request->payment_status == '1' ? 1 : 0;

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

        $booking = $this->booking->where('id', $booking_id)->first();

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

        $booking = $this->booking->where('id', $booking_id)->first();

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
    public function service_address_update($service_address_id, Request $request): RedirectResponse
    {
        Validator::make($request->all(), [
            'city' => 'required',
            'street' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'address' => 'required',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'address_label' => 'required',
            'zone_id' => 'required|uuid',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $user_address = $this->user_address->find($service_address_id);
        $user_address->city = $request['city'];
        $user_address->street = $request['street'];
        $user_address->zip_code = $request['zip_code'];
        $user_address->country = $request['country'];
        $user_address->address = $request['address'];
        $user_address->contact_person_name = $request['contact_person_name'];
        $user_address->contact_person_number = $request['contact_person_number'];
        $user_address->address_label = $request['address_label'];
        $user_address->zone_id = $request['zone_id'];
        $user_address->lat = $request['latitude'];
        $user_address->lon = $request['longitude'];
        $user_address->save();

        Toastr::success(DEFAULT_UPDATE_200['message']);
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
        $request['booking_status'] = $request['booking_status'] ?? 'pending';

        $query_param = [];

        if ($request->has('zone_ids')) {
            $zone_ids = $request['zone_ids'];
            $query_param['zone_ids'] = $zone_ids;
        }

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

        $items = $this->booking->with(['customer'])
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $keys = explode(' ', $request['search']);
                    foreach ($keys as $key) {
                        $query->orWhere('readable_id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($booking_status != 'all', function ($query) use ($booking_status) {
                $query->ofBookingStatus($booking_status);
            })
            ->when($request->has('zone_ids'), function ($query) use ($request) {
                $query->whereIn('zone_id', $request['zone_ids']);
            })->when($query_param['start_date'] != null && $query_param['end_date'] != null, function ($query) use ($request) {
                $query->whereBetween('created_at', [$request['start_date'], $request['end_date']]);
            })->when($request->has('sub_category_ids'), function ($query) use ($request) {
                $query->whereIn('sub_category_id', $request['sub_category_ids']);
            })->when($request->has('category_ids'), function ($query) use ($request) {
                $query->whereIn('category_id', $request['category_ids']);
            })
            ->latest()->get();


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
        $booking = $this->booking->with(['detail.service' => function ($query) {
            $query->withTrashed();
        }, 'customer', 'provider', 'service_address', 'serviceman', 'service_address', 'status_histories.user'])->find($id);
        return view('bookingmodule::admin.booking.invoice', compact('booking'));
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verify_offline_payment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 200);
        }

        $booking = $this->booking->find($request['booking_id']);
        $booking->is_paid = 1;
        $booking->save();

        place_booking_transaction_for_digital_payment($booking);

        return response()->json(response_formatter(DEFAULT_UPDATE_200, null), 200);
    }
}
