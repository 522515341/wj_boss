<?php

namespace Weigatherboss\BossLogin\Http\Controllers\Admin;

use Weigatherboss\BossLogin\Models\AdminBossLogin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\MessageBag;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class BossAdminController extends AdminController
{
    public function index(Content $content)
    {
        return $content
            ->title('总码')
            ->description('管理员登陆列表')
            ->body($this->bossGrid());
    }

    public function bossGrid()
    {
        $grid = new Grid(new AdminBossLogin());

        $grid->disableFilter();
        // $grid->disableCreateButton();
        $grid->column('id', __('Id'));
        $grid->column('user',__('管理员账号'))->display(function(){
            return $this->be_one_admin['username'];
        });
        $grid->column('admin_id',__('管理员名称'))->display(function(){
            return $this->be_one_admin['name'];
        });
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('修改时间'));
        $grid->actions(function(Grid\Displayers\Actions $actions){
            $actions->disableView();
            $actions->disableEdit();
        });
        return $grid;
    }

    public function form()
    {
        $form = new Form(new AdminBossLogin());
        $form->select('admin_id',__('管理员'))->options(function(){
            $userModel = config('admin.database.users_model');
            return $userModel::pluck('name','id');
        });
        $form->submitted(function(Form $form){
            $admin_id = Request()->admin_id;
            if(AdminBossLogin::where('admin_id','=',$admin_id)->count() > 0){
                $error = new MessageBag([
                    'title'   => '错误提示',
                    'message' => '该管理员已经添加过了！',
                ]);
                return back()->with(compact('error'));
            }
        });
        return $form;
    }
}