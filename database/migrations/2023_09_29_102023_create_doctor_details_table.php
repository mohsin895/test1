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
        Schema::create('doctor_details', function (Blueprint $table) {
            $table->id();
            $table->string('experience')->nullable();
            $table->double('age')->nullable();
            $table->string('bmdc')->nullable();
            $table->boolean('publish_bmdc')->default(true);
            $table->json('trainings')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->unsignedBigInteger('medical_id')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->double('old_fees')->default(0);
            $table->double('new_fees')->default(0);
            $table->double('report_fees')->default(0);
            $table->json('weekdays')->nullable();
            $table->tinyInteger('is_combine')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_details');
    }
};
