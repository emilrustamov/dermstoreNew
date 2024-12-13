<?php

namespace App\Models;

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
        'brands',
        'filters',
    ];

    // Преобразование полей из строки в массив и обратно
    protected $casts = [
        'categories' => 'array',
        'sections' => 'array',
        'subcategories' => 'array',
        'brands' => 'array',
        'filters' => 'array',
    ];

    // Связь с подкатегориями (для удобства работы)
    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'product_subcategory');
    }
}
