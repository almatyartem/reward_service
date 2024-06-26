<?php

use App\Http\Controllers\RewardController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Support\Facades\Route;


Route::middleware(EnsureTokenIsValid::class)->group(function(){
    Route::get('/healthcheck', function(){
        return response()->json(['success' => true]);
    });
    Route::resource('reward', RewardController::class, [
        'except' => ['index', 'edit', 'create']
    ]);
    Route::post('/reward/{id}/attach_to_user', [RewardController::class, 'attachToUser']);
});

