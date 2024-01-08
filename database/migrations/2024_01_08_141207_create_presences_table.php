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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable(true);
            $table->dateTime('in');
            $table->dateTime('out');
            $table->dateTime('overtime_in');
            $table->dateTime('overtime_out');
            $table->boolean('is_overtime')->default(0);
            $table->boolean('no_record')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
