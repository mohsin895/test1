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
        Schema::create('serialized_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointments_id');
            $table->foreignId('doctor_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serialized_histories');
    }
};
