<?php

namespace Modules\ProviderManagement\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ProviderManagement\Entities\Provider;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\User;

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
    public function collect_cash(Request $request): RedirectResponse
    {
        Validator::make($request->all(), [
            'provider_id' => 'required|uuid',
            'amount' => 'required|numeric|min:1',
        ]);


        $provider_user_id = get_user_id($request['provider_id'], PROVIDER_USER_TYPES[0]);
        $provider_user = $this->user->with(['account'])->find($provider_user_id);
        if(is_null($provider_user)) {
            Toastr::success(DEFAULT_404['message']);
            return back();
        }

        if($request['amount'] > $provider_user->account->account_payable) {
            Toastr::error(COLLECT_CASH_FAIL_200['message']);
            return back();
        }

        collect_cash_transaction($request->provider_id, $request['amount']);

        Toastr::success(COLLECT_CASH_SUCCESS_200['message']);
        return back();
    }

    /**
     *
     * @param Request $request
     * @return Renderable
     */
    public function index($provider_id, Request $request)
    {
        Validator::make($request->all(), [
            'search' => 'search',
        ]);


        $search = $request->has('search') ? $request['search'] : '';
        $web_page = 'overview';
        $query_param = ['search' => $search, 'web_page' => $web_page];

        $transactions = $this->transaction
            ->with(['from_user.account'])
            ->where('from_user_id', get_user_id($provider_id, PROVIDER_USER_TYPES[0]))
            ->where('trx_type', 'paid_commission')
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('id', 'LIKE', '%' . $key . '%')
                            ->orWhere('ref_trx_id', 'LIKE', '%' . $key . '%')
                            ->orWhere('trx_type', 'LIKE', '%' . $key . '%')
                            ->orWhere('from_user_id', 'LIKE', '%' . $key . '%')
                            ->orWhere('to_user_id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()
            ->paginate(pagination_limit())->appends($query_param);

        $total_collected_cash = $this->transaction
            ->where('from_user_id', get_user_id($provider_id, PROVIDER_USER_TYPES[0]))
            ->where('trx_type', TRANSACTION_TYPE[3]['key'])
            ->sum('debit');

        return view('providermanagement::admin.account.collect-cash', compact('transactions', 'total_collected_cash', 'web_page', 'search', 'provider_id'));
    }
}
