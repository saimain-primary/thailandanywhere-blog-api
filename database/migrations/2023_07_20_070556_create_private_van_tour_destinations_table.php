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
        Schema::create('private_van_tour_destinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('private_van_tour_id');
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
        Schema::dropIfExists('private_van_tour_destinations');
    }
};
