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
        Schema::create('ip_bans', function (Blueprint $table) {
            $table->id();
            $table->longText('ip');
            $table->bigInteger('admin_id')->unsigned();
            $table->longText('reason');
            $table->timestamps();
            $table->timestamp('expires_at')->nullable();
            $table->tinyInteger('active')->default('1');
        });

        Schema::table('ip_bans', function (Blueprint $table) {
            $table->foreign('admin_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_bans');
    }
};
