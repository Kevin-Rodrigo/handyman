<?php

namespace Modules\AdminModule\Http\Controllers\Web\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ProviderManagement\Entities\WithdrawRequest;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\User;
use function response;
use function response_formatter;
use function withdraw_request_accept_transaction;
use function withdraw_request_deny_transaction;

class WithdrawController extends Controller
{
    protected User $user;
    protected Account $account;
    protected WithdrawRequest $withdraw_request;
    protected Transaction $transaction;

    public function __construct(User $user, Account $account, WithdrawRequest $withdraw_request, Transaction $transaction)
    {
        $this->user = $user;
        $this->account = $account;
        $this->withdraw_request = $withdraw_request;
        $this->transaction = $transaction;
    }


    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:pending,approved,denied,all',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $withdraw_request = $this->withdraw_request->with(['user.provider.bank_detail','request_updater'])
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->where('request_status', $request->status);
            })->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');


        return response()->json(response_formatter(DEFAULT_200, $withdraw_request), 200);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'request_status' => 'required|in:approved,denied',
            'note' => 'max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 403);
        }

        $withdraw_request = $this->withdraw_request::find($id);
        if(isset($withdraw_request) && $withdraw_request['request_status'] != 'pending') {
            return response()->json(response_formatter(DEFAULT_400), 200);
        }

        if($request['request_status'] == 'approved') {
            withdraw_request_accept_transaction($withdraw_request['request_updated_by'], $withdraw_request['amount']);

            $withdraw_request->request_status = 'approved';
            $withdraw_request->request_updated_by = $request->user()->id;
            $withdraw_request->note = $request->note;
            $withdraw_request->is_paid = 1;
            $withdraw_request->save();

        } else {
            withdraw_request_deny_transaction($withdraw_request['request_updated_by'], $withdraw_request['amount']);

            $withdraw_request->request_status = 'denied';
            $withdraw_request->request_updated_by = $request->user()->id;
            $withdraw_request->note = $request->note;
            $withdraw_request->is_paid = 0;
            $withdraw_request->save();

        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

}
