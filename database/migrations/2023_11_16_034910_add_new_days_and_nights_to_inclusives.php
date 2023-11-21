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
        Schema::table('inclusives', function (Blueprint $table) {
            $table->integer('day')->default(1)->after('agent_price');
            $table->integer('night')->nullable()->after('day');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inclusives', function (Blueprint $table) {
            $table->integer('day');
            $table->integer('night');
            
        });
    }
};
