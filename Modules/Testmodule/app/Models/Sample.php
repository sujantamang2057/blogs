<?php

namespace Modules\Testmodule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Testmodule\Database\Factories\SampleFactory;

class Sample extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): SampleFactory
    // {
    //     // return SampleFactory::new();
    // }
}
