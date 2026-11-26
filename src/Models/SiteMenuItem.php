<?php

namespace LaveroAdmin\Admin\Menus\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LaveroAdmin\Admin\Traits\ModelTree;

class SiteMenuItem extends Model
{
    use ModelTree;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'order',
        'title',
        'url',
        'target',
        'icon',
        'css_class',
        'is_active',
    ];

    protected $casts = [
        'menu_id'   => 'integer',
        'parent_id' => 'integer',
        'order'     => 'integer',
        'is_active' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('admin.database.connection') ?: config('database.default'));
        $this->setTitleColumn('title');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(SiteMenu::class, 'menu_id');
    }

    protected static function booted(): void
    {
        $flush = static function (self $item): void {
            $menu = SiteMenu::find($item->menu_id);
            if ($menu) {
                SiteMenu::flushCacheForSlug($menu->slug);
            }
        };

        static::saved($flush);
        static::deleted($flush);
    }
}
