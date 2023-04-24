<?php

use App\Http\Controllers\Sklad;
use Illuminate\Support\Facades\Route;

Route::get('/sklad/page/{id}', [Sklad::class, 'page'])
    ->middleware('auth')
    ->name('sklad');

Route::post('/sklad/add_storage', [Sklad::class, 'add_storage'])
    ->middleware('auth')
    ->name('add_storage');

Route::get('/sklad/storage', [Sklad::class, 'storage'])
    ->middleware('auth')
    ->name('storage');

Route::post('/sklad/storage', [Sklad::class, 'storage_delete'])
    ->middleware('auth')
    ->name('storage_delete');

Route::post('/sklad/edit', [Sklad::class, 'storage_edit'])
    ->middleware('auth')
    ->name('sklad_storage_edit');

Route::get('/sklad/form_send_add_nomenclature', [Sklad::class, 'form_send_add_nomenclature'])
    ->middleware('auth')
    ->name('form_send_add_nomenclature');

Route::post('/sklad/send_add_nomenclature', [Sklad::class, 'send_add_nomenclature'])
    ->middleware('auth')
    ->name('send_add_nomenclature');

Route::get('/sklad/category', [Sklad::class, 'category'])
    ->middleware('auth')
    ->name('category');

Route::get('/sklad/category/{id}', [Sklad::class, 'setcategory'])
    ->middleware('auth')
    ->name('setcategory');

Route::get('/sklad/skladPdf', [Sklad::class, 'skladPdf'])
    ->middleware('auth')
    ->name('skladPdf');

Route::get('/sklad/tmcExcel', [Sklad::class, 'tmcExcel'])
    ->middleware('auth')
    ->name('tmcExcel');

Route::get('/sklad/skladGet', [Sklad::class, 'skladGet'])
    ->middleware('auth')
    ->name('skladGet');

Route::post('/sklad/search', [Sklad::class, 'search'])
    ->middleware('auth')
    ->name('search');

