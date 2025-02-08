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
    Schema::create('prescription_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
        $table->string('medicine_name');
        $table->string('dosage');
        $table->string('frequency');
        $table->string('duration');
        $table->text('instructions')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::disableForeignKeyConstraints();
    Schema::dropIfExists('prescription_items');
    Schema::enableForeignKeyConstraints();
}
};
