<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('health_problem_id')->constrained()->onDelete('cascade');
        $table->dateTime('scheduled_time');
        $table->dateTime('proposed_time')->nullable();
        $table->string('status')->default('pending'); // pending, accepted, declined, rescheduled, completed
        $table->text('notes')->nullable();
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
