<?php

namespace LaveroAdmin\Admin\Menus\Controllers;

use LaveroAdmin\Admin\Controllers\HasResourceActions;
use LaveroAdmin\Admin\Form;
use LaveroAdmin\Admin\Layout\Content;
use LaveroAdmin\Admin\Menus\Models\SiteMenu;
use LaveroAdmin\Admin\Menus\Models\SiteMenuItem;
use LaveroAdmin\Admin\Tree;

class MenuItemController
{
    use HasResourceActions;

    public function index(Content $content): Content
    {
        return $content
            ->header(trans('lavero-admin-menus::menus.items'))
            ->description(trans('admin.list'))
            ->body($this->treeView());
    }

    public function create(Content $content): Content
    {
        return $content
            ->header(trans('lavero-admin-menus::menus.items'))
            ->description(trans('admin.create'))
            ->body($this->form());
    }

    public function edit($id, Content $content): Content
    {
        return $content
            ->header(trans('lavero-admin-menus::menus.items'))
            ->description(trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    protected function treeView(): Tree
    {
        $tree = new Tree(new SiteMenuItem());

        $tree->branch(function ($branch) {
            $icon = !empty($branch['icon']) ? "<i class='{$branch['icon']}'></i> " : '';
            $url = !empty($branch['url']) ? " <a href='{$branch['url']}' class='dd-nodrag'>{$branch['url']}</a>" : '';
            $badge = empty($branch['is_active']) ? ' <span class="badge bg-secondary ms-1">off</span>' : '';

            return "{$icon}<strong>{$branch['title']}</strong>{$url}{$badge}";
        });

        return $tree;
    }

    public function form(): Form
    {
        $form = new Form(new SiteMenuItem());

        $form->select('menu_id', trans('lavero-admin-menus::menus.menu'))
            ->options(SiteMenu::pluck('name', 'id'))
            ->rules('required');
        $form->select('parent_id', trans('admin.parent_id'))->options(SiteMenuItem::selectOptions());
        $form->text('title', trans('admin.title'))->rules('required');
        $form->text('url', 'URL')->placeholder('/about');
        $form->select('target', trans('lavero-admin-menus::menus.target'))->options([
            '_self'  => '_self',
            '_blank' => '_blank',
        ])->default('_self');
        $form->icon('icon', trans('admin.icon'))->default('fa-link');
        $form->text('css_class', 'CSS Class');
        $form->switch('is_active', trans('lavero-admin-menus::menus.is_active'))->default(1);

        return $form;
    }
}
