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
            $table->integer("extra_time")->default(0);
            $table->integer("extra_time_amount")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_recaps', function (Blueprint $table) {
            $table->dropColumn("extra_time");
            $table->dropColumn("extra_time_amount");
        });
    }
};
