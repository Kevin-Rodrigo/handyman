<?php

namespace Modules\ChattingModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\ChattingModule\Entities\ChannelConversation;
use Modules\ChattingModule\Entities\ChannelList;
use Modules\ChattingModule\Entities\ChannelUser;
use Modules\ChattingModule\Entities\ConversationFile;
use Modules\UserManagement\Entities\User;
use Ramsey\Uuid\Nonstandard\Uuid;

class ChattingController extends Controller
{
    protected ChannelList $channel_list;
    protected ChannelUser $channel_user;
    protected ChannelConversation $channel_conversation;
    protected ConversationFile $conversation_file;
    protected User $user;

    public function __construct(User $user, ChannelList $channel_list, ChannelUser $channel_user, ChannelConversation $channel_conversation, ConversationFile $conversation_file)
    {
        $this->channel_list = $channel_list;
        $this->channel_user = $channel_user;
        $this->channel_conversation = $channel_conversation;
        $this->conversation_file = $conversation_file;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Factory|View|Application
     */
    public function index(Request $request): Factory|View|Application
    {
        $chat_list = $this->channel_list->withCount(['channelUsers'])
            ->with(['channelUsers.user.provider'])
            ->whereHas('channelUsers', function ($query) use ($request) {
                $query->where(['user_id' => $request->user()->id]);
            })->orderBy('updated_at', 'DESC')->get();

        $chat_list->map(function ($chat) use ($request) {
            $chat['is_read'] = $chat->channelUsers->where('user_id', $request->user()->id)->first()->is_read;
        });

        $customers = $this->user->ofStatus(1)->where(['user_type' => 'customer'])->get();
        $providers = $this->user->ofStatus(1)->where(['user_type' => 'provider-admin'])->with(['provider'])->get();
        $servicemen = $this->user->ofStatus(1)->where(['user_type' => 'provider-serviceman'])->get();

        return view('chattingmodule::admin.index', compact('chat_list', 'customers', 'providers', 'servicemen'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function channel_list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $chat_list = $this->channel_list->withCount(['channelUsers'])
            ->with(['channelUsers.user'])
            ->whereHas('channelUsers', function ($query) use ($request) {
                $query->where(['user_id' => $request->user()->id]);
            })->orderBy('updated_at', 'DESC')
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $chat_list), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function referenced_channel_list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'reference_id' => 'required',
            'reference_type' => 'required|in:booking_id',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $chat_list = $this->channel_list->withCount(['channelUsers'])->with(['channelUsers.user'])
            ->where(['reference_id' => $request['reference_id'], 'reference_type' => $request['reference_type']])
            ->whereHas('channelUsers', function ($query) use ($request) {
                $query->where(['user_id' => $request->user()->id]);
            })->orderBy('updated_at', 'DESC')
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $chat_list), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function create_channel(Request $request): RedirectResponse
    {
        $request->validate([
            'reference_id' => '',
            'reference_type' => 'in:booking_id',
            'user_type' => 'in:customer,provider-admin,provider-serviceman',
        ]);

        if ($request['user_type'] == 'customer') {
            $request['to_user'] = $request['customer_id'];
        }elseif ($request['user_type'] == 'provider-admin') {
            $request['to_user'] = $request['provider_id'];
        }elseif ($request['user_type'] == 'provider-serviceman') {
            $request['to_user'] = $request['serviceman_id'];
        }

        if (!$this->user->where('id', $request['to_user'])->exists()) {
            Toastr::error('Receiver not found');
            return back();
        }

        $channel_ids = $this->channel_user->where(['user_id' => $request->user()->id])->pluck('channel_id')->toArray();
        $find_channel = $this->channel_list->whereIn('id', $channel_ids)->whereHas('channelUsers', function ($query) use ($request) {
            $query->where(['user_id' => $request['to_user']]);
        })->latest()->first();

        if (!isset($find_channel)) {
            $channel = $this->channel_list;
            $channel->reference_id = $request['reference_id'] ?? null;
            $channel->reference_type = $request['reference_type'] ?? null;
            $channel->save();

            $this->channel_user->insert([
                [
                    'id' => Uuid::uuid4(),
                    'channel_id' => $channel->id,
                    'user_id' => $request->user()->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'id' => Uuid::uuid4(),
                    'channel_id' => $channel->id,
                    'user_id' => $request['to_user'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }

        Toastr::success(translate('you_can_start_conversation_now'));
        return back();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function send_message(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => '',
            'channel_id' => 'required|uuid',
            'files' => is_null($request['message']) ? 'required|array' : 'array',
            'files.*' => 'max:10240|mimes:' . implode(',', array_column(FILE_TYPE, 'key')),
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        DB::transaction(function () use ($request) {
            $this->channel_list->where('id', $request['channel_id'])->update([
                'updated_at' => now()
            ]);
            $this->channel_user->where('channel_id', $request['channel_id'])->where('user_id', '!=', $request->user()->id)
                ->update([
                    'is_read' => 0
                ]);

            $channel_conversation = $this->channel_conversation;
            $channel_conversation->channel_id = $request->channel_id;
            $channel_conversation->message = $request['message'];
            $channel_conversation->user_id = $request->user()->id;
            $channel_conversation->save();

            if ($request->has('files')) {
                foreach ($request->file('files') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $this->conversation_file->create([
                        'conversation_id' => $channel_conversation->id,
                        'file_name' => file_uploader('conversation/', $extension, $file),
                        'file_type' => $extension,
                    ]);
                }
            }
        });

        $conversation = $this->channel_conversation->where(['channel_id' => $request['channel_id']])
            ->with(['user', 'conversationFiles'])->whereHas('channel.channelUsers', function ($query) use ($request) {
                $query->where(['user_id' => $request->user()->id]);
            })->latest()->paginate(100, ['*'], 'offset', $request['offset']);

        return response()->json([
            'template' => view('chattingmodule::admin.partials._conversation-messages-only', compact('conversation'))->render()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function conversation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'channel_id' => 'required|uuid',
            'offset' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->channel_user->where('channel_id', $request['channel_id'])->where('user_id', $request->user()->id)
            ->update([
                'is_read' => 1
            ]);

        $conversation = $this->channel_conversation->where(['channel_id' => $request['channel_id']])
            ->with(['user', 'conversationFiles'])->whereHas('channel.channelUsers', function ($query) use ($request) {
                $query->where(['user_id' => $request->user()->id]);
            })->latest()->paginate(100, ['*'], 'offset', $request['offset']);

        $from_user = $this->channel_user->where('channel_id', $request['channel_id'])->where('user_id', '!=', $request->user()->id)->first();

        $channel_id = $request['channel_id'];

        return response()->json([
            'template' => view('chattingmodule::admin.partials._conversations', compact('from_user', 'conversation', 'channel_id'))->render()
        ]);
    }
}
