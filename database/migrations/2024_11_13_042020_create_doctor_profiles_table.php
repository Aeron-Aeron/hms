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
            $table->string('qualification');
            $table->string('experience');
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->string('profile_image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_profiles');
    }
};
