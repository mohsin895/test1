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
        Schema::create('slot_modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_id')->constrained('slots')->cascadeOnDelete();
            $table->string('day')->nullable();
            $table->string('from_time')->nullable();
            $table->string('to_time')->nullable();
            $table->integer('limit')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('doctor_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_modifiers');
    }
};
