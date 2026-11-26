<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        if (!Schema::connection($connection)->hasTable('site_menus')) {
            Schema::connection($connection)->create('site_menus', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100)->unique();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }

        if (!Schema::connection($connection)->hasTable('site_menu_items')) {
            Schema::connection($connection)->create('site_menu_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('menu_id')->index();
                $table->unsignedBigInteger('parent_id')->default(0)->index();
                $table->integer('order')->default(0)->index();
                $table->string('title', 150);
                $table->string('url', 500)->nullable();
                $table->string('target', 20)->default('_self');
                $table->string('icon', 80)->nullable();
                $table->string('css_class', 120)->nullable();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();

                $table->index(['menu_id', 'parent_id', 'order'], 'site_menu_items_tree_idx');
            });
        }
    }

    public function down(): void
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        Schema::connection($connection)->dropIfExists('site_menu_items');
        Schema::connection($connection)->dropIfExists('site_menus');
    }
};
