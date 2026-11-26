<?php

namespace LaveroAdmin\Admin\Menus\Controllers;

use LaveroAdmin\Admin\Controllers\AdminController;
use LaveroAdmin\Admin\Form;
use LaveroAdmin\Admin\Grid;
use LaveroAdmin\Admin\Menus\Models\SiteMenu;
use LaveroAdmin\Admin\Show;

class MenuController extends AdminController
{
    protected function title()
    {
        return trans('lavero-admin-menus::menus.menus');
    }

    protected function grid(): Grid
    {
        $grid = new Grid(new SiteMenu());
        $grid->column('id', __('admin.id'))->sortable();
        $grid->column('name', trans('lavero-admin-menus::menus.name'));
        $grid->column('slug', trans('lavero-admin-menus::menus.slug'))->label('primary');
        $grid->column('is_active', trans('lavero-admin-menus::menus.is_active'))->switch();
        $grid->column('items_count', trans('lavero-admin-menus::menus.items'))->display(fn () => $this->items()->count());

        return $grid;
    }

    protected function detail($id): Show
    {
        $show = new Show(SiteMenu::findOrFail($id));
        $show->field('id', __('admin.id'));
        $show->field('name', trans('lavero-admin-menus::menus.name'));
        $show->field('slug', trans('lavero-admin-menus::menus.slug'));
        $show->field('is_active', trans('lavero-admin-menus::menus.is_active'));
        $show->field('created_at');

        return $show;
    }

    protected function form(): Form
    {
        $form = new Form(new SiteMenu());
        $form->text('name', trans('lavero-admin-menus::menus.name'))->rules('required');
        $form->text('slug', trans('lavero-admin-menus::menus.slug'))->rules('required')->placeholder('header');
        $form->switch('is_active', trans('lavero-admin-menus::menus.is_active'))->default(1);

        return $form;
    }
}
