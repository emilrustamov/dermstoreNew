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
            $table->string('name'); 
            $table->text('description'); 
            $table->text('categories')->nullable(); 
            $table->text('sections')->nullable(); 
            $table->text('subcategories')->nullable(); 
            $table->text('brands')->nullable();
            $table->text('filters')->nullable(); 
            $table->text('ranges')->nullable(); 
            $table->text('characteristics')->nullable(); 
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
