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
        Schema::create('private_van_tours', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code')->uniqid();
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('long_description')->nullable();
            $table->string('cover_image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_van_tours');
    }
};
