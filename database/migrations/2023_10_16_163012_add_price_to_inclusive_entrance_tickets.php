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
        Schema::table('inclusive_entrance_tickets', function (Blueprint $table) {
            $table->integer('cost_price')->nullable();
            $table->integer('selling_price')->nullable();
            $table->integer('quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inclusive_entrance_tickets', function (Blueprint $table) {
            //
        });
    }
};
