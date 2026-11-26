<?php

use LaveroAdmin\Admin\Menus\Models\SiteMenu;

if (!function_exists('site_menu')) {
    /**
     * Get cached nested menu items by menu slug.
     */
    function site_menu(string $slug): array
    {
        return SiteMenu::getTreeBySlug($slug);
    }
}
