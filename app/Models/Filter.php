<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'values'];

    // Преобразование `values` из строки в массив и обратно
    protected $casts = [
        'values' => 'array', // Laravel автоматически преобразует JSON в массив
    ];
}
