<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;

Route::get('/', [SpotifyController::class, 'index']);

Route::get('/spotifries', [SpotifyController::class, 'authorize']);

Route::get('/song', [SpotifyController::class, 'getSong']);
