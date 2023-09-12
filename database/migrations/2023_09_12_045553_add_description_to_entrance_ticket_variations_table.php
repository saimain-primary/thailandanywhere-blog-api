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
        if (Schema::hasColumn('entrance_ticket_variations', 'entrance_ticket_id')) {

            Schema::table('entrance_ticket_variations', function (Blueprint $table) {
                $table->dropColumn('entrance_ticket_id');
            });
        }

        Schema::table('entrance_ticket_variations', function (Blueprint $table) {
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entrance_ticket_variations', function (Blueprint $table) {
            //
        });
    }
};
