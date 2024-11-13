<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('comment');
            $table->integer('rating');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('testimonials');
    }
};
