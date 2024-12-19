<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sections', 'selected_links'];

  
    protected $casts = [
        'sections' => 'array', 
    ];

    // Связь с подкатегориями
    public function subcategories()
    {
        return Subcategory::where('categories', 'LIKE', '%"'.(string) $this->id.'"%')->get();
    }
    

    
}
