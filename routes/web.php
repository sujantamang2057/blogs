<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::controller(BlogController::class)->group(function(){
    Route::get('/blogs','index')->name('blogs.index');
    Route::get('/blogs/create','create')->name('blogs.create');
    Route::post('/blogs','store')->name('blogs.store');
    Route::get('/blogs/{product}/edit','edit')->name('blogs.edit');
    Route::put('/blogs/{product}','update')->name('blogs.update');
    Route::delete('/blogs/{product}','destroy')->name('blogs.destroy');    
});


