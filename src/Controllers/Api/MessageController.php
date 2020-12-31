<?php

namespace Qihucms\Information\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Qihucms\Information\Models\InformationFriend;
use Qihucms\Information\Models\InformationMessage;
use Qihucms\Information\Requests\StoreMessageRequest;
use Qihucms\Information\Resources\FriendCollection;
use Qihucms\Information\Resources\MessageCollection;
use Qihucms\Information\Resources\Message as MessageResource;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * 发送过消息的好友列表
     *
     * @param Request $request
     * @return FriendCollection
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 15);

        $items = InformationFriend::where('user_id', '=', Auth::id())
            ->has('information_messages')
            ->withCount(['information_messages' => function (Builder $query) {
                $query->where('status', '=', 0);
            }])
            ->paginate($limit);

        return new FriendCollection($items);
    }

    /**
     * 好友消息列表
     *
     * @param int $id 好友关系ID
     * @return JsonResponse|MessageCollection
     */
    public function show($id)
    {
        if (InformationFriend::where('user_id', Auth::id())->where('id', $id)->doesntExist()) {
            return $this->jsonResponse([__('information::message.params_error')], '', 422);
        }

        $items = InformationMessage::where('information_friend_id', $id)->paginate();

        return new MessageCollection($items);
    }

    /**
     * 发布消息
     *
     * @param StoreMessageRequest $request
     * @return \Illuminate\Http\JsonResponse|MessageResource
     */
    public function store(StoreMessageRequest $request)
    {
        $data = $request->only(['information_friend_id', 'type', 'message']);

        // 发布者好友关系
        $information_user = InformationFriend::find($data['information_friend_id']);
        if (!$information_user || $information_user->status != 1) {
            return $this->jsonResponse(
                [__('information::message.no_friend_no_message')], '', 422);
        }

        if ($information_user->user_id != Auth::id()) {
            return $this->jsonResponse([__('information::message.params_error')], '', 422);
        }

        // 接收者好友关系
        $information_friend = InformationFriend::where('user_id', $information_user->friend_id)
            ->where('friend_id', $information_user->user_id)->first();
        if (!$information_friend || $information_friend->status != 1) {
            return $this->jsonResponse(
                [__('information::message.no_friend_no_message')], '', 422);
        }

        // 附加数据
        $data['user_id'] = Auth::id();

        // 发布者的消息
        $data['status'] = 0; // *** 此状态是反馈给发布者的，所以未读状态放在发布都的信息中 ***
        $item = InformationMessage::create($data);

        // 接收者的消息
        $data['status'] = 1;
        $data['information_friend_id'] = $information_friend->id;
        InformationMessage::create($data);

        return new MessageResource($item);
    }

    /**
     * 更新好友消息阅读状态
     *
     * @param int $id 好友关系ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        $information_user = InformationFriend::find($id);

        if ($information_user && $information_user->user_id == Auth::id()) {
            $information_friend = InformationFriend::where('user_id', $information_user->friend_id)
                ->where('friend_id', $information_user->user_id)->first();

            // 更新是内容是属于发布者读取的记录状态
            InformationMessage::where('information_friend_id', $information_friend->id)->update(['status' => 1]);

            return $this->jsonResponse(['id' => $id]);
        }

        return $this->jsonResponse([__('information::message.params_error')], '', 422);
    }

    /**
     * 存在all参数，清空消息
     * 存在ids参数，且参数类型为数组，批量删除消息
     * 否则执行删除一条消息
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        // 删除所有供自己读取的消息，ID为好友关系ID
        if ($request->has('all')) {
            // 验证关系是否属于当前用户
            $friend = InformationFriend::where('id', $id)->where('user_id', Auth::id())->first();
            if ($friend) {
                InformationMessage::where('information_friend_id', $friend->id)->delete();

                return $this->jsonResponse(['id' => $id, 'all' => true]);
            }
        } elseif ($request->has('ids')) {
            // 验证关系是否属于当前用户
            $friend = InformationFriend::where('id', $id)->where('user_id', Auth::id())->first();
            $ids = $request->input('ids');
            $ids = explode(',', $ids);
            $ids = array_filter($ids);
            if ($friend && count($ids)) {
                InformationMessage::whereIn('id', $ids)->delete();

                return $this->jsonResponse(['id' => $id, 'ids' => $ids]);
            }
        } else {
            // 删除一条消息时，ID为消息ID
            $model = InformationMessage::find($id);

            // 验证消息是否属于当前用于
            if ($model->information_friend->user_id == Auth::id()) {
                $model->delete();

                return $this->jsonResponse(['id' => $id]);
            }
        }

        return $this->jsonResponse([__('information::message.params_error')], '', 422);
    }
}