<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->string('qualification')->nullable(); // Add this line
        });
    }

    public function down()
    {
        Schema::table('doctor_profiles', function (Blueprint $table) {
            $table->dropColumn('qualification');
        });
    }
};
