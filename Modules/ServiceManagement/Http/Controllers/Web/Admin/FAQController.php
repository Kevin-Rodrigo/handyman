<?php

namespace Modules\ServiceManagement\Http\Controllers\Web\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ServiceManagement\Entities\Faq;

class FAQController extends Controller
{

    private $faq;

    public function __construct(Faq $faq)
    {
        $this->faq = $faq;
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
            'status' => 'required|in:active,inactive,all',
            'service_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $faq = $this->faq->latest()
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                if ($request['status'] == 'active') {
                    return $query->where(['is_active' => 1]);
                } else {
                    return $query->where(['is_active' => 0]);
                }
            })->when($request->has('service_id'), function ($query) use ($request) {
                return $query->where('service_id', $request->service_id);
            })->paginate(pagination_limit(), ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $faq), 200);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param $service_id
     * @return JsonResponse
     */
    public function store(Request $request, $service_id): JsonResponse
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);

        $faq = $this->faq;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->service_id = $service_id;
        $faq->is_active = 1;
        $faq->save();

        $faqs = $this->faq->latest()->where('service_id', $service_id)->get();

        return response()->json(['flag' => 1, 'template' => view('servicemanagement::admin.partials._faq-list', compact('faqs'))->render()]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);

        $faq = $this->faq->find($id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->is_active = 1;
        $faq->save();

        $faqs = $this->faq->latest()->where('service_id', $faq->service_id)->get();

        return response()->json(['flag' => 1, 'template' => view('servicemanagement::admin.partials._faq-list', compact('faqs'))->render()]);
    }


    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param $faq_id
     * @param $service_id
     * @return JsonResponse
     */
    public function destroy(Request $request, $faq_id, $service_id): JsonResponse
    {
        $this->faq->where(['id' => $faq_id])->delete();
        $faqs = $this->faq->latest()->where('service_id', $service_id)->get();

        return response()->json(['flag' => 1, 'template' => view('servicemanagement::admin.partials._faq-list', compact('faqs'))->render()]);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function status_update(Request $request, $id): JsonResponse
    {
        $this->faq->where('id', $id)->update(['is_active' => !$this->faq->where('id', $id)->first()->is_active]);
        return response()->json(response_formatter(DEFAULT_STATUS_UPDATE_200), 200);
    }
}
