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
    Schema::table('doctor_ratings', function (Blueprint $table) {
        $table->integer('helpful_votes')->default(0);
        $table->integer('total_votes')->default(0);
        $table->boolean('verified_appointment')->default(true);
        $table->timestamp('review_date')->useCurrent();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
