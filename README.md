# Site Menus Builder for LaveroAdmin

Build and manage public site navigation menus (`header`, `footer`, `sidebar`, etc.) with drag-and-drop hierarchy and permanent caching.

## Requirements

- PHP >= 8.1
- `laveroadmin/laveroadmin`: `^1.0`

## Installation

```bash
composer require laveroadmin/menus
php artisan migrate
php artisan admin:import menus
```

## Usage

Use the `site_menu()` helper in Blade templates:

```blade
@foreach(site_menu('header') as $item)
    <a href="{{ $item['url'] }}" target="{{ $item['target'] }}">{{ $item['title'] }}</a>
@endforeach
```

## Features

- Multiple named menus (`site_menus` and `site_menu_items` tables)
- Nested drag-and-drop item ordering, icons, target, and visibility controls
- Cached forever via `Cache::rememberForever` with automatic invalidation on save/delete
- Full English and Russian localization

## License

The MIT License (MIT). See [LICENSE](LICENSE).
