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
        Schema::create('reservation_car_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_item_id')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_contact')->nullable();
            $table->string('car_number')->nullable();
            $table->string('car_photo')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_car_infos');
    }
};
