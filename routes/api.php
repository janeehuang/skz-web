<?php

// include 'api/v1/linemesRoute.php';

use App\Http\Controllers\Api\V1\LinemesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/index', [LinemesController::class, 'index']);
Route::post('/webhook', [LinemesController::class, 'webhook']);