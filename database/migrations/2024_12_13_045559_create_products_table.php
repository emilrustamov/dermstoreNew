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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название товара
            $table->text('description'); // Описание товара
            $table->text('categories')->nullable(); // Связанные категории
            $table->text('sections')->nullable(); // Связанные разделы
            $table->text('subcategories')->nullable(); // Связанные подкатегории
            $table->text('brands')->nullable(); // Связанные бренды
            $table->text('filters')->nullable(); // Связанные фильтры
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
