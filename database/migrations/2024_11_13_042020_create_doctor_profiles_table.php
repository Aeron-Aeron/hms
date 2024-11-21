<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('specialization');
            $table->text('bio')->nullable();
            $table->string('education')->nullable();  // Add this line
            $table->integer('experience_years')->nullable();  // Add this line
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_profiles');
    }
};
