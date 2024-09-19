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
        Schema::create('visitation_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->string('day')->nullable();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('slot_id')->nullable();
            $table->unsignedBigInteger('doctor_chamber_id')->nullable();
            $table->integer('status')->default(1);
            $table->text('note')->nullable();
            $table->string('break_duration')->nullable();
            $table->timestamp('break_start_at')->nullable();
            $table->string('break_type')->nullable();
            $table->string('current_serial')->nullable();
            $table->string('last_serial')->nullable();
            $table->enum('is_running',['yes','no'])->default('no');
            $table->enum('update_status',['Edit','Stoped'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitation_tracks');
    }
};
