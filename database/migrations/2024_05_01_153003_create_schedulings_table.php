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
        Schema::create('schedulings', function (Blueprint $table) {
            $table->id();
            $table->string('treatment_type');
            $table->text('comment');
            $table->dateTime('date_time');
            $table->string('status');
            $table->string('payment_status');
            $table->unsignedBigInteger('dentist_id');
            $table->unsignedBigInteger('patient_id');
            $table->timestamps();

            $table->foreign('dentist_id')->references('id')->on('dentists');
            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedulings');
    }
};
