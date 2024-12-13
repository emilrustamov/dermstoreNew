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
            $table->json('categories')->nullable(); // Связанные категории
            $table->json('sections')->nullable(); // Связанные разделы
            $table->json('subcategories')->nullable(); // Связанные подкатегории
            $table->json('brands')->nullable(); // Связанные бренды
            $table->json('filters')->nullable(); // Связанные фильтры
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
