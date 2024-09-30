<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class blog_category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title', // Add 'title' to the fillable array
        // Add other fields as necessary
    ];

    //for the relation to parent class
    public function ParentBlogCategory()
    {
        return $this->belongsTo(blog_category::class, 'parent_id')->withTrashed();
    }

    public function categorycreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function categoryupdatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
