<?php

use App\Http\Controllers\BlogCategoryController;
use App\Http\Middleware\VerifyIdMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/customers', [BlogCategoryController::class, 'apiIndex']);
Route::get('/customers/{id}', [BlogCategoryController::class, 'apishow'])->middleware(VerifyIdMiddleware::class);
Route::post('/customers', [BlogCategoryController::class, 'apistore']);
Route::put('/customers/{id}', [BlogCategoryController::class, 'apiupdate']);
Route::delete('/customers/{id}', [BlogCategoryController::class, 'apidelete']);

Route::get('/test', function () {
    return response()->json([
        'status' => true,
        'message' => 'Test successful',
        'data' => ['name' => 'John Doe here is', 'email' => 'johndoe@example.com'],
    ]);
});
