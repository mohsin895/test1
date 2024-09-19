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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name')->nullable();
            $table->string('phone')->nullable();
            $table->double('age')->nullable();
            $table->unsignedBigInteger('patient_type_id')->nullable();
            $table->unsignedBigInteger('chamber_id')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('slot_id')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('tracking_code')->unique()->nullable();
            $table->unsignedBigInteger('media')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->tinyInteger('present')->default(0);
            $table->tinyInteger('is_running')->default(0);
            $table->tinyInteger('is_next')->default(0);
            $table->tinyInteger('is_report')->default(0);
            $table->enum('status',['yes','no'])->nullable();
            $table->integer('sorting_order')->nullable();
            $table->double('fees')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
