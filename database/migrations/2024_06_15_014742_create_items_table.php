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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('desc')->nullable();
            $table->bigInteger('creator_id');
            $table->tinyInteger('creator_type')->default(1);
            $table->bigInteger('bucks')->default(0);
            $table->bigInteger('bits')->default(0);
            $table->integer('type');
            $table->integer('stock_limit')->default('0');
            $table->integer('special')->default('0');
            $table->timestamp('offsale_at')->nullable();
            $table->text('source');
            $table->text('hash');
            $table->tinyInteger('pending')->default('1');
            $table->tinyInteger('scrubbed')->default('0');
            $table->timestamps();
            $table->timestamp('updated_real')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
