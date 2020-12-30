<?php

namespace Qihucms\Information\Controllers\Admin;

use App\Admin\Controllers\Controller;
use App\Models\User;
use Qihucms\Information\Models\InformationMessage;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MessageController extends Controller
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
        $grid = new Grid(new InformationMessage());

        $grid->model()->latest();
        $grid->disableCreateButton();
        $grid->actions(function ($actions) {
            // 去掉编辑
            $actions->disableEdit();
        });

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->equal('information_friend_id', __('information::information_message.information_friend_id'));
            $filter->equal('user_id', __('information::information_message.user_id'));
            $filter->equal('type', __('information::information_message.type.label'))
                ->select(__('information::information_message.type.value'));
            $filter->equal('status', __('information::information_message.status.label'))
                ->select(__('information::information_message.status.value'));
            $filter->like('message', __('information::information_message.message'));
        });

        $grid->column('information_friend_id', __('information::information_message.information_friend_id'));
        $grid->column('user.username', __('information::information_message.user_id'));
        $grid->column('type', __('information::information_message.type.label'))
            ->using(__('information::information_message.type.value'));
        $grid->column('message', __('information::information_message.message'));
        $grid->column('status', __('information::information_message.status.label'))
            ->using(__('information::information_message.status.value'));
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
        $show = new Show(InformationMessage::findOrFail($id));

        $show->field('id', __('information::information_message.id'));
        $show->field('information_friend_id', __('information::information_message.information_friend_id'));
        $show->field('user', __('information::information_message.user_id'))
            ->as(function () {
                return $this->user ? $this->user->username : '';
            });
        $show->field('type', __('information::information_message.type.label'))
            ->using(__('information::information_message.type.value'));
        $show->field('message', __('information::information_message.message'));
        $show->field('status', __('information::information_message.status.label'))
            ->using(__('information::information_message.status.value'));
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
        $form = new Form(new InformationMessage());

        return $form;
    }
}
