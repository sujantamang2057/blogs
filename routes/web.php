<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//dash board route 

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


//authenticate and middleware for profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//For blog post in simple all not using resource controller using simple controler ans route
Route::controller(BlogController::class)->group(function(){
    Route::get('/blogs','index')->name('blogs.index');
    Route::get('/blogs/create','create')->name('blogs.create');
    Route::post('/blogs','store')->name('blogs.store');
    Route::get('/blogs/{product}/edit','edit')->name('blogs.edit');
    Route::put('/blogs/{product}','update')->name('blogs.update');
    Route::delete('/blogs/{product}','destroy')->name('blogs.destroy');    
});


//for category route using resource controller
Route::resource('category', CategoryController::class);
