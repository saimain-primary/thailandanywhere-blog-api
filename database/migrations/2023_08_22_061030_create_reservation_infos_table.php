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
        Schema::create('reservation_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_item_id')->nullable();
            $table->string('customer_feedback')->nullable();
            $table->string('customer_score')->nullable();
            $table->string('special_request')->nullable();
            $table->string('other_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_infos');
    }
};
