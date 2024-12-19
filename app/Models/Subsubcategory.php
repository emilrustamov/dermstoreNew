<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsubcategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'subcategories'];

    // Преобразование `subcategories` из строки в массив и обратно
    protected $casts = [
        'subcategories' => 'array', // Laravel автоматически преобразует JSON в массив
    ];

    // Связь с продуктами
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_subsubcategory');
    }
}
