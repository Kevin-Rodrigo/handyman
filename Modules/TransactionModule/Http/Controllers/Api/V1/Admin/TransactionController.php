<?php

namespace Modules\TransactionModule\Http\Controllers\Api\V1\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\TransactionModule\Entities\Transaction;


class TransactionController extends Controller
{
    private Transaction $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'start_date' => 'date',
            'end_date' => 'date',
            'trx_type' => 'required|in:all,debit,credit'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $transactions = $this->transaction
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request['trx_type'] != 'all', function ($query) use ($request) {
                if ($request['trx_type'] == 'debit') {
                    return $query->where('debit', '!=', 0);
                } else {
                    return $query->where('credit', '!=', 0);
                }
            })->with(['from_user', 'to_user'])
            ->when($request->has('from_date') && $request->has('to_date'), function ($query) use ($request) {
                $query->whereBetween('created_at', [date('Y-m-d', strtotime($request['from_date'])), date('Y-m-d', strtotime($request['to_date']))]);
            })->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $data = [
            'commission_earning' => $this->transaction->where('trx_type', 'commission')->whereIn('to_user_account', ['received_balance'])
                ->when($request->has('start_date') && $request->has('end_date'), function ($query) use ($request) {
                    return $query->whereBetween('created_at', [$request['start_date'], $request['end_date']]);
                })->sum('credit'),
            'total_debit' => $this->transaction
                ->when($request->has('start_date') && $request->has('end_date'), function ($query) use ($request) {
                    return $query->whereBetween('created_at', [$request['start_date'], $request['end_date']]);
                })->sum('debit'),
            'total_credit' => $this->transaction
                ->when($request->has('start_date') && $request->has('end_date'), function ($query) use ($request) {
                    return $query->whereBetween('created_at', [$request['start_date'], $request['end_date']]);
                })->sum('credit')
        ];
        return response()->json(response_formatter(DEFAULT_200, ['transactions' => $transactions, 'cards_data' => $data]), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'from_user_id' => 'required|uuid',
            'to_user_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        //make transaction
        DB::transaction(function () use ($request) {
            $transaction = $this->transaction;
            //first transaction
            $data = [
                'ref_trx_id' => null,
                'booking_id' => $request->booking_id,
                'trx_type' => null,
                'debit' => $request->amount,
                'credit' => 0,
                'balance' => 0,
                'from_user_id' => $request->from_user_id,
                'to_user_id' => $request->to_user_id
            ];
            $transaction::create($data);

            //second transactions
            $data = [
                'ref_trx_id' => $transaction['trx_id'],
                'booking_id' => $request->booking_id,
                'trx_type' => null,
                'debit' => 0,
                'credit' => $request->amount,
                'balance' => 0,
                'from_user_id' => $request->to_user_id,
                'to_user_id' => $request->from_user_id
            ];
            $transaction::create($data);
        });

        return response()->json(response_formatter(DEFAULT_STORE_200), 200);
    }
}
