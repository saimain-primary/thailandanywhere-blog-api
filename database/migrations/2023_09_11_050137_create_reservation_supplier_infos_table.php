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
        Schema::create('reservation_supplier_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_item_id')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('ref_number')->nullable();
            $table->string('booking_confirm_letter')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_supplier_infos');
    }
};
