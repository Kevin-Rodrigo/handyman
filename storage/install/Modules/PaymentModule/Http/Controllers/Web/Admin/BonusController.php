<?php

namespace Modules\PaymentModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Modules\PaymentModule\Entities\Bonus;
use Rap2hpoutre\FastExcel\FastExcel;

class BonusController extends Controller
{
    protected Bonus $bonus;

    public function __construct(Bonus $bonus)
    {
        $this->bonus = $bonus;
    }


    //*** WITHDRAW METHOD RELATED FUNCTIONS ***

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function list(Request $request): Renderable
    {
        $request->validate([
            'status' => 'in:active,inactive,all',
        ]);
        $search = $request->has('search') ? $request['search'] : '';
        $status = $request->has('status') ? $request['status'] : 'all';
        $query_param = ['search' => $search, 'status' => $status];

        $bonuses = $this->bonus
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->where('bonus_title', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($status != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })
            ->latest()->paginate(pagination_limit())->appends($query_param);

        return View('paymentmodule::admin.bonus.list', compact('bonuses', 'status', 'search'));
    }

    /**
     * Create resource.
     * @return Renderable
     */
    public function create(): Renderable
    {
        $type = 'offline_payment';
        return View('paymentmodule::admin.bonus.create', compact('type'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'bonus_title' => 'required|string',
            'short_description' => 'required|string',
            'bonus_amount_type' => 'required|in:percent,amount',
            'bonus_amount' => [
                'required', 'gt:0',
                function ($attribute, $value, $fail) use ($request) {
                    $amountType = $request->input('amount_type');
                    if ($amountType === 'percent' && $value > 100) {
                        $fail('The bonus amount percent value must be less than or equal 100 ');
                    }
                },
            ],
            'minimum_add_amount' => 'required|numeric|min:0',
            'maximum_bonus_amount' => $request['bonus_amount_type'] == 'percent' ? 'required|numeric|gt:0' : '',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $bonus = $this->bonus;
        $bonus->bonus_title = $request['bonus_title'];
        $bonus->short_description = $request['short_description'];
        $bonus->bonus_amount_type = $request['bonus_amount_type'];
        $bonus->bonus_amount = $request['bonus_amount'];
        $bonus->minimum_add_amount = $request['minimum_add_amount'];
        $bonus->maximum_bonus_amount = $request['bonus_amount_type'] == 'percent' ? $request['maximum_bonus_amount'] : 0;
        $bonus->start_date = $request['start_date'];
        $bonus->end_date = $request['end_date'];
        $bonus->save();

        Toastr::success(DEFAULT_STORE_200['message']);
        return redirect()->route('admin.bonus.list');
    }

    /**
     * Edit resource.
     * @param $id
     * @return Renderable
     */
    public function edit($id): Renderable
    {
        $bonus = $this->bonus->find($id);
        return View('paymentmodule::admin.bonus.edit', compact('bonus'));
    }

    /**
     * Update resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bonus_title' => 'required|string',
            'short_description' => 'required|string',
            'bonus_amount_type' => 'required|in:percent,amount',
            'bonus_amount' => [
                'required', 'gt:0',
                function ($attribute, $value, $fail) use ($request) {
                    $amountType = $request->input('amount_type');
                    if ($amountType === 'percent' && $value > 100) {
                        $fail('The bonus amount percent value must be less than or equal 100 ');
                    }
                },
            ],
            'minimum_add_amount' => 'required|numeric|min:0',
            'maximum_bonus_amount' => $request['bonus_amount_type'] == 'percent' ? 'required|numeric|gt:0' : '',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $bonus = $this->bonus->find($id);

        if(!isset($bonus)) {
            Toastr::error(DEFAULT_404['message']);
            return back();
        }

        $bonus->bonus_title = $request['bonus_title'];
        $bonus->short_description = $request['short_description'];
        $bonus->bonus_amount_type = $request['bonus_amount_type'];
        $bonus->bonus_amount = $request['bonus_amount'];
        $bonus->minimum_add_amount = $request['minimum_add_amount'];
        $bonus->maximum_bonus_amount = $request['bonus_amount_type'] == 'percent' ? $request['maximum_bonus_amount'] : 0;
        $bonus->start_date = $request['start_date'];
        $bonus->end_date = $request['end_date'];
        $bonus->save();

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    /**
     * Destroy resource.
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $this->bonus->where('id', $id)->delete();
        Toastr::success(DEFAULT_DELETE_200['message']);
        return back();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function status_update(Request $request, $id): JsonResponse
    {
        $bonus = $this->bonus->where('id', $id)->first();
        $this->bonus->where('id', $id)->update(['is_active' => !$bonus->is_active]);
        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }

        /**
     * Display a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     */
    public function download(Request $request): string|StreamedResponse
    {
        $items = $this->bonus
        ->when($request->has('search'), function ($query) use ($request) {
            $keys = explode(' ', $request['search']);
            return $query->where(function ($query) use ($keys) {
                foreach ($keys as $key) {
                    $query->where('bonus_title', 'LIKE', '%' . $key . '%');
                }
            });
        })
        ->latest()->get();

        return (new FastExcel($items))->download(time().'-file.xlsx');
    }

}
