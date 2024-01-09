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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable(false);
            $table->time('in',1)->default(0);
            $table->time('out',1)->default(0);
            $table->time('over_in',1)->default(0);
            $table->time('over_out',1)->default(0);
            $table->integer('off_day_per_month')->default(0);
            $table->bigInteger('fine_per_minute')->default(0);
            $table->enum('day_off',["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"])
                  ->default("sunday");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};