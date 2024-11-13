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
    Schema::create('time_slots', function (Blueprint $table) {
        $table->id();
        $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
        $table->time('start_time');
        $table->time('end_time');
        $table->integer('duration')->default(30); // duration in minutes
        $table->boolean('is_available')->default(true);
        $table->date('date');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
