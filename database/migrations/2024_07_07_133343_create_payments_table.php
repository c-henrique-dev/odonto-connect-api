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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('scheduling_id')->unique();
            $table->date('payment_date');
            $table->string('payment_reason');
            $table->decimal('amount', 8, 2);
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('scheduling_id')->references('id')->on('schedulings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
