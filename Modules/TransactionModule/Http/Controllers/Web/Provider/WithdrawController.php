<?php

namespace Modules\TransactionModule\Http\Controllers\Web\Provider;

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
    public function get_method(Request $request): JsonResponse
    {
        $method = $this->withdrawal_method->ofStatus(1)->where('id', $request->method_id)->first();
        return response()->json(response_formatter(DEFAULT_200, $method), 200);
    }
}
