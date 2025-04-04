<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicController;

Route::get('/', function () {
  return response()->json([
    'success' => "API Tião Carreiro & Pardinho está funcionando",
  ]);
});

Route::apiResource('musics', MusicController::class);
