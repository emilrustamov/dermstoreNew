<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'categories',
        'sections',
        'subcategories',
        'subsubcategories', // Add this line
        'brands',
        'filters',
        'image',
        'characteristics',
        'ranges',
    ];

    // Преобразование полей из строки в массив и обратно
    protected $casts = [
        'categories' => 'array',
        'sections' => 'array',
        'subcategories' => 'array',
        'subsubcategories' => 'array', // Add this line
        'brands' => 'array',
        'filters' => 'array',
        'characteristics' => 'array',
        'ranges' => 'array',
    ];

    // Связь с подкатегориями (для удобства работы)
    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'product_subcategory');
    }

    // Связь с подподкатегориями (для удобства работы)
    public function subsubcategories()
    {
        return $this->belongsToMany(Subsubcategory::class, 'product_subsubcategory');
    }

    // Define the relationship with Characteristic
    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class);
    }
}
