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
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('topic_id');
            $table->text('title');
            $table->longText('body');
            $table->bigInteger('views')->default('0');
            $table->boolean('scrubbed')->default(false);
            $table->boolean('pinned')->default(false);
            $table->boolean('locked')->default(false);
            $table->boolean('stuck')->default(false);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('last_reply')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threads');
    }
};
