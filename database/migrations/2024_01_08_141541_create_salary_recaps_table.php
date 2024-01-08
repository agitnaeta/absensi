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
        Schema::create('salary_recaps', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable(false);
            $table->string("recap_month")->nullable(false);
            $table->integer("work_day");
            $table->integer("late_day");
            $table->bigInteger("salary_amount");
            $table->bigInteger("overtime_amount");
            $table->bigInteger("loan_cut");
            $table->bigInteger("late_cut");
            $table->bigInteger("abstain_cut");
            $table->bigInteger("received");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_recaps');
    }
};
