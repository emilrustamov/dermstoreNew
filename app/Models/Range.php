<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Range extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brands'];

 
    protected $casts = [
        'brands' => 'array', 
    ];

    // Связь с продуктами
    // public function products()
    // {
    //     return $this->belongsToMany(Product::class, 'product_range');
    // }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
