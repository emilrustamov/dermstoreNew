<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'selected_links'];

    
    // public function categories()
    // {
    //     return $this->hasMany(Category::class);
    // }
    public function categories()
    {
        return Category::where('sections', 'LIKE', '%"'.(string) $this->id.'"%')->get();
    }
    

}
