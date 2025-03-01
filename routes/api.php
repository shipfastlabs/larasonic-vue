<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiUserController;
use App\Http\Controllers\ApiGuestController;

Route::apiResource('user', ApiUserController::class);
Route::post('check-email', [ApiGuestController::class, 'checkEmail'])->name('api.checkEmail');
Route::post('check-id', [ApiGuestController::class, 'checkId'])->name('api.checkId');