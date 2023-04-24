<?php

use App\Http\Controllers\ControllerDf;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

require __DIR__.'/sklad.php';

require __DIR__.'/nomenclature.php';

require __DIR__.'/profile.php';

Route::get('/', function () {
    return view('welcome');
})->middleware(['auth'])->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/downloadSklad', [ControllerDf::class, 'downloadSklad'])
    ->middleware('auth')
    ->name('downloadSklad');
    