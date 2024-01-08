<?php

namespace Modules\TransactionModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ProviderManagement\Entities\WithdrawRequest;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\TransactionModule\Entities\WithdrawalMethod;
use Modules\UserManagement\Entities\User;

class WithdrawnController extends Controller
{
    protected User $user;
    protected Account $account;
    protected WithdrawRequest $withdraw_request;
    protected Transaction $transaction;
    protected WithdrawalMethod $withdrawal_method;

    public function __construct(User $user, Account $account, WithdrawRequest $withdraw_request, Transaction $transaction, WithdrawalMethod $withdrawal_method)
    {
        $this->user = $user;
        $this->account = $account;
        $this->withdraw_request = $withdraw_request;
        $this->transaction = $transaction;
        $this->withdrawal_method = $withdrawal_method;
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

        $withdrawal_methods = $this->withdrawal_method
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
        return View('transactionmodule::admin.withdraw.method.list', compact('withdrawal_methods', 'status', 'search'));
    }

    /**
     * Create resource.
     * @return Renderable
     */
    public function method_create(): Renderable
    {
        return View('transactionmodule::admin.withdraw.method.create');
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
            'field_type' => 'required|array',
            'field_name' => 'required|array',
            'placeholder_text' => 'required|array',
            'is_required' => '',
            'is_default' => 'in:0,1 ',
        ]);

        $method_fields = [];
        foreach ($request->field_name as $key=>$field_name) {
            $method_fields[] = [
                'input_type' => $request->field_type[$key],
                'input_name' => strtolower(str_replace(' ', "_", $request->field_name[$key])),
                'placeholder' => $request->placeholder_text[$key],
                'is_required' => isset($request['is_required']) && isset($request['is_required'][$key]) ? 1 : 0,
            ];
        }

        $data_count = $this->withdrawal_method->get()->count();

        $withdrawal_method_object = $this->withdrawal_method->updateOrCreate(
            ['method_name' => $request->method_name],
            [
                'method_fields' => $method_fields,
                'is_default' => ($request->has('is_default') && $request->is_default || $data_count == 0) == '1' ? 1 : 0,
            ]
        );

        if ($request->has('is_default') && $request->is_default == '1') {
            $this->withdrawal_method->where('id', '!=', $withdrawal_method_object->id)->update(['is_default' => 0]);
        }

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
        $withdrawal_method = $this->withdrawal_method->find($id);
        return View('transactionmodule::admin.withdraw.method.edit', compact('withdrawal_method'));
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
            'field_type' => 'required|array',
            'field_name' => 'required|array',
            'placeholder_text' => 'required|array',
            'is_required' => '',
            'is_default' => 'in:0,1 ',
        ]);
        // dd($request->all());
        $withdrawal_method = $this->withdrawal_method->find($request['id']);

        if(!isset($withdrawal_method)) {
            Toastr::error(DEFAULT_404['message']);
            return back();
        }

        $method_fields = [];
        foreach ($request->field_name as $key=>$field_name) {
            $method_fields[] = [
                'input_type' => $request->field_type[$key],
                'input_name' => strtolower(str_replace(' ', "_", $request->field_name[$key])),
                'placeholder' => $request->placeholder_text[$key],
                'is_required' => isset($request['is_required']) && isset($request['is_required'][$key]) ? $request['is_required'][$key] : 0,
            ];
        }

        $withdrawal_method_object = $this->withdrawal_method->updateOrCreate(
            ['method_name' => $request->method_name],
            [
                'method_fields' => $method_fields,
                'is_default' => $request->has('is_default') && $request->is_default == '1' ? 1 : 0,
            ]
        );

        if ($request->has('is_default') && $request->is_default == '1') {
            $this->withdrawal_method->where('id', '!=', $withdrawal_method_object->id)->update(['is_default' => 0]);
        }

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
        $this->withdrawal_method->where('id', $id)->delete();
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
        $withdrawal_method = $this->withdrawal_method->where('id', $id)->first();

        if (!$withdrawal_method->is_default) {
            $this->withdrawal_method->where('id', $id)->update(['is_active' => !$withdrawal_method->is_active]);
            return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
        }
        return response()->json(DEFAULT_400, 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function method_default_status_update(Request $request, $id): JsonResponse
    {
        $withdrawal_method = $this->withdrawal_method->where('id', $id)->first();
        if ($withdrawal_method->is_default == 1) {
            return response()->json(DEFAULT_STATUS_FAILED_200, 200);
        }

        $this->withdrawal_method->where('id', '!=', $id)->update(['is_default' => $withdrawal_method->is_default]);
        $this->withdrawal_method->where('id', $id)->update(['is_default' => !$withdrawal_method->is_default]);

        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }

}
