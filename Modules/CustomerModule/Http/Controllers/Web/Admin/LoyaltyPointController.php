<?php

namespace Modules\CustomerModule\Http\Controllers\Web\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\TransactionModule\Entities\LoyaltyPointTransaction;
use Modules\UserManagement\Entities\User;
use Modules\ZoneManagement\Entities\Zone;
use Rap2hpoutre\FastExcel\FastExcel;

class LoyaltyPointController extends Controller
{
    protected User $user;
    protected Zone $zone;
    protected LoyaltyPointTransaction $loyalty_point_transaction;

    public function __construct(User $user, Zone $zone, LoyaltyPointTransaction $loyalty_point_transaction)
    {
        $this->user = $user;
        $this->zone = $zone;
        $this->loyalty_point_transaction = $loyalty_point_transaction;
    }

    public function get_loyalty_point_report(Request $request)
    {
        Validator::make($request->all(), [
            'zone_ids' => 'array',
            'zone_ids.*' => 'uuid',
            'date_range' => 'in:all_time,this_week,last_week,this_month,last_month,last_15_days,this_year,last_year,last_6_month,this_year_1st_quarter,this_year_2nd_quarter,this_year_3rd_quarter,this_year_4th_quarter,custom_date',
            'from' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'to' => $request['date_range'] == 'custom_date' ? 'required' : '',

            'transaction_type' => 'in:all,debit,credit'
        ])->validate();

        //Dropdown data
        $zones = $this->zone->select('id', 'name')->get();
        $customers = $this->user->ofType(['customer'])->select('id', 'first_name', 'last_name', 'phone')->get();

        //params
        $query_params = [];
        $transaction_type = $request->has('transaction_type') ? $request->transaction_type : 'all';
        $query_params['transaction_type'] = $transaction_type;
        $search = $request['search'];
        $query_params['search'] = $search;
        if($request->has('zone_ids')) {
            $query_params['zone_ids'] = $request['zone_ids'];
        }
        if($request->has('customer_ids')) {
            $query_params['customer_ids'] = $request['customer_ids'];
        }
        if ($request->has('date_range')) {
            $query_params['date_range'] = $request['date_range'];
        }
        if ($request->has('date_range') && $request['date_range'] == 'custom_date') {
            $query_params['from'] = $request['from'];
            $query_params['to'] = $request['to'];
        }

        //*** Card Data ***
        $total_credit = $this->filter_query($this->loyalty_point_transaction, $request)
            ->with(['user'])
            ->sum('credit');

        $total_debit = $this->filter_query($this->loyalty_point_transaction, $request)
            ->with(['user'])
            ->sum('debit');

        //*** Table Data ***
        $filtered_transactions = $this->filter_query($this->loyalty_point_transaction, $request)
            ->with(['user'])
            ->latest()->paginate(pagination_limit())->appends($query_params);

        return view('customermodule::loyalty-point.report', compact('zones', 'customers', 'filtered_transactions', 'transaction_type', 'total_credit', 'total_debit', 'query_params'));

    }

    /**
     * Display a listing of the resource.
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function get_loyalty_point_report_download(Request $request)
    {
        Validator::make($request->all(), [
            'zone_ids' => 'array',
            'zone_ids.*' => 'uuid',
            'date_range' => 'in:all_time,this_week,last_week,this_month,last_month,last_15_days,this_year,last_year,last_6_month,this_year_1st_quarter,this_year_2nd_quarter,this_year_3rd_quarter,this_year_4th_quarter,custom_date',
            'from' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'to' => $request['date_range'] == 'custom_date' ? 'required' : '',

            'transaction_type' => 'in:all,debit,credit'
        ])->validate();

        //*** Table Data ***
        $filtered_transactions = $this->filter_query($this->loyalty_point_transaction, $request)
            ->with(['user'])
            ->latest()->get();

        return (new FastExcel($filtered_transactions))->download(time().'-provider-report.xlsx', function ($transaction) {
            return [
                'Transaction ID' => $transaction->id,
                'Transaction Date' => date('d-M-Y h:ia',strtotime($transaction->created_at)),
                'Customer Full Name)' => isset($transaction->user) ? $transaction->user->first_name.' '.$transaction->user->last_name : null,
                'Customer phone' => isset($transaction->user) ? $transaction->user->phone : null,
                'Customer email' => isset($transaction->user) ? $transaction->user->email : null,
                'Reference' => $transaction->reference??'',
                'Transaction Type' => $transaction->transaction_type??'',
                'Debit' => with_currency_symbol($transaction->debit),
                'Credit' => with_currency_symbol($transaction->credit),
                'Balance' => with_currency_symbol($transaction->balance),
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
            ->when($request->has('transaction_type') && $request['transaction_type'] != 'all', function ($query) use($request) {
                if ($request['transaction_type'] == 'debit') {
                    $query->where('debit', '!=', 0);
                } elseif ($request['transaction_type'] == 'credit') {
                    $query->where('credit', '!=', 0);
                }
            })
            ->when($request->has('zone_ids'), function ($query) use ($request) {
                $query->whereHas('user.zones', function ($query) use ($request) {
                    $query->whereIn('zone_id', $request['zone_ids']);
                })->orWhereHas('user.zones', function ($query) use ($request) {
                    $query->whereIn('zone_id', $request['zone_ids']);
                });
            })
            ->when($request->has('customer_ids'), function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->whereIn('id', $request['customer_ids']);
                })->orWhereHas('user', function ($query) use ($request) {
                    $query->whereIn('id', $request['customer_ids']);
                });
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
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->whereHas('user', function ($query) use ($request, $keys) {
                    foreach ($keys as $key) {
                        $query->where('first_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('phone', 'LIKE', '%' . $key . '%');
                    }
                })->orWhereHas('user', function ($query) use ($request, $keys) {
                    foreach ($keys as $key) {
                        $query->where('first_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('phone', 'LIKE', '%' . $key . '%');
                    }
                });
            });
    }
}
