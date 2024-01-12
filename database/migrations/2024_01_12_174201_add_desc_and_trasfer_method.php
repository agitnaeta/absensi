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
        Schema::table('salary_recaps', function (Blueprint $table) {
            $table->longText('desc')->nullable();
            $table->boolean('paid')->default(false);
            $table->enum('method',['transfer','cash'])->default('cash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_recaps', function (Blueprint $table) {
            $table->dropColumn('desc');
            $table->dropColumn('paid');
            $table->dropColumn('method');
        });
    }
};
