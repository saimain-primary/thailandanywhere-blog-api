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
        Schema::create('airport_pickup_destinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('airport_pickup_id');
            $table->unsignedBigInteger('destination_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airport_pickup_destinations');
    }
};
