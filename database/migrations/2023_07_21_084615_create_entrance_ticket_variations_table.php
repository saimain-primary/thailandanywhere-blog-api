<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entrance_ticket_variations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('price_name')->nullable();
            $table->string('cost_price')->nullable();
            $table->string('price')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrance_ticket_variations');
    }
};
