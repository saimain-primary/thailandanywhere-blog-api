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
        Schema::create('inclusive_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inclusive_id');
            $table->string('product_type');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('car_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inclusive_products');
    }
};
