<?php

namespace Modules\ServicemanModule\Http\Controllers\Web\Provider;

use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\BookingModule\Entities\BookingDetailsAmount;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\User;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServicemanController extends Controller
{
    private User $employee;
    private User $serviceman_user;
    private Serviceman $serviceman;
    private Booking $booking;
    private BookingDetailsAmount $booking_details_amount;

    public function __construct(Serviceman $serviceman, User $serviceman_user, User $employee, Booking $booking, BookingDetailsAmount $booking_details_amount)
    {
        $this->serviceman = $serviceman;
        $this->employee = $employee;
        $this->serviceman_user = $serviceman_user;
        $this->booking = $booking;
        $this->booking_details_amount = $booking_details_amount;
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $request->validate([
            'status' => 'in:active,inactive,all',
        ]);

        $search = $request->has('search') ? $request['search'] : '';
        $status = $request->has('status') ? $request['status'] : 'all';
        $query_param = ['status' => $status, 'search' => $search];

        $servicemen = $this->serviceman_user->with(['serviceman'])
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $keys = explode(' ', $request['search']);
                    foreach ($keys as $key) {
                        $query->orWhere('first_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('email', 'LIKE', '%' . $key . '%')
                            ->orWhere('phone', 'LIKE', '%' . $key . '%')
                            ->orWhere('identification_number', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request['status'] != 'all', function ($query) use ($request) {
                $query->where('is_active', ($request['status'] == 'active') ? 1 : 0);
            })
            ->whereHas('serviceman', function ($query) use ($request) {
                $query->where('provider_id', $request->user()->provider->id);
            })
            ->where(['user_type' => 'provider-serviceman'])
            ->latest()
            ->paginate(pagination_limit())->appends($query_param);

        return view('servicemanmodule::Provider.Serviceman.list', compact('servicemen', 'search', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function create(Request $request): Renderable
    {
        return view('servicemanmodule::Provider.Serviceman.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'profile_image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000',
            'identity_type' => 'required|in:passport,driving_license,company_id,nid,trade_license',
            'identity_number' => 'required',
            'identity_image' => 'required|array',
            'identity_image.*' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        if (!$request->has('identity_image') || count($request->identity_image) < 1) {
            Toastr::error(translate('Identification_image_is_required'));
            return back();
        }

        $identity_images = [];
        foreach ($request->identity_image as $image) {
            $identity_images[] = file_uploader('serviceman/identity/', 'png', $image);
        }


        DB::transaction(function () use ($request, $identity_images) {
            $employee = $this->employee;
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->profile_image = file_uploader('serviceman/profile/', 'png', $request->file('profile_image'));
            $employee->identification_number = $request->identity_number;
            $employee->identification_type = $request->identity_type;
            $employee->identification_image = $identity_images;
            $employee->password = bcrypt($request->password);
            $employee->user_type = 'provider-serviceman';
            $employee->is_active = 1;
            $employee->save();

            $serviceman = $this->serviceman;
            $serviceman->provider_id = $request->user()->provider->id;
            $serviceman->user_id = $employee->id;
            $serviceman->save();
        });

        Toastr::success(SERVICE_STORE_200['message']);
        return back();
    }

    /**
     * Show the specified resource.
     * @param string $id
     */
    public function show(Request $request, string $id)
    {
        Validator::make($request->all(), [
            'date_range' => 'in:all_time,this_week,last_week,this_month,last_month,last_15_days,this_year,last_year,last_6_month,this_year_1st_quarter,this_year_2nd_quarter,this_year_3rd_quarter,this_year_4th_quarter,custom_date',
        ])->validate();

        $serviceman = $this->serviceman::with(['user.addresses'])
            ->withCount(['bookings as total_ongoing_bookings' => function ($query) use ($request) {
                self::filter_query($query, $request)->where('booking_status', 'ongoing');
            }])
            ->withCount(['bookings as total_completed_bookings' => function ($query) use ($request) {
                self::filter_query($query, $request)->where('booking_status', 'completed');
            }])
            ->withCount(['bookings as total_canceled_bookings' => function ($query) use ($request) {
                self::filter_query($query, $request)->where('booking_status', 'canceled');
            }])
            ->find($id);

        $total_assigned_bookings = self::filter_query($this->booking, $request)->where('serviceman_id', $id)->count();

        if(!isset($serviceman)) {
            Toastr::error(DEFAULT_404['message']);
            return back();
        }

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

        $bookings = $this->booking
            //->ofBookingStatus('completed')
            ->where('serviceman_id', $id)
            ->when($request->has('zone_ids'), function ($query) use($request) {
                $query->whereIn('zone_id', $request['zone_ids']);
            })
            ->when($request->has('category_ids'), function ($query) use($request) {
                $query->whereIn('category_id', $request['category_ids']);
            })
            ->when($request->has('sub_category_ids'), function ($query) use($request) {
                $query->whereIn('sub_category_id', $request['sub_category_ids']);
            })
            ->when($request->has('booking_status'), function ($query) use($request) {
                $query->whereIn('booking_status', $request['booking_status']);
            })
            ->when($request->has('date_range') && $request['date_range'] == 'custom_date', function ($query) use($request) {
                $query->whereBetween('created_at', [date($request['from']), date($request['to'])]);
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
            })

            ->when(isset($group_by_deterministic), function ($query) use ($group_by_deterministic) {
                $query->select(
                    DB::raw('count(id) as total_booking'),
                    DB::raw($group_by_deterministic.'(created_at) '.$group_by_deterministic)
                );
            })
            ->groupby($group_by_deterministic)
            ->get()->toArray();

        $chart_data = ['total_booking'=>array(), 'timeline'=>array()];
        //data filter for deterministic
        if($deterministic == 'month') {
            $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            foreach ($months as $month) {
                $found=0;
                $chart_data['timeline'][] = $month;
                foreach ($bookings as $key=>$item) {
                    if ($item['month'] == $month) {
                        $chart_data['total_booking'][] = $item['total_booking'];
                        $found=1;
                    }
                }
                if(!$found){
                    $chart_data['total_booking'][] = 0;
                }
            }

        }
        elseif ($deterministic == 'year') {
            foreach ($bookings as $key=>$item) {
                $chart_data['total_booking'][] = $item['total_booking'];
                $chart_data['timeline'][] = $item[$deterministic];
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
                        $chart_data['total_booking'][] = $item['total_booking'];
                        $found=1;
                    }
                }
                if(!$found){
                    $chart_data['total_booking'][] = 0;
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
                        $chart_data['total_booking'][] = $item['total_booking'];
                        $found=1;
                    }
                }
                if(!$found) {
                    $chart_data['total_booking'][] = 0;
                }
            }
        }

        return view('servicemanmodule::Provider.Serviceman.details', compact('serviceman', 'chart_data', 'date_range', 'total_assigned_bookings'));
    }

    /**
     * Show the specified resource.
     * @param string $id
     * @return Application|Factory|View
     */
    public function edit(string $id): Application|Factory|View
    {
        $serviceman = $this->serviceman::with(['user'])->find($id);
        return view('servicemanmodule::Provider.Serviceman.edit', compact('serviceman'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $employee = $this->employee::whereHas('serviceman', function ($query) use ($id) {
            $query->where(['id' => $id]);
        })->first();

        if (!isset($employee)) {
            Toastr::error(translate('you_can _not_change_this_user_info'));
            return back();
        }

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:users,phone,' . $employee->id,
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'password' => '',
            'confirm_password' => !is_null($request->password) ? 'required|min:8|same:password' : '',
            'profile_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'identity_type' => 'in:passport,driving_license,company_id,nid,trade_license',
            'identity_number' => 'required',
            'identity_image' => 'array',
            'identity_image.*' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        //$identity_images = (array)$employee->identification_image;
        $identity_images = [];
        if ($request->has('identity_image')) {
            foreach ($request['identity_image'] as $image) {
                $identity_images[] = file_uploader('serviceman/identity/', 'png', $image);
            }
        }

        DB::transaction(function () use ($request, $identity_images, $employee) {
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            if ($request->has('profile_image')) {
                $employee->profile_image = file_uploader('serviceman/profile/', 'png', $request->file('profile_image'));
            }
            $employee->identification_number = $request->identity_number;
            $employee->identification_type = $request->identity_type;
            if(count($identity_images)) {
                $employee->identification_image = $identity_images;
            }
            if (!is_null($request->password)) {
                $employee->password = bcrypt($request->password);
            }
            $employee->user_type = 'provider-serviceman';
            $employee->save();
        });

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
        $serviceman = $this->serviceman->find($id);
        $serviceman->delete();

        Toastr::success(DEFAULT_DELETE_200['message']);
        return redirect(route('provider.serviceman.list', ['status'=>'all']));
    }

    /**
     * * Bulk status update
     * @param Request $request
     * @return JsonResponse
     */


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function status_update(Request $request, $id): JsonResponse
    {
        $serviceman = $this->employee->where('id', $id)->first();
        $this->employee->where('id', $id)->update(['is_active' => !$serviceman->is_active]);

        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     */
    public function download(Request $request): string|StreamedResponse
    {
        $request->validate([
            'status' => 'in:active,inactive,all',
        ]);

        $items = $this->serviceman_user->with(['serviceman'])
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $keys = explode(' ', $request['search']);
                    foreach ($keys as $key) {
                        $query->orWhere('first_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('email', 'LIKE', '%' . $key . '%')
                            ->orWhere('phone', 'LIKE', '%' . $key . '%')
                            ->orWhere('identification_number', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request['status'] != 'all', function ($query) use ($request) {
                $query->where('is_active', ($request['status'] == 'active') ? 1 : 0);
            })
            ->whereHas('serviceman', function ($query) use ($request) {
                $query->where('provider_id', $request->user()->provider->id);
            })
            ->where(['user_type' => 'provider-serviceman'])
            ->latest()
            ->get();

        return (new FastExcel($items))->download(time() . '-file.xlsx');
    }

    /**
     * @param $instance
     * @param $request
     * @return mixed
     */
    function filter_query($instance, $request): mixed
    {
        return $instance
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
