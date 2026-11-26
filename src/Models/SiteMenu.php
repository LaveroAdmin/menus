<?php

namespace LaveroAdmin\Admin\Menus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SiteMenu extends Model
{
    public const CACHE_PREFIX = 'lavero_site_menu_';

    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('admin.database.connection') ?: config('database.default'));
    }

    public function items(): HasMany
    {
        return $this->hasMany(SiteMenuItem::class, 'menu_id')->orderBy('order')->orderBy('id');
    }

    public static function getTreeBySlug(string $slug): array
    {
        try {
            return Cache::rememberForever(self::CACHE_PREFIX . $slug, function () use ($slug): array {
                $instance = new static();
                if (!Schema::connection($instance->getConnectionName())->hasTable('site_menus')) {
                    return [];
                }

                $menu = static::where('slug', $slug)->where('is_active', true)->first();
                if (!$menu) {
                    return [];
                }

                $items = SiteMenuItem::where('menu_id', $menu->id)
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->orderBy('id')
                    ->get()
                    ->toArray();

                return static::buildBranch($items, 0);
            });
        } catch (\Throwable) {
            return [];
        }
    }

    protected static function buildBranch(array $items, int $parentId): array
    {
        $branch = [];
        foreach ($items as $item) {
            if ((int) $item['parent_id'] === $parentId) {
                $children = static::buildBranch($items, (int) $item['id']);
                $item['children'] = $children;
                $branch[] = $item;
            }
        }

        return $branch;
    }

    public static function flushCacheForSlug(?string $slug): void
    {
        if (!$slug) {
            return;
        }
        try {
            Cache::forget(self::CACHE_PREFIX . $slug);
        } catch (\Throwable) {
        }
    }

    protected static function booted(): void
    {
        static::saved(fn (self $m) => static::flushCacheForSlug($m->slug));
        static::deleted(fn (self $m) => static::flushCacheForSlug($m->slug));
    }
}
