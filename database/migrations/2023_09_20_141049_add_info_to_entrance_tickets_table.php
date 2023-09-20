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
        Schema::table('entrance_tickets', function (Blueprint $table) {
            $table->string('place')->nullable();
            $table->string('legal_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('account_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entrance_tickets', function (Blueprint $table) {
            //
        });
    }
};
