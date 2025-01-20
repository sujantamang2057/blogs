<?php

use Illuminate\Support\Facades\Route;
use Modules\Testmodule\Http\Controllers\TestmoduleController;
use Modules\Testmodule\app\Http\Controllers\SampleController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::resource('testmodule', TestmoduleController::class)->names('testmodule');
});

Route::get('/sample', [SampleController::class, 'index'])->name('sample.index');

