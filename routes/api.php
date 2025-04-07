<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\AuthController;

Route::fallback(function () {
  return response()->json([
    'status' => 404,
    'message' => 'Endpoint not found.'
  ], 404);
});

Route::get('/', function () {
  return response()->json([
    'success' => "API Tião Carreiro & Pardinho está funcionando",
  ]);
});

Route::get('/musics', [MusicController::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/user', [AuthController::class, 'user']);
  Route::post('/logout', [AuthController::class, 'logout']);

  Route::prefix('admin')->middleware('is_admin')->group(function () {
    Route::get('/isApprove', [MusicController::class, 'musicsApproval']);
    Route::post('/approve/{id}', [MusicController::class, 'approval']);
    Route::delete('/rejecting/{id}', [MusicController::class, 'destroy']);
    Route::patch('/musics/{music}', [MusicController::class, 'update']);
    Route::post('/musics', [MusicController::class, 'storeAdm']);
  });

  Route::apiResource('musics', MusicController::class)->except(['index', 'update', 'destroy']);
});
