<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inclusive_airline_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inclusive_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->integer('cost_price')->nullable();
            $table->integer('selling_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inclusive_airline_tickets');
    }
};
