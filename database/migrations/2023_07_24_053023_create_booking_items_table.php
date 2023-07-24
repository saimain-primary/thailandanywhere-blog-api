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
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('product_type');
            $table->unsignedBigInteger('product_id');
            $table->date('service_date')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('duration')->nullable();
            $table->string('selling_price')->nullable();
            $table->text('comment')->nullable();
            $table->string('reservation_status')->nullable();
            $table->string('receipt_image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
