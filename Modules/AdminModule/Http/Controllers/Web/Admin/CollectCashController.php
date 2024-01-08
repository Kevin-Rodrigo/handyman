<?php

namespace Modules\AdminModule\Http\Controllers\Web\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ProviderManagement\Entities\Provider;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\User;
use function collect_cash_transaction;
use function get_user_id;
use function response;
use function response_formatter;
use const TRANSACTION_TYPE;

class CollectCashController extends Controller
{
    protected User $user;
    protected Provider $provider;
    protected Transaction $transaction;

    public function __construct(User $user, Provider $provider, Transaction $transaction)
    {
        $this->user = $user;
        $this->provider = $provider;
        $this->transaction = $transaction;
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function collect_cash(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|uuid',
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $provider_user_id = get_user_id($request['provider_id'], PROVIDER_USER_TYPES[0]);
        $provider_user = $this->user->with(['account'])->find($provider_user_id);
        if(is_null($provider_user)) {
            return response()->json(response_formatter(DEFAULT_404, null, error_processor($validator)), 404);
        }

        if($request['amount'] > $provider_user->account->account_payable) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 200);
        }

        collect_cash_transaction($request->provider_id, $request['amount']);

        return response()->json(response_formatter(DEFAULT_200), 200);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function collect_cash_transaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'provider_id' => 'required|uuid',
            'string' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $transactions = $this->transaction
            ->with(['from_user.account'])
            ->where('from_user_id', get_user_id($request['provider_id'], PROVIDER_USER_TYPES[0]))
            ->where('trx_type', TRANSACTION_TYPE[3]['key'])
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('ref_trx_id', 'LIKE', '%' . $key . '%')
                            ->orWhere('trx_type', 'LIKE', '%' . $key . '%')
                            ->orWhere('from_user_id', 'LIKE', '%' . $key . '%')
                            ->orWhere('to_user_id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $total_collected_cash = $this->transaction
            ->where('from_user_id', get_user_id($request['provider_id'], PROVIDER_USER_TYPES[0]))
            ->where('trx_type', TRANSACTION_TYPE[3]['key'])
            ->sum('debit');


        return response()->json(response_formatter(DEFAULT_200, ['transactions' => $transactions, 'total_collected_cash' => $total_collected_cash]), 200);
    }

}
