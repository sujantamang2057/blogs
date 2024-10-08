<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Albumimages extends Model
{
    use HasFactory;

    public function galleryalbum()
    {
        return $this->belongsTo(Album::class, 'album_id');
    }
}
