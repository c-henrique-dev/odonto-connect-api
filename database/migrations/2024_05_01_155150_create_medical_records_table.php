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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->date('record_date');
            $table->unsignedBigInteger('patient_id');
            $table->text('description');
            $table->string('attachment');
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->unique('patient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
