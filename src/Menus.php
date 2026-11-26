<?php

namespace LaveroAdmin\Admin\Menus;

use Illuminate\Routing\Router;
use LaveroAdmin\Admin\Admin;
use LaveroAdmin\Admin\Extension;

class Menus extends Extension
{
    public static function boot(): void
    {
        static::registerRoutes();
        Admin::extend('menus', __CLASS__);
    }

    protected static function registerRoutes(): void
    {
        parent::routes(function (Router $router) {
            $router->resource('site-menus/items', 'LaveroAdmin\Admin\Menus\Controllers\MenuItemController', ['as' => 'site-menus']);
            $router->resource('site-menus', 'LaveroAdmin\Admin\Menus\Controllers\MenuController', ['as' => 'site-menus']);
        });
    }

    public static function import(): void
    {
        parent::createMenu('Site Menus', 'site-menus', 'icon-bars', 0, [
            [
                'title' => 'Menus',
                'path'  => 'site-menus',
                'uri'   => 'site-menus',
                'icon'  => 'icon-list',
            ],
            [
                'title' => 'Menu Items',
                'path'  => 'site-menus/items',
                'uri'   => 'site-menus/items',
                'icon'  => 'icon-sitemap',
            ],
        ]);

        parent::createPermission('Site Menus', 'ext.menus', 'site-menus*');
    }
}
