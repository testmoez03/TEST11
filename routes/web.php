<?php

use App\Http\Controllers\ThemeController;

Route::get('/', [ThemeController::class, 'index']);
Route::post('/deploy', [ThemeController::class, 'deploy']);
Route::delete('/subdomain/{id}', [ThemeController::class, 'destroy']);

