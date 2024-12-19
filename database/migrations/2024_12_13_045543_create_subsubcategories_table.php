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
        Schema::create('subsubcategories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название подподкатегории
            $table->string('subcategories')->nullable(); // ID подкатегорий, записанные через запятую
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subsubcategories');
    }
};
