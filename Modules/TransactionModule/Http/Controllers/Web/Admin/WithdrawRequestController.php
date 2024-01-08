<?php

namespace Modules\TransactionModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ProviderManagement\Entities\WithdrawRequest;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\TransactionModule\Entities\WithdrawalMethod;
use Modules\UserManagement\Entities\User;
use Rap2hpoutre\FastExcel\FastExcel;

class WithdrawRequestController extends Controller
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


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,denied,settled,all',
            'search' => 'max:255'
        ])->validate();

        $search = $request['search']??"";
        $status = $request['status']??'all';
        $query_param = ['search' => $request['search'], 'status' => $status];

        $withdraw_requests = $this->withdraw_request
            ->with(['provider.bank_detail'])
            //->with(['user.provider.bank_detail', 'request_updater'])
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->where('request_status', $request->status);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->whereHas('provider', function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->where('company_name', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()
            ->paginate(pagination_limit())->appends($query_param);

//        return $withdraw_requests;
        return View('transactionmodule::admin.withdraw.request.list', compact('withdraw_requests', 'status', 'search'));
    }

    /**
     * Display a listing of the resource.
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Request $request)
    {
        Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,denied,settled,all',
        ])->validate();

        $withdraw_requests = $this->withdraw_request
            ->with(['provider.bank_detail', 'withdraw_method'])
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->where('request_status', $request->status);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('amount', 'LIKE', '%' . $key . '%')
                            ->orWhere('note', 'LIKE', '%' . $key . '%')
                            ->orWhere('request_status', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()
            ->get();

        //Data formatting
        $withdraw_requests->map(function ($query) {
            //company info
            $query->company_name = isset($query->provider) ? $query->provider->company_name : '';
            $query->company_phone = isset($query->provider) ? $query->provider->company_phone : '';
            $query->company_address = isset($query->provider) ? $query->provider->company_address : '';
            $query->company_email = isset($query->provider) ? $query->provider->company_email : '';

            //request info
            $query->withdraw_id = $query->id;
            $query->withdrawal_amount = $query->amount;
            $query->payment_status = $query->is_paid ? 'paid' : 'unpaid';
            $query->optional_note = $query->admin_note;

            //method info
            $query->withdraw_method_name = isset($query->withdraw_method) ? $query->withdraw_method->method_name : '';
            foreach ($query->withdrawal_method_fields as $key=>$field) {
                $query[$key] = $field;
            }
        });

        foreach ($withdraw_requests as $key=>$item) {
            unset($item['id']);
            unset($item['user_id']);
            unset($item['request_updated_by']);
            unset($item['created_at']);
            unset($item['updated_at']);
            unset($item['amount']);
            unset($item['is_paid']);
            unset($item['note']);
            unset($item['admin_note']);
            unset($item['withdrawal_method_fields']);
            unset($item['withdrawal_method_id']);
            unset($item['provider']);
            unset($item['withdraw_method']);
        }
        //end

        return (new FastExcel($withdraw_requests))->download(time().'-withdraw-request.xlsx');
    }

    public function import(Request $request)
    {
        try {
            $collections = (new FastExcel)->import($request->file('withdraw_request_file'));
        } catch (\Exception $exception) {
            Toastr::error(translate('You have uploaded a wrong format file, please upload the right file.'));
            return back();
        }

        //check
        $field_array = ['company_name', 'company_phone', 'company_address', 'payment_status', 'request_status'];
        if(count($collections) < 1) {
            Toastr::error(translate('At least one row value is required'));
            return back();
        }

        foreach ($field_array as $item) {
            if(!array_key_exists($item, $collections->first())) {
                Toastr::error(translate($item) . translate(' must not be empty.'));
                return back();
            }
        }

        foreach ($collections as $collection) {
            $withdraw_request = $this->withdraw_request->find($collection['withdraw_id']);

            if ($collection['request_status'] == 'approved' && $withdraw_request && $withdraw_request->request_status == 'pending') {
                withdraw_request_accept_transaction($withdraw_request['request_updated_by'], $withdraw_request['amount']);

                $withdraw_request->request_status = 'approved';
                $withdraw_request->request_updated_by = $request->user()->id;
                $withdraw_request->admin_note = $collection['optional_note'];
                $withdraw_request->is_paid = 1;
                $withdraw_request->save();

            }
            elseif($collection['request_status'] == 'settled' && $withdraw_request && $withdraw_request->request_status == 'approved') {
                $withdraw_request->request_status = 'settled';
                $withdraw_request->request_updated_by = $request->user()->id;
                $withdraw_request->admin_note = $collection['optional_note'];
                $withdraw_request->save();

            }
            elseif ($collection['request_status'] == 'denied' && $withdraw_request && $withdraw_request->request_status == 'pending') {
                withdraw_request_deny_transaction($withdraw_request['request_updated_by'], $withdraw_request['amount']);

                $withdraw_request->request_status = 'denied';
                $withdraw_request->request_updated_by = $request->user()->id;
                $withdraw_request->admin_note = $collection['optional_note'];
                $withdraw_request->is_paid = 0;
                $withdraw_request->save();
            }
        }

        Toastr::success(translate('Updated successfully!'));
        return back();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_status(Request $request, string $id)
    {
        Validator::make($request->all(), [
            'status' => 'required|in:approved,denied,settled',
            'note' => 'max:255',
        ])->validate();


        $withdraw_request = $this->withdraw_request::find($id);

        if ($request['status'] == 'approved') {
            withdraw_request_accept_transaction($withdraw_request['request_updated_by'], $withdraw_request['amount']);

            $withdraw_request->request_status = 'approved';
            $withdraw_request->request_updated_by = $request->user()->id;
            $withdraw_request->admin_note = $request->note;
            $withdraw_request->is_paid = 1;
            $withdraw_request->save();

        } else if ($request['status'] == 'settled') {
            $withdraw_request->request_status = 'settled';
            $withdraw_request->request_updated_by = $request->user()->id;
            $withdraw_request->admin_note = $request->note;
            $withdraw_request->save();

        } else if ($request['status'] == 'denied') {
            withdraw_request_deny_transaction($withdraw_request['request_updated_by'], $withdraw_request['amount']);

            $withdraw_request->request_status = 'denied';
            $withdraw_request->request_updated_by = $request->user()->id;
            $withdraw_request->admin_note = $request->note;
            $withdraw_request->is_paid = 0;
            $withdraw_request->save();

        }

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update_multiple_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,denied,settled',
            'request_ids' => 'required|array',
            'request_ids.*' => 'uuid',
            'note' => 'max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $withdraw_requests = $this->withdraw_request::whereIn('id', $request->request_ids)->get();

        if ($request['status'] == 'approved') {
            foreach ($withdraw_requests as $withdraw_request) {
                if($withdraw_request->request_status == 'pending') {
                    withdraw_request_accept_transaction($withdraw_request['request_updated_by'], $withdraw_request['amount']);

                    $withdraw_request->request_status = 'approved';
                    $withdraw_request->request_updated_by = $request->user()->id;
                    $withdraw_request->admin_note = $request->note;
                    $withdraw_request->is_paid = 1;
                    $withdraw_request->save();
                }
            }

        } else if ($request['status'] == 'settled') {
            foreach ($withdraw_requests as $withdraw_request) {
                if($withdraw_request->request_status == 'approved') {
                    $withdraw_request->request_status = 'settled';
                    $withdraw_request->request_updated_by = $request->user()->id;
                    $withdraw_request->admin_note = $request->note;
                    $withdraw_request->save();
                }
            }

        } else if ($request['status'] == 'denied') {
            foreach ($withdraw_requests as $withdraw_request) {
                if($withdraw_request->request_status == 'pending') {
                    withdraw_request_deny_transaction($withdraw_request['request_updated_by'], $withdraw_request['amount']);

                    $withdraw_request->request_status = 'denied';
                    $withdraw_request->request_updated_by = $request->user()->id;
                    $withdraw_request->admin_note = $request->note;
                    $withdraw_request->is_paid = 0;
                    $withdraw_request->save();
                }
            }

        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

}
