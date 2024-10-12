<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_food_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id');
            $table->foreign('food_id')->references('id')->on('user_foods')->onDelete('cascade');
            $table->foreignId('material_id');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->enum('amount', ['1', '2', '3']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_food_materials');
    }
};
