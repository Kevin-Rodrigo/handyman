<?php

namespace Modules\ProviderManagement\Http\Controllers\Api\V1\Provider\Report;

use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\BookingModule\Entities\BookingDetailsAmount;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ServiceManagement\Entities\Service;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\User;
use Modules\ZoneManagement\Entities\Zone;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function pagination_limit;
use function view;
use function with_currency_symbol;

class BookingReportController extends Controller
{
    protected Zone $zone;
    protected Provider $provider;
    protected Category $categories;
    protected Booking $booking;

    protected Account $account;
    protected Service $service;
    protected User $user;
    protected Transaction $transaction;

    public function __construct(Zone $zone, Provider $provider, Category $categories, Service $service, Booking $booking, Account $account, User $user, Transaction $transaction, BookingDetailsAmount $booking_details_amount)
    {
        $this->zone = $zone;
        $this->provider = $provider;
        $this->categories = $categories;
        $this->booking = $booking;

        $this->service = $service;
        $this->account = $account;
        $this->user = $user;
        $this->transaction = $transaction;
        $this->booking_details_amount = $booking_details_amount;
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function get_booking_report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'zone_ids' => $request->has('zone_ids') && !is_null($request['zone_ids'][0]) ? 'array' : '',
            'zone_ids.*' => $request->has('zone_ids') && !is_null($request['zone_ids'][0]) ? 'uuid' : '',
            'category_ids' => $request->has('category_ids') && !is_null($request['category_ids'][0]) ? 'array' : '',
            'category_ids.*' => $request->has('category_ids') && !is_null($request['category_ids'][0]) ? 'uuid' : '',
            'sub_category_ids' => $request->has('sub_category_ids') && !is_null($request['sub_category_ids'][0]) ? 'array' : '',
            'sub_category_ids.*' => $request->has('sub_category_ids') && !is_null($request['sub_category_ids'][0]) ? 'uuid' : '',
            'date_range' => 'in:all_time,this_week,last_week,this_month,last_month,last_15_days,this_year,last_year,last_6_month,this_year_1st_quarter,this_year_2nd_quarter,this_year_3rd_quarter,this_year_4th_quarter,custom_date',
            'from' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'to' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'booking_status' => 'in:' . implode(',', array_column(BOOKING_STATUSES, 'key')) . ',all',

            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        //Dropdown data
        $zones = $this->zone->ofStatus(1)->select('id', 'name')->get();
        $categories = $this->categories->ofType('main')->select('id', 'name')->get();
        $sub_categories = $this->categories->ofType('sub')->select('id', 'name')->get();

        //params
        $search = $request['search'];
        $query_params = ['search' => $search];
        if($request->has('zone_ids')) {
            $query_params['zone_ids'] = $request['zone_ids'];
        }
        if ($request->has('category_ids')) {
            $query_params['category_ids'] = $request['category_ids'];
        }
        if ($request->has('sub_category_ids')) {
            $query_params['sub_category_ids'] = $request['sub_category_ids'];
        }
        if ($request->has('date_range')) {
            $query_params['date_range'] = $request['date_range'];
        }
        if ($request->has('date_range') && $request['date_range'] == 'custom_date') {
            $query_params['from'] = $request['from'];
            $query_params['to'] = $request['to'];
        }

        //** Table Data **
        $filtered_bookings = self::filter_query($this->booking, $request)
            ->with(['customer', 'provider.owner'])
            ->when($request->has('booking_status') && $request['booking_status'] != 'all' , function ($query) use($request) {
                $query->where('booking_status', $request['booking_status']);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->where('readable_id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()->paginate(pagination_limit())
            ->appends($query_params);


        //** Card Data **
        $bookings_for_amount = self::filter_query($this->booking, $request)
            ->with(['customer', 'provider.owner'])
            ->whereIn('booking_status', ['accepted', 'ongoing', 'completed', 'canceled'])
            ->get();

        $bookings_count = [];
        $bookings_count['total_bookings'] = $bookings_for_amount->count();
        $bookings_count['accepted'] = $bookings_for_amount->where('booking_status', 'accepted')->count();
        $bookings_count['ongoing'] = $bookings_for_amount->where('booking_status', 'ongoing')->count();
        $bookings_count['completed'] = $bookings_for_amount->where('booking_status', 'completed')->count();
        $bookings_count['canceled'] = $bookings_for_amount->where('booking_status', 'canceled')->count();

        $booking_amount = [];
        $booking_amount['total_booking_amount'] = $bookings_for_amount->sum('total_booking_amount');
        $booking_amount['total_paid_booking_amount'] = $bookings_for_amount->where('booking_status', 'completed')->where('is_paid', 1)->sum('total_booking_amount');
        $booking_amount['total_unpaid_booking_amount'] = $bookings_for_amount->where('payment_method', '!=', 'cash_after_service')->sum('total_booking_amount');

        //** Chart Data **

        //deterministic
        $date_range = $request['date_range'];
        if(is_null($date_range) || $date_range == 'all_time') {
            $deterministic = 'year';
        } elseif ($date_range == 'this_week' || $date_range == 'last_week') {
            $deterministic = 'week';
        } elseif ($date_range == 'this_month' || $date_range == 'last_month' || $date_range == 'last_15_days') {
            $deterministic = 'day';
        } elseif ($date_range == 'this_year' || $date_range == 'last_year' || $date_range == 'last_6_month' || $date_range == 'this_year_1st_quarter' || $date_range == 'this_year_2nd_quarter' || $date_range == 'this_year_3rd_quarter' || $date_range == 'this_year_4th_quarter') {
            $deterministic = 'month';
        } elseif($date_range == 'custom_date') {
            $from = Carbon::parse($request['from'])->startOfDay();
            $to = Carbon::parse($request['to'])->endOfDay();
            $diff = Carbon::parse($from)->diffInDays($to);

            if($diff <= 7) {
                $deterministic = 'week';
            } elseif ($diff <= 30) {
                $deterministic = 'day';
            } elseif ($diff <= 365) {
                $deterministic = 'month';
            } else {
                $deterministic = 'year';
            }
        }
        $group_by_deterministic = $deterministic=='week'?'day':$deterministic;

        $amounts = $this->booking_details_amount
            ->whereHas('booking', function ($query) use ($request) {
                self::filter_query($query, $request)->whereIn('booking_status', ['accepted', 'ongoing', 'completed', 'canceled']);
            })
            ->when(isset($group_by_deterministic), function ($query) use ($group_by_deterministic) {
                $query->select(
                    DB::raw('sum(admin_commission) as admin_commission'),

                    DB::raw($group_by_deterministic.'(created_at) '.$group_by_deterministic)
                );
            })
            ->groupby($group_by_deterministic)
            ->get()->toArray();

        $bookings = self::filter_query($this->booking, $request)
            ->whereIn('booking_status', ['accepted', 'ongoing', 'completed', 'canceled'])
            ->when(isset($group_by_deterministic), function ($query) use ($group_by_deterministic) {
                $query->select(
                    DB::raw('sum(total_booking_amount) as total_booking_amount'),
                    DB::raw('sum(total_tax_amount) as total_tax_amount'),

                    DB::raw($group_by_deterministic.'(created_at) '.$group_by_deterministic)
                );
            })
            ->groupby($group_by_deterministic)
            ->get()->toArray();

        $chart_data = ['booking_amount'=>array(), 'tax_amount'=>array(), 'admin_commission'=>array(), 'timeline'=>array()];
        //data filter for deterministic
        if($deterministic == 'month') {
            $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            foreach ($months as $month) {
                $found=0;
                $chart_data['timeline'][] = $month;
                foreach ($bookings as $key=>$item) {
                    if ($item['month'] == $month) {
                        $chart_data['booking_amount'][] = $item['total_booking_amount'];
                        $chart_data['tax_amount'][] = $item['total_tax_amount'];

                        $chart_data['admin_commission'][] = $amounts[$key]['admin_commission']??0;
                        $found=1;
                    }
                }
                if(!$found){
                    $chart_data['booking_amount'][] = 0;
                    $chart_data['tax_amount'][] = 0;
                    $chart_data['admin_commission'][] = 0;
                }
            }

        }
        elseif ($deterministic == 'year') {
            foreach ($bookings as $key=>$item) {
                $chart_data['booking_amount'][] = $item['total_booking_amount'];
                $chart_data['tax_amount'][] = $item['total_tax_amount'];
                $chart_data['timeline'][] = $item[$deterministic];

                $chart_data['admin_commission'][] = $amounts[$key]['admin_commission']??0;
            }
        }
        elseif ($deterministic == 'day') {
            if ($date_range == 'this_month') {
                $to = Carbon::now()->lastOfMonth();
            } elseif ($date_range == 'last_month') {
                $to = Carbon::now()->subMonth()->endOfMonth();
            } elseif ($date_range == 'last_15_days') {
                $to = Carbon::now();
            }

            $number = date('d',strtotime($to));

            for ($i = 1; $i <= $number; $i++) {
                $found=0;
                $chart_data['timeline'][] = $i;
                foreach ($bookings as $key=>$item) {
                    if ($item['day'] == $i) {
                        $chart_data['booking_amount'][] = $item['total_booking_amount'];
                        $chart_data['tax_amount'][] = $item['total_tax_amount'];

                        $chart_data['admin_commission'][] = $amounts[$key]['admin_commission']??0;
                        $found=1;
                    }
                }
                if(!$found){
                    $chart_data['booking_amount'][] = 0;
                    $chart_data['tax_amount'][] = 0;
                    $chart_data['admin_commission'][] = 0;
                }
            }
        }
        elseif ($deterministic == 'week') {
            if ($date_range == 'this_week') {
                $from = Carbon::now()->startOfWeek();
                $to = Carbon::now()->endOfWeek();
            } elseif ($date_range == 'last_week') {
                $from = Carbon::now()->subWeek()->startOfWeek();
                $to = Carbon::now()->subWeek()->endOfWeek();
            }

            for ($i = (int)$from->format('d'); $i <= (int)$to->format('d'); $i++) {
                $found=0;
                $chart_data['timeline'][] = $i;
                foreach ($bookings as $key=>$item) {
                    if ($item['day'] == $i) {
                        $chart_data['booking_amount'][] = $item['total_booking_amount'];
                        $chart_data['tax_amount'][] = $item['total_tax_amount'];

                        $chart_data['admin_commission'][] = $amounts[$key]['admin_commission']??0;
                        $found=1;
                    }
                }
                if(!$found) {
                    $chart_data['booking_amount'][] = 0;
                    $chart_data['tax_amount'][] = 0;
                    $chart_data['admin_commission'][] = 0;
                }
            }
        }


        return response()->json(response_formatter(DEFAULT_200, [
            'zones' => $zones,
            'categories' => $categories,
            'sub_categories' => $sub_categories,

            'filtered_bookings' => $filtered_bookings,
            'bookings_count' => $bookings_count,
            'booking_amount' => $booking_amount,
            'chart_data' => $chart_data,
        ]), 200);
    }

    /**
     * Download a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function get_booking_report_download(Request $request): string|StreamedResponse
    {
        Validator::make($request->all(), [
            'zone_ids' => 'array',
            'zone_ids.*' => 'uuid',
            'sub_category_ids' => 'array',
            'sub_category_ids.*' => 'uuid',
            'date_range' => 'in:all_time,this_week,last_week,this_month,last_month,last_15_days,this_year,last_year,last_6_month, this_year_1st_quarter,this_year_2nd_quarter,this_year_3rd_quarter,this_year_4th_quarter,custom_date',
            'from' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'to' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'booking_status' => 'in:' . implode(',', array_column(BOOKING_STATUSES, 'key')) . ',all',
        ]);

        $filtered_bookings = self::filter_query($this->booking, $request)
            ->with(['customer', 'provider.owner', ])
            ->ofBookingStatus('completed')
            ->when($request->has('booking_status'), function ($query) use($request) {
                $query->whereIn('booking_status', $request['booking_status']);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->where('readable_id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()->get();

        return (new FastExcel($filtered_bookings))->download(time().'-booking-report.xlsx', function ($booking) {
            return [
                'Booking ID' => $booking->readable_id,
                'Customer Name' => isset($booking->customer) ? ($booking->customer->first_name . ' ' . $booking->customer->last_name) : '',
                'Customer Phone' => isset($booking->customer) ? ($booking->customer->phone??'') : '',
                'Customer Email' => isset($booking->customer) ? ($booking->customer->email??'') : '',
                'Provider Name' => isset($booking->provider) && isset($booking->provider->owner) ? ($booking->provider->owner->first_name . ' ' . $booking->provider->owner->last_name) : '',
                'Provider Phone' => isset($booking->provider) && isset($booking->provider->owner) ? ($booking->provider->owner->phone??'') : '',
                'Provider Email' => isset($booking->provider) && isset($booking->provider->owner) ? ($booking->provider->owner->email??'') : '',

                'Booking Amount' => with_currency_symbol($booking['total_booking_amount']),
                'Service Discount' => with_currency_symbol($booking['total_discount_amount']),
                'Coupon Discount' => with_currency_symbol($booking['total_coupon_amount']),
                'VAT / Tax' => with_currency_symbol($booking['total_tax_amount']),
            ];
        });
    }

    /**
     * @param $instance
     * @param $request
     * @return mixed
     */
    function filter_query($instance, $request): mixed
    {
        return $instance
            ->where('provider_id', $request->user()->provider->id)
            ->when($request->has('zone_ids'), function ($query) use($request) {
                $query->whereIn('zone_id', $request['zone_ids']);
            })
            ->when($request->has('category_ids'), function ($query) use($request) {
                $query->whereIn('category_id', $request['category_ids']);
            })
            ->when($request->has('sub_category_ids'), function ($query) use($request) {
                $query->whereIn('sub_category_id', $request['sub_category_ids']);
            })
            ->when($request->has('date_range') && $request['date_range'] == 'custom_date', function ($query) use($request) {
                $query->whereBetween('created_at', [Carbon::parse($request['from'])->startOfDay(), Carbon::parse($request['to'])->endOfDay()]);
            })
            ->when($request->has('date_range') && $request['date_range'] != 'custom_date', function ($query) use($request) {
                //DATE RANGE
                if($request['date_range'] == 'this_week') {
                    //this week
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);

                } elseif ($request['date_range'] == 'last_week') {
                    //last week
                    $query->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);

                } elseif ($request['date_range'] == 'this_month') {
                    //this month
                    $query->whereMonth('created_at', Carbon::now()->month);

                } elseif ($request['date_range'] == 'last_month') {
                    //last month
                    $query->whereMonth('created_at', Carbon::now()->subMonth()->month);

                } elseif ($request['date_range'] == 'last_15_days') {
                    //last 15 days
                    $query->whereBetween('created_at', [Carbon::now()->subDay(15), Carbon::now()]);

                } elseif ($request['date_range'] == 'this_year') {
                    //this year
                    $query->whereYear('created_at', Carbon::now()->year);

                } elseif ($request['date_range'] == 'last_year') {
                    //last year
                    $query->whereYear('created_at', Carbon::now()->subYear()->year);

                } elseif ($request['date_range'] == 'last_6_month') {
                    //last 6month
                    $query->whereBetween('created_at', [Carbon::now()->subMonth(6), Carbon::now()]);

                } elseif ($request['date_range'] == 'this_year_1st_quarter') {
                    //this year 1st quarter
                    $query->whereBetween('created_at', [Carbon::now()->month(1)->startOfQuarter(), Carbon::now()->month(1)->endOfQuarter()]);

                } elseif ($request['date_range'] == 'this_year_2nd_quarter') {
                    //this year 2nd quarter
                    $query->whereBetween('created_at', [Carbon::now()->month(4)->startOfQuarter(), Carbon::now()->month(4)->endOfQuarter()]);

                } elseif ($request['date_range'] == 'this_year_3rd_quarter') {
                    //this year 3rd quarter
                    $query->whereBetween('created_at', [Carbon::now()->month(7)->startOfQuarter(), Carbon::now()->month(7)->endOfQuarter()]);

                } elseif ($request['date_range'] == 'this_year_4th_quarter') {
                    //this year 4th quarter
                    $query->whereBetween('created_at', [Carbon::now()->month(10)->startOfQuarter(), Carbon::now()->month(10)->endOfQuarter()]);
                }
            });
    }

}
