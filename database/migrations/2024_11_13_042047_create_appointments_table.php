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
        $table->dateTime('scheduled_time');
        $table->dateTime('proposed_time')->nullable();
        $table->string('status')->default('pending'); // pending, accepted, declined, rescheduled, completed
        $table->text('patient_notes')->nullable(); // For patient to describe their problem
        $table->text('doctor_notes')->nullable(); // For doctor's comments
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('appointments');
}
};
