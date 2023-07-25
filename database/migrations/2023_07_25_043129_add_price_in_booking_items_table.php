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
        Schema::table('booking_items', function (Blueprint $table) {
            $table->string('cost_price')->nullable()->after('receipt_image');
            $table->string('payment_status')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('confirmation_letter')->nullable();
            $table->string('exchange_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_items', function (Blueprint $table) {
            //
        });
    }
};
