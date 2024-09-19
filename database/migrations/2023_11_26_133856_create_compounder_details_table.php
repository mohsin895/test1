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
        Schema::create('compounder_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compounder_id')->constrained('users');
            $table->foreignId('chamber_id')->constrained('chambers');
            $table->foreignId('slot_id')->constrained('slots');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compounder_details');
    }
};
