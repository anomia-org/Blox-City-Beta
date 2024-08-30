<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('banner_enabled')->default('0');
            $table->text('banner_color')->nullable();
            $table->text('banner_text')->nullable();
            $table->timestamp('banner_expires')->nullable();
            $table->tinyInteger('market_enabled')->default('1');
            $table->tinyInteger('purchase_items_enabled')->default('1');
            $table->tinyInteger('create_items_enabled')->default('1');
            $table->tinyInteger('groups_enabled')->default('1');
            $table->tinyInteger('avatar_enabled')->default('1');
            $table->tinyInteger('trading_enabled')->default('1');
            $table->tinyInteger('settings_enabled')->default('1');
            $table->tinyInteger('forum_enabled')->default('1');
            $table->tinyInteger('posts_enabled')->default('1');
            $table->tinyInteger('register_enabled')->default('1');
            $table->tinyInteger('maintenance_enabled')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
