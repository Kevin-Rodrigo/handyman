<?php

namespace Modules\AdminModule\Http\Controllers\Web\Admin\Report\Business;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
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

class OverviewReportController extends Controller
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
     * @return Renderable
     */
    public function get_business_overview_report(Request $request)
    {
        Validator::make($request->all(), [
            'zone_ids' => 'array',
            'zone_ids.*' => 'uuid',
            'category_ids' => 'array',
            'category_ids.*' => 'uuid',
            'sub_category_ids' => 'array',
            'sub_category_ids.*' => 'uuid',
            'date_range' => 'in:all_time, this_week, last_week, this_month, last_month, last_15_days, this_year, last_year, last_6_month, this_year_1st_quarter, this_year_2nd_quarter, this_year_3rd_quarter, this_year_4th_quarter, custom_date',
            'from' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'to' => $request['date_range'] == 'custom_date' ? 'required' : '',
        ]);

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
                $query->ofBookingStatus('completed')
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
                            $query->whereMonth('created_at', Carbon::now()->month-1);

                        } elseif ($request['date_range'] == 'last_15_days') {
                            //last 15 days
                            $query->whereBetween('created_at', [Carbon::now()->subDay(15), Carbon::now()]);

                        } elseif ($request['date_range'] == 'this_year') {
                            //this year
                            $query->whereYear('created_at', Carbon::now()->year);

                        } elseif ($request['date_range'] == 'last_year') {
                            //last year
                            $query->whereYear('created_at', Carbon::now()->year-1);

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
            })
            ->when(isset($group_by_deterministic), function ($query) use ($group_by_deterministic) {
                $query->select(
                    DB::raw('sum(service_unit_cost) as service_unit_cost'),
                    DB::raw('sum(discount_by_admin) as discount_by_admin'),
                    DB::raw('sum(discount_by_provider) as discount_by_provider'),
                    DB::raw('sum(coupon_discount_by_admin) as coupon_discount_by_admin'),
                    DB::raw('sum(coupon_discount_by_provider) as coupon_discount_by_provider'),
                    DB::raw('sum(campaign_discount_by_admin) as campaign_discount_by_admin'),
                    DB::raw('sum(campaign_discount_by_provider) as campaign_discount_by_provider'),
                    DB::raw('sum(admin_commission) as admin_commission'),

                    DB::raw($group_by_deterministic.'(created_at) '.$group_by_deterministic)
                );
            })
            ->groupby($group_by_deterministic)
            ->get()->toArray();


        $chart_data = ['earnings'=>array(), 'expenses'=>array(), 'timeline'=>array()];
        $total_promotional_cost = ['discount' => 0, 'coupon' => 0, 'campaign' => 0];

        //data filter for deterministic
        if($deterministic == 'month') {
            $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            foreach ($months as $month) {
                $found=0;
                $chart_data['timeline'][] = $month;
                foreach ($amounts as $item) {
                    if ($item['month'] == $month) {
                        $chart_data['earnings'][] = with_decimal_point($item['admin_commission']);
                        $chart_data['expenses'][] = with_decimal_point($item['discount_by_admin'] + $item['coupon_discount_by_admin'] + $item['campaign_discount_by_admin']);
                        $found=1;
                    }
                }
                if(!$found){
                    $chart_data['earnings'][] = with_decimal_point(0);
                    $chart_data['expenses'][] = with_decimal_point(0);
                }

                $total_promotional_cost['discount'] += $item['discount_by_admin']??0;
                $total_promotional_cost['coupon'] += $item['coupon_discount_by_admin']??0;
                $total_promotional_cost['campaign'] += $item['campaign_discount_by_admin']??0;
            }

        }
        elseif ($deterministic == 'year') {
            foreach ($amounts as $item) {
                $chart_data['earnings'][] = with_decimal_point($item['admin_commission']);
                $chart_data['expenses'][] = with_decimal_point($item['discount_by_admin'] + $item['coupon_discount_by_admin'] + $item['campaign_discount_by_admin']);
                $chart_data['timeline'][] = $item[$deterministic];

                $total_promotional_cost['discount'] += $item['discount_by_admin']??0;
                $total_promotional_cost['coupon'] += $item['coupon_discount_by_admin']??0;
                $total_promotional_cost['campaign'] += $item['campaign_discount_by_admin']??0;
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
                foreach ($amounts as $item) {
                    if ($item['day'] == $i) {
                        $chart_data['earnings'][] = with_decimal_point($item['admin_commission']);
                        $chart_data['expenses'][] = with_decimal_point($item['discount_by_admin'] + $item['coupon_discount_by_admin'] + $item['campaign_discount_by_admin']);
                        $found=1;
                    }
                }
                if(!$found){
                    $chart_data['earnings'][] = with_decimal_point(0);
                    $chart_data['expenses'][] = with_decimal_point(0);
                }

                $total_promotional_cost['discount'] += $item['discount_by_admin']??0;
                $total_promotional_cost['coupon'] += $item['coupon_discount_by_admin']??0;
                $total_promotional_cost['campaign'] += $item['campaign_discount_by_admin']??0;
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
                foreach ($amounts as $item) {
                    if ($item['day'] == $i) {
                        $chart_data['earnings'][] = with_decimal_point($item['admin_commission']);
                        $chart_data['expenses'][] = with_decimal_point($item['discount_by_admin'] + $item['coupon_discount_by_admin'] + $item['campaign_discount_by_admin']);
                        $found=1;
                    }
                }
                if(!$found){
                    $chart_data['earnings'][] = with_decimal_point(0);
                    $chart_data['expenses'][] = with_decimal_point(0);
                }

                $total_promotional_cost['discount'] += $item['discount_by_admin']??0;
                $total_promotional_cost['coupon'] += $item['coupon_discount_by_admin']??0;
                $total_promotional_cost['campaign'] += $item['campaign_discount_by_admin']??0;
            }
        }

        return view('adminmodule::admin.report.business.overview', compact('zones', 'categories', 'sub_categories', 'amounts', 'chart_data', 'total_promotional_cost', 'deterministic', 'query_params'));
    }

    public function get_business_overview_report_download(Request $request)
    {
        Validator::make($request->all(), [
            'zone_ids' => 'array',
            'zone_ids.*' => 'uuid',
            'category_ids' => 'array',
            'category_ids.*' => 'uuid',
            'sub_category_ids' => 'array',
            'sub_category_ids.*' => 'uuid',
            'date_range' => 'in:all_time, this_week, last_week, this_month, last_month, last_15_days, this_year, last_year, last_6_month, this_year_1st_quarter, this_year_2nd_quarter, this_year_3rd_quarter, this_year_4th_quarter, custom_date',
            'from' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'to' => $request['date_range'] == 'custom_date' ? 'required' : '',
        ]);

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
                $query->ofBookingStatus('completed')
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
                            $query->whereMonth('created_at', Carbon::now()->month-1);

                        } elseif ($request['date_range'] == 'last_15_days') {
                            //last 15 days
                            $query->whereBetween('created_at', [Carbon::now()->subDay(15), Carbon::now()]);

                        } elseif ($request['date_range'] == 'this_year') {
                            //this year
                            $query->whereYear('created_at', Carbon::now()->year);

                        } elseif ($request['date_range'] == 'last_year') {
                            //last year
                            $query->whereYear('created_at', Carbon::now()->year-1);

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
            })
            ->when(isset($group_by_deterministic), function ($query) use ($group_by_deterministic) {
                $query->select(
                    DB::raw('sum(service_unit_cost) as service_unit_cost'),
                    DB::raw('sum(discount_by_admin) as discount_by_admin'),
                    DB::raw('sum(discount_by_provider) as discount_by_provider'),
                    DB::raw('sum(coupon_discount_by_admin) as coupon_discount_by_admin'),
                    DB::raw('sum(coupon_discount_by_provider) as coupon_discount_by_provider'),
                    DB::raw('sum(campaign_discount_by_admin) as campaign_discount_by_admin'),
                    DB::raw('sum(campaign_discount_by_provider) as campaign_discount_by_provider'),
                    DB::raw('sum(admin_commission) as admin_commission'),

                    DB::raw($group_by_deterministic.'(created_at) '.$group_by_deterministic)
                );
            })
            ->groupby($group_by_deterministic)
            ->get();

        foreach ($amounts as $amount) {
            $total_earning = $amount['admin_commission'];
            $total_expense = $amount['discount_by_admin'] + $amount['coupon_discount_by_admin'] + $amount['campaign_discount_by_admin'];

            $net_profit = $total_earning-$total_expense;
            $net_profit_rate = $total_earning!=0 ? ($net_profit*100)/$total_earning : $net_profit*100;


            //set to amount object
            $amount->total_expense = $total_expense;
            $amount->net_profit = $net_profit;
            $amount->net_profit_rate = $net_profit_rate;
        }

        return (new FastExcel($amounts))->download(time().'-business-overview-report.xlsx', function ($item) use ($deterministic) {
            return [
                $deterministic => $item[$deterministic]??'',
                'Commission Earnings ('.currency_symbol().')' => with_decimal_point($item['admin_commission']),
                'Total Expenses ('.currency_symbol().')' => with_decimal_point($item['total_expense']),
                'Net Profit ('.currency_symbol().')' => with_decimal_point($item['net_profit']),
                'Net Profit Rate (%)' => with_decimal_point($item['net_profit_rate']),
            ];
        });
    }
}
