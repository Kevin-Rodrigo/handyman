<?php

namespace Modules\TransactionModule\Http\Controllers\Api\V1\Provider;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\TransactionModule\Entities\WithdrawalMethod;

class WithdrawController extends Controller
{
    protected WithdrawalMethod $withdrawal_method;

    public function __construct(withdrawalMethod $withdrawal_method)
    {
        $this->withdrawal_method = $withdrawal_method;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function get_methods(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $methods = $this->withdrawal_method->ofStatus(1)
            ->paginate($request['limit'], ['*'], 'offset', $request['offset']);

        return response()->json(response_formatter(DEFAULT_200, $methods), 200);
    }

}
