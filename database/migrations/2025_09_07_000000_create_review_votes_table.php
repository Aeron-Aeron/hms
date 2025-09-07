<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('review_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_rating_id')->constrained('doctor_ratings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_helpful')->default(true);
            $table->timestamps();

            $table->unique(['doctor_rating_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('review_votes');
    }
};
