<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blog_category extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', // Add 'title' to the fillable array
        // Add other fields as necessary
    ];
    //for the relation to parent class
      public function ParentBlogCategory()
    {
        return $this->belongsTo(blog_category::class, 'parent_id');
    }
}
