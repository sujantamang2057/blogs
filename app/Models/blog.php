<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class blog extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function blogCategory()
    {
        return $this->belongsTo(blog_category::class, 'blog_category_id')->withTrashed();
    }

    public function blogcreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function blogupdatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
