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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_url')->default('6279053fe3b87737fc2a0ee42a351084723f19524985ada99deb76aa5cadd97d');
            $table->timestamp('avatar_render')->useCurrent();
            $table->string('headshot_url')->default('8d6abd3804474531e7157dca9304975f7cbf68d50f4082613ee9ab256d85bf63');
            $table->timestamp('headshot_render')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
