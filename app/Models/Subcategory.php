<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'categories', 'selected_links'];

    // Преобразование `categories` из строки в массив и обратно
    protected $casts = [
        'categories' => 'array', // Laravel автоматически преобразует JSON в массив
    ];

    // Связь с продуктами
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_subcategory');
    }

    public function subsubcategories()
    {
        return Subsubcategory::where('subcategories', 'LIKE', '%"'.(string) $this->id.'"%')->get();
    }
}
