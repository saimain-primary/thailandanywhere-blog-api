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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('sub_total')->nullable()->after('discount');
            $table->string('grand_total')->nullable()->after('sub_total');
            $table->string('deposit')->nullable()->after('grand_total');
            $table->string('balance_due')->nullable()->after('deposit');
            $table->string('balance_due_date')->nullable()->after('balance_due');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
