<?php

namespace Qihucms\Information\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Qihucms\Information\Models\InformationFriend;
use Qihucms\Information\Models\InformationFriendPolicy;
use Qihucms\Information\Models\InformationMessage;
use Qihucms\Information\Requests\StoreFriendRequest;
use Qihucms\Information\Resources\FriendCollection;
use Qihucms\Information\Resources\Friend as FriendResource;

class FriendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * 好友列表
     *
     * @param Request $request
     * @return FriendCollection
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 15);

        $condition = [
            ['user_id', '=', Auth::id()],
            ['status', '=', (int)$request->get('status', 1)]
        ];

        $items = InformationFriend::where($condition)->paginate($limit);

        return new FriendCollection($items);
    }

    /**
     * 添加好友
     *
     * @param StoreFriendRequest $request
     * @return \Illuminate\Http\JsonResponse|FriendResource
     */
    public function store(StoreFriendRequest $request)
    {
        $friend_id = $request->input('friend_id');

        // 验证会员好友策略
        $friend_policy = InformationFriendPolicy::where('user_id', $friend_id)->latest()->first();
        if ($friend_policy) {
            if (!empty($friend_policy->password)) {
                if ($friend_policy->password != $request->input('password')) {
                    return $this->jsonResponse(
                        ['password' => __('information::message.password_error')], '', 422);
                }
                $status = 1;
            } elseif (!empty($friend_policy->answer)) {
                if ($friend_policy->answer != $request->input('answer')) {
                    return $this->jsonResponse(
                        ['answer' => __('information::message.answer_error')], '', 422);
                }
                $status = 1;
            } else {
                $status = 0;
            }
        } else {
            $status = 1;
        }

        if ($status == 1) {
            // 通过密码和问题的直接成为好友，创建自己的好友记录
            InformationFriend::updateOrCreate(
                ['friend_id' => $friend_id, 'user_id' => Auth::id()],
                ['status' => 1]
            );
        }

        // 创建供好友审核的记录
        $item = InformationFriend::firstOrCreate(
            ['user_id' => $friend_id, 'friend_id' => Auth::id()],
            ['status' => $status]
        );

        return new FriendResource($item);
    }

    /**
     * 会员审核好友申请
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $friend = InformationFriend::where('user_id', Auth::id())->where('friend_id', $id)
            ->where('status', 0)->first();

        if ($friend) {
            // 拒绝添加好友
            if ($request->has('status')) {
                $friend->status = 2;
                $friend->save();

                return $this->jsonResponse(['friend_id' => $id, 'status' => 2]);
            }

            // 同意添加好友
            $friend->status = 1;
            $friend->save();

            // 通过好友请求后，创建申请加好友会员的记录
            InformationFriend::updateOrCreate(
                ['user_id' => $id, 'friend_id' => Auth::id()],
                ['status' => 1]
            );

            return $this->jsonResponse(['friend_id' => $id, 'status' => 1]);
        }

        return $this->jsonResponse([__('information::message.params_error')], '', 422);
    }

    /**
     * 修改好友备注名称
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if ($request->has('friend_name')) {
            $result = InformationFriend::where('user_id', Auth::id())->where('friend_id', $id)
                ->update(['friend_name' => $request->input('friend_name')]);

            if ($result) {
                return $this->jsonResponse(['friend_id' => $id]);
            }
        }

        return $this->jsonResponse([__('information::message.params_error')], '', 422);
    }

    /**
     * 删除好友
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $model = InformationFriend::where('user_id', Auth::user())->where('friend_id', $id)->first();

        if ($model) {
            // 删除消息记录
            InformationMessage::where('information_friend_id', $model->id)->delete();

            // 删除好友
            $model->delete();

            // 更新好友状态
            InformationFriend::where('friend_id', Auth::user())->where('user_id', $id)->update(['status' => 3]);

            return $this->jsonResponse(['friend_id' => $id]);
        }

        return $this->jsonResponse([__('information::message.params_error')], '', 422);
    }
}