<?php

namespace Modules\PaymentModule\Http\Controllers\Api\V1\Customer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\PaymentModule\Entities\OfflinePayment;

class OfflinePaymentController extends Controller
{
    protected OfflinePayment $offline_payment;

    public function __construct(OfflinePayment $offline_payment)
    {
        $this->offline_payment = $offline_payment;
    }

    /**
     * Display a listing of the resource.
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

        $methods = $this->offline_payment->ofStatus(1)
            ->paginate($request['limit'], ['*'], 'offset', $request['offset']);

        return response()->json(response_formatter(DEFAULT_200, $methods), 200);
    }
}
