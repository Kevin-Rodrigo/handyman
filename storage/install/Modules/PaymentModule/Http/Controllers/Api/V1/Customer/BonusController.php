<?php

namespace Modules\PaymentModule\Http\Controllers\Api\V1\Customer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\PaymentModule\Entities\Bonus;

class BonusController extends Controller
{
    protected Bonus $bonus;

    public function __construct(Bonus $bonus)
    {
        $this->bonus = $bonus;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function get_bonuses(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $bonuses = $this->bonus->ofStatus(1)
        ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->paginate($request['limit'], ['*'], 'offset', $request['offset']);

        return response()->json(response_formatter(DEFAULT_200, $bonuses), 200);
    }
}
