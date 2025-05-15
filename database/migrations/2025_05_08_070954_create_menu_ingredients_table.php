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
        Schema::create('menu_ingredients', function (Blueprint $table) {
            $table->id('menu_ingredient_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->foreign('item_id')->references('item_id')->on('menu_items');
            $table->foreign('ingredient_id')->references('ingredient_id')->on('ingredients');
            $table->decimal('qty', 10, 2)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_ingredients');
    }
};
