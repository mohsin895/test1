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
        Schema::create('off_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            // $table->unsignedBigInteger('chamber_id');
            // $table->unsignedBigInteger('slot_id');
            $table->date('date');
            $table->string('day');
            $table->json('chamber_slot')->nullable();
            $table->boolean('is_full_day')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('off_days');
    }
};
