<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    
    // public function categories()
    // {
    //     return $this->hasMany(Category::class);
    // }
    public function categories()
    {
        return Category::whereJsonContains('sections', (string) $this->id)->get();
    }
    

}
