<?php

namespace Qihucms\Information\Controllers\Admin;

use App\Admin\Controllers\Controller;
use App\Models\User;
use Qihucms\Information\Models\InformationFriendPolicy;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FriendPolicyController extends Controller
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '好友验证';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new InformationFriendPolicy());

        $grid->model()->latest();

        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->equal('user_id', __('information::information_friend_policy.user_id'));
        });

        $grid->column('user_id', __('information::information_friend_policy.user_id'));
        $grid->column('question', __('information::information_friend_policy.question'));
        $grid->column('answer', __('information::information_friend_policy.answer'));
        $grid->column('password', __('information::information_friend_policy.password'));
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
        $show = new Show(InformationFriendPolicy::findOrFail($id));

        $show->field('id', __('information::information_friend_policy.id'));
        $show->field('user_id', __('information::information_friend_policy.user_id'));
        $show->field('question', __('information::information_friend_policy.question'));
        $show->field('answer', __('information::information_friend_policy.answer'));
        $show->field('password', __('information::information_friend_policy.password'));
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
        $form = new Form(new InformationFriendPolicy());

        $form->select('user_id', __('information::information_friend_policy.user_id'))
            ->options(function ($use_id) {
                $model = User::find($use_id);
                if ($model) {
                    return [$model->id => $model->username];
                }
            })
            ->ajax(route('admin.select.user'))
            ->rules('required');
        $form->text('question', __('information::information_friend_policy.question'));
        $form->text('answer', __('information::information_friend_policy.answer'));
        $form->text('password', __('information::information_friend_policy.password'));

        return $form;
    }
}
