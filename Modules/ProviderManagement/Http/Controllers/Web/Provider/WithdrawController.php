<?php

namespace Modules\ProviderManagement\Http\Controllers\Web\Provider;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ProviderManagement\Entities\WithdrawRequest;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\TransactionModule\Entities\WithdrawalMethod;
use Modules\UserManagement\Entities\User;
use Rap2hpoutre\FastExcel\FastExcel;

class WithdrawController extends Controller
{
    protected User $user;
    protected Provider $provider;
    protected WithdrawRequest $withdraw_request;
    protected Transaction $transaction;
    protected Account $account;
    protected WithdrawalMethod $withdrawal_method;

    public function __construct(User $user, Provider $provider, WithdrawRequest $withdraw_request, Transaction $transaction, Account $account, withdrawalMethod $withdrawal_method)
    {
        $this->user = $user;
        $this->provider = $provider;
        $this->withdraw_request = $withdraw_request;
        $this->transaction = $transaction;
        $this->account = $account;
        $this->withdrawal_method = $withdrawal_method;
    }

    /**
     *
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
    {
        Validator::make($request->all(), [
            'search' => 'string',
        ]);

        $search = $request->has('search') ? $request['search'] : '';
        $page_type = 'overview';
        $query_param = ['search' => $search, 'page_type' => $page_type];

        $withdraw_requests = $this->withdraw_request
            ->with(['user.account', 'request_updater.account'])
            ->where('user_id', $request->user()->id)
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                foreach ($keys as $key) {
                    $query->where('amount', 'LIKE', '%' . $key . '%')
                        ->orWhere('request_status', 'LIKE', '%' . $key . '%')
                        ->orWhere('note', 'LIKE', '%' . $key . '%');
                }
            })
            ->latest()
            ->paginate(pagination_limit())->appends($query_param);

        $total_collected_cash = $this->transaction
            ->where('from_user_id', $request->user()->id)
            ->where('trx_type', TRANSACTION_TYPE[1]['key'])
            ->sum('debit');

        $withdraw_request_amount = [
            'minimum' => (float)(business_config('minimum_withdraw_amount', 'business_information'))->live_values ?? null,
            'maximum' => (float)(business_config('maximum_withdraw_amount', 'business_information'))->live_values ?? null,
        ];

        //random value generate
        $min = $withdraw_request_amount['minimum']; // Set the minimum value
        $max = $withdraw_request_amount['maximum']; // Set the maximum value

        // Generate random numbers
        $mid = round(($min + $max) / 2 / 10) * 10; // Middle of min and max
        $mid1 = round(($min + $mid) / 2 / 10) * 10; // Middle of min and mid
        $mid2 = round(($mid + $max) / 2 / 10) * 10; // Middle of mid and max
        $num4 = ceil($max / 10) * 10; // Maximum value

        if ($min == 0 && $max == 0) {
            $num5 = 0;
        } else {
            do {
                $num5 = floor(rand($min, $max) / 10) * 10; // Random value between min and max
            } while (in_array($num5, array($mid, $mid1, $mid2, $num4)));
        }

        // Store generated numbers in an array
        $withdraw_request_amount['random'] = array($mid, $mid1, $num5, $mid2, $num4);
        //end

        $collectable_cash = $this->account->where('user_id', $request->user()->id)->first()->account_receivable ?? 0;

        $withdrawal_methods = $this->withdrawal_method->ofStatus(1)->get();

        return view('providermanagement::provider.account.withdraw', compact('withdraw_requests', 'total_collected_cash', 'search', 'page_type', 'collectable_cash', 'withdrawal_methods', 'withdraw_request_amount', 'withdraw_request_amount'));
    }

    /**
     * withdraw amount
     * @param Request $request
     * @return RedirectResponse
     */
    public function withdraw(Request $request): RedirectResponse
    {
        $method = $this->withdrawal_method->find($request['withdraw_method']);
        $fields = array_column($method->method_fields, 'input_name');

        $values = $request->all();
        $data = [];

        foreach ($fields as $field) {
            if(key_exists($field, $values)) {
                $data[$field] = $values[$field];
            }
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'max:255'
        ]);

        $provider_user = $this->user->with(['account'])->find($request->user()->id);

        if ($request['amount'] > $provider_user->account->account_receivable) {
            Toastr::error(DEFAULT_400['message']);
            return back();
        }

        //min max check
        $withdraw_request_amount = [
            'minimum' => (float)(business_config('minimum_withdraw_amount', 'business_information'))->live_values ?? null,
            'maximum' => (float)(business_config('maximum_withdraw_amount', 'business_information'))->live_values ?? null,
        ];

        if($request['amount'] < $withdraw_request_amount['minimum'] || $request['amount'] > $withdraw_request_amount['maximum']) {
            Toastr::error(DEFAULT_400['message']);
            return back();
        }

        withdraw_request_transaction($request->user()->id, $request['amount']);

        $this->withdraw_request->create([
            'user_id' => $request->user()->id,
            'request_updated_by' => $request->user()->id,
            'amount' => $request['amount'],
            'request_status' => 'pending',
            'is_paid' => 0,
            'note' => $request['note'],
            'withdrawal_method_id' => $request['withdraw_method'],
            'withdrawal_method_fields' => $data,
        ]);

        Toastr::success(DEFAULT_200['message']);
        return back();
    }

    public function download(Request $request)
    {
        $keys = explode(' ', $request['search']);
        $items = $this->withdraw_request
            ->where('user_id', $request->user()->id)
            ->where(function ($query) use ($keys) {
                foreach ($keys as $key) {
                    $query->orWhere('amount', 'LIKE', '%' . $key . '%')
                        ->orWhere('request_status', 'LIKE', '%' . $key . '%')
                        ->orWhere('note', 'LIKE', '%' . $key . '%');
                }
            })->get();
        return (new FastExcel($items))->download(time() . '-file.xlsx');
    }
}
