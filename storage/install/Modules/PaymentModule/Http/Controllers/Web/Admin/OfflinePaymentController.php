<?php

namespace Modules\PaymentModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\PaymentModule\Entities\OfflinePayment;

class OfflinePaymentController extends Controller
{
    protected OfflinePayment $offline_payment;

    public function __construct(OfflinePayment $offline_payment)
    {
        $this->offline_payment = $offline_payment;
    }


    //*** WITHDRAW METHOD RELATED FUNCTIONS ***

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Renderable
     */
    public function method_list(Request $request): Renderable
    {
        Validator::make($request->all(), [
            'search' => 'max:255',
            'body' => 'required',
        ]);

        $withdrawal_methods = $this->offline_payment
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->where('method_name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->paginate(pagination_limit());
        $status = null;
        $search = $request['search'];
        $type = 'offline_payment';
        return View('paymentmodule::admin.offline-payments.list', compact('withdrawal_methods', 'status', 'search','type'));
    }

    /**
     * Create resource.
     * @return Renderable
     */
    public function method_create(): Renderable
    {
        $type = 'offline_payment';
        return View('paymentmodule::admin.offline-payments.create', compact('type'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function method_store(Request $request): RedirectResponse
    {
        $request->validate([
            'method_name' => 'required',
            'data' => 'required|array',
            'title' => 'required|array',
            'field_name' => 'required|array',
            'placeholder' => 'required|array',
            'is_required' => '',
        ]);

        //payment note for all
        $customer_information [] = [
            'field_name' => 'payment_note',
            'placeholder' => 'payment_note',
            'is_required' => 0
        ];

        foreach ($request->field_name as $key=>$field_name) {
            $customer_information[] = [
                'field_name' => strtolower(str_replace(' ', "_", $request->field_name[$key])),
                'placeholder' => $request->placeholder[$key],
                'is_required' => isset($request['is_required']) && isset($request['is_required'][$key]) ? 1 : 0,
            ];
        }

        $payment_information = [];
        foreach ($request->data as $key=>$data) {
            $payment_information[] = [
                'title' => strtolower(str_replace(' ', "_", $request->title[$key])),
                'data' => $request->data[$key],
            ];
        }

        $offline_payment_object = $this->offline_payment->updateOrCreate(
            ['method_name' => $request->method_name],
            [
            'customer_information' => $customer_information,
            'payment_information' => $payment_information
            ]
        );

        Toastr::success(DEFAULT_STORE_200['message']);
        return back();
    }

    /**
     * Edit resource.
     * @param $id
     * @return Renderable
     */
    public function method_edit($id): Renderable
    {
        $withdrawal_method = $this->offline_payment->find($id);
        $type = 'offline_payment';
        return View('paymentmodule::admin.offline-payments.edit', compact('withdrawal_method', 'type'));
    }

    /**
     * Update resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function method_update(Request $request)
    {
        $request->validate([
            'method_name' => 'required',
            'data' => 'required|array',
            'title' => 'required|array',
            'field_name' => 'required|array',
            'placeholder' => 'required|array',
            'is_required' => '',
        ]);

        $withdrawal_method = $this->offline_payment->find($request['id']);

        if(!isset($withdrawal_method)) {
            Toastr::error(DEFAULT_404['message']);
            return back();
        }

        //payment note for all
        $customer_information [] = [
            'field_name' => 'payment_note',
            'placeholder' => 'payment_note',
            'is_required' => 0
        ];

        foreach ($request->field_name as $key=>$field_name) {
            $customer_information[] = [
                'field_name' => strtolower(str_replace(' ', "_", $request->field_name[$key])),
                'placeholder' => $request->placeholder[$key],
                'is_required' => isset($request['is_required']) && isset($request['is_required'][$key]) ? $request['is_required'][$key] : 0,
            ];
        }

        $payment_information = [];
        foreach ($request->data as $key=>$data) {
            $payment_information[] = [
                'title' => strtolower(str_replace(' ', "_", $request->title[$key])),
                'data' => $request->data[$key],
            ];
        }

        $offline_payment_object = $this->offline_payment->updateOrCreate(
            ['method_name' => $request->method_name],
            [
            'customer_information' => $customer_information,
            'payment_information' => $payment_information
            ]
        );

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    /**
     * Destroy resource.
     * @param $id
     * @return RedirectResponse
     */
    public function method_destroy($id): RedirectResponse
    {
        $this->offline_payment->where('id', $id)->delete();
        Toastr::success(DEFAULT_DELETE_200['message']);
        return back();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function method_status_update(Request $request, $id): JsonResponse
    {
        $offline_payment = $this->offline_payment->where('id', $id)->first();
        $this->offline_payment->where('id', $id)->update(['is_active' => !$offline_payment->is_active]);
        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }

}
