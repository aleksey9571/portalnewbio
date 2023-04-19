<?php

use App\Http\Controllers\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/profile/{id}', [Profile::class, 'profile'])
    ->middleware('auth')
    ->name('profile');
