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
        Schema::create('group_tours', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code')->unique();
            $table->string('name');
            $table->string("description")->nullable();
            $table->string('cover_image')->nullable();
            $table->string('price')->nullable();
            $table->unsignedBigInteger('cancellation_policy_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_tours');
    }
};
