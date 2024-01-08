<?php

namespace Modules\ProviderManagement\Http\Controllers\Api\V1\Provider;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ProviderManagement\Entities\WithdrawRequest;
use Modules\TransactionModule\Entities\Transaction;
use Modules\TransactionModule\Entities\WithdrawalMethod;
use Modules\UserManagement\Entities\User;

class WithdrawController extends Controller
{
    protected User $user;
    protected Provider $provider;
    protected WithdrawRequest $withdraw_request;
    protected Transaction $transaction;
    protected WithdrawalMethod $withdrawal_method;

    public function __construct(User $user, Provider $provider, WithdrawRequest $withdraw_request, Transaction $transaction, WithdrawalMethod $withdrawal_method)
    {
        $this->user = $user;
        $this->provider = $provider;
        $this->withdraw_request = $withdraw_request;
        $this->transaction = $transaction;
        $this->withdrawal_method = $withdrawal_method;
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'string' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $withdraw_requests = $this->withdraw_request
            ->with(['user.account', 'request_updater.account'])
            ->where('user_id', $request->user()->id)
            ->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $total_collected_cash = $this->transaction
            ->where('from_user_id', $request->user()->id)
            ->where('trx_type', TRANSACTION_TYPE[1]['key'])
            ->sum('debit');

        return response()->json(response_formatter(DEFAULT_200, ['withdraw_requests' => $withdraw_requests, 'total_collected_cash' => $total_collected_cash]), 200);
    }

    /**
     * withdraw amount
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'note' => 'max:255',
            'withdrawal_method_id' => 'required',
            'withdrawal_method_fields' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        //input fields validation check
        $withdrawal_method = $this->withdrawal_method->find($request['withdrawal_method_id']);
        $fields = array_column($withdrawal_method->method_fields, 'input_name');

        $values = (array)json_decode(base64_decode($request['withdrawal_method_fields']))[0];

        foreach ($fields as $field) {
            if(!key_exists($field, $values)) {
                return response()->json(response_formatter(DEFAULT_400, $fields, null), 400);
            }
        }

        $provider_user = $this->user->with(['account'])->find($request->user()->id);

        if($request['amount'] > $provider_user->account->account_receivable) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 200);
        }

        withdraw_request_transaction($request->user()->id, $request['amount']);

        $this->withdraw_request->create([
            'user_id' => $request->user()->id,
            'request_updated_by' => $request->user()->id,
            'amount' => $request['amount'],
            'request_status' => 'pending',
            'is_paid' => 0,
            'note' => $request['note'],
            'withdrawal_method_id' => $request['withdrawal_method_id'],
            'withdrawal_method_fields' => $values,
        ]);

        return response()->json(response_formatter(DEFAULT_200), 200);
    }


}
