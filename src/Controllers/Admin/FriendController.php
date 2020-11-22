<?php

namespace Qihucms\Information\Controllers\Admin;

use App\Admin\Controllers\Controller;
use Qihucms\Information\Models\InformationFriend;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FriendController extends Controller
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '好友消息';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new InformationFriend());

        $grid->model()->latest();
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 去掉编辑
            $actions->disableEdit();
        });

        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->equal('user_id', __('information::information_friend.user_id'));
            $filter->equal('friend_id', __('information::information_friend.user_id'));
            $filter->like('friend_name', __('information::information_friend.friend_name'));
            $filter->equal('status', __('information::information_friend.status.label'))
                ->select(__('information::information_friend.status.value'));
        });

        $grid->column('id', __('information::information_friend.id'));
        $grid->column('user.username', __('information::information_friend.user_id'));
        $grid->column('friend.username', __('information::information_friend.friend_id'));
        $grid->column('friend_name', __('information::information_friend.friend_name'));
        $grid->column('status', __('information::information_friend.status.label'))
            ->using(__('information::information_friend.status.value'));
        $grid->column('created_at', __('admin.created_at'));
        $grid->column('updated_at', __('admin.updated_at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(InformationFriend::findOrFail($id));

        $show->field('id', __('information::information_friend.id'));
        $show->field('user', __('information::information_friend.user_id'))
            ->as(function () {
                return $this->user ? $this->user->username : '会员不存在';
            });
        $show->field('friend', __('information::information_friend.friend_id'))
            ->as(function () {
                return $this->friend ? $this->friend->username : '会员不存在';
            });
        $show->field('friend_name', __('information::information_friend.friend_name'));
        $show->field('status', __('information::information_friend.status.label'))
            ->using(__('information::information_friend.status.value'));
        $show->field('created_at', __('admin.created_at'));
        $show->field('updated_at', __('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new InformationFriend());

        $form->number('friend_name', __('information::information_friend.friend_name'));
        $form->select('status', __('information::information_friend.status.label'))
        ->options(__('information::information_friend.status.value'));

        return $form;
    }
}
