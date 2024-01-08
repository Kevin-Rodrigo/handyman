<?php

namespace Modules\ProviderManagement\Http\Controllers\Web\Provider\Report;

use Auth;
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
use Symfony\Component\HttpFoundation\StreamedResponse;
use function pagination_limit;
use function view;
use function with_currency_symbol;

class TransactionReportController extends Controller
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
    public function get_transaction_report(Request $request)
    {
        Validator::make($request->all(), [
//            'zone_ids' => 'array',
//            'zone_ids.*' => 'uuid',
            'date_range' => 'in:all_time, this_week, last_week, this_month, last_month, last_15_days, this_year, last_year, last_6_month, this_year_1st_quarter, this_year_2nd_quarter, this_year_3rd_quarter, this_year_4th_quarter, custom_date',
            'from' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'to' => $request['date_range'] == 'custom_date' ? 'required' : '',

            'transaction_type' => 'in:all,debit,credit'
        ]);

        //Dropdown data
        $zones = $this->zone->select('id', 'name')->get();

        //params
        $query_params = [];
        $transaction_type = $request->has('transaction_type') ? $request->transaction_type : 'all';
        $query_params['transaction_type'] = $transaction_type;
        $search = $request['search'];
        $query_params['search'] = $search;
        if($request->has('zone_ids')) {
            $query_params['zone_ids'] = $request['zone_ids'];
        }
        if ($request->has('date_range')) {
            $query_params['date_range'] = $request['date_range'];
        }
        if ($request->has('date_range') && $request['date_range'] == 'custom_date') {
            $query_params['from'] = $request['from'];
            $query_params['to'] = $request['to'];
        }

        $filtered_transactions = $this->transaction
            ->with(['booking', 'from_user.provider', 'to_user.provider'])
            ->when($request->has('transaction_type') && $request->transaction_type != 'all', function ($query) use($request) {
                if ($request->transaction_type == 'debit') {
                    $query->where('debit', '!=', 0);
                } elseif ($request->transaction_type == 'credit') {
                    $query->where('credit', '!=', 0);
                }
            })
//            ->when($request->has('zone_ids'), function ($query) use ($request) {
//                $query->whereHas('booking', function ($query) use ($request) {
//                    $query->whereIn('zone_id', $request['zone_ids']);
//                })->orWhereHas('to_user.provider', function ($query) use ($request) {
//                    $query->whereIn('zone_id', $request['zone_ids']);
//                })->orWhereHas('from_user.provider', function ($query) use ($request) {
//                    $query->whereIn('zone_id', $request['zone_ids']);
//                });
//            })
            ->where(function ($query) {
                $query->where('to_user_id', Auth::user()->id)->orWhere('from_user_id', Auth::user()->id);
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
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->where('id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()->paginate(pagination_limit())->appends($query_params);

        $account_info = Account::where('user_id', Auth::user()->id)->first();
        return view('providermanagement::provider.report.transaction', compact('zones', 'filtered_transactions', 'transaction_type', 'account_info', 'query_params'));
    }

    /**
     * Download a listing of the resource.
     * @param Request $request
     * @return StreamedResponse|string
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function download_transaction_report(Request $request): StreamedResponse|string
    {
        Validator::make($request->all(), [
//            'zone_ids' => 'array',
//            'zone_ids.*' => 'uuid',
            'date_range' => 'in:all_time, this_week, last_week, this_month, last_month, last_15_days, this_year, last_year, last_6_month, this_year_1st_quarter, this_year_2nd_quarter, this_year_3rd_quarter, this_year_4th_quarter, custom_date',
            'from' => $request['date_range'] == 'custom_date' ? 'required' : '',
            'to' => $request['date_range'] == 'custom_date' ? 'required' : '',

            'transaction_type' => 'in:all,debit,credit'
        ]);

        $filtered_transactions = $this->transaction
            ->with(['booking', 'from_user.provider', 'to_user.provider'])
            ->when($request->has('transaction_type') && $request->transaction_type != 'all', function ($query) use($request) {
                if ($request->transaction_type == 'debit') {
                    $query->where('debit', '!=', 0);
                } elseif ($request->transaction_type == 'credit') {
                    $query->where('credit', '!=', 0);
                }
            })
//            ->when($request->has('zone_ids'), function ($query) use ($request) {
//                $query->whereHas('booking', function ($query) use ($request) {
//                    $query->whereIn('zone_id', $request['zone_ids']);
//                })->orWhereHas('to_user.provider', function ($query) use ($request) {
//                    $query->whereIn('zone_id', $request['zone_ids']);
//                })->orWhereHas('from_user.provider', function ($query) use ($request) {
//                    $query->whereIn('zone_id', $request['zone_ids']);
//                });
//            })
            ->whereHas('to_user.provider', function ($query) use ($request) {
                $query->where('id', $request->user()->provider->id);
            })->orWhereHas('from_user.provider', function ($query) use ($request) {
                $query->where('id', $request->user()->provider->id);
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
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->where('id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()->get();

        return (new FastExcel($filtered_transactions))->download(time().'-provider-report.xlsx', function ($transaction) {
            return [
                'Transaction ID' => $transaction->id,
                'Transaction Date' => date('d-M-Y h:ia',strtotime($transaction->created_at)),
                'Transaction To (full name)' => isset($transaction->to_user) ? $transaction->to_user->first_name.' '.$transaction->to_user->last_name : null,
                'Transaction To (phone)' => isset($transaction->to_user) ? $transaction->to_user->phone : null,
                'Transaction To (email)' => isset($transaction->to_user) ? $transaction->to_user->email : null,
                'Debit' => with_currency_symbol($transaction->debit),
                'Credit' => with_currency_symbol($transaction->credit),
                'Transactional Balance' => with_currency_symbol($transaction->balance),
            ];
        });
    }

}
