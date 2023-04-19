<?php

use App\Http\Controllers\Nomenclature;
use Illuminate\Support\Facades\Route;

Route::get('/nomenclature/page/{id}', [Nomenclature::class, 'page'])
    ->middleware('auth')
    ->name('nomenclature');

Route::post('/nomenclature/add_storage', [Nomenclature::class, 'add_storage'])
    ->middleware('auth')
    ->name('nomenclature_add_storage');

Route::get('/nomenclature/storage', [Nomenclature::class, 'storage'])
    ->middleware('auth')
    ->name('nomenclature_storage');

Route::post('/nomenclature/storage', [Nomenclature::class, 'storage_delete'])
    ->middleware('auth')
    ->name('nomenclature_storage_delete');

Route::post('/nomenclature/edit', [Nomenclature::class, 'storage_edit'])
    ->middleware('auth')
    ->name('nomenclature_storage_edit');

Route::get('/nomenclature/form_send_add_nomenclature', [Nomenclature::class, 'form_send_add_nomenclature'])
    ->middleware('auth')
    ->name('nomenclature_form_send_add_nomenclature');

Route::post('/nomenclature/send_add_nomenclature', [Nomenclature::class, 'send_add_nomenclature'])
    ->middleware('auth')
    ->name('nomenclature_send_add_nomenclature');

Route::get('/nomenclature/category', [Nomenclature::class, 'category'])
    ->middleware('auth')
    ->name('nomenclature_category');

Route::get('/nomenclature/category/{id}', [Nomenclature::class, 'setcategory'])
    ->middleware('auth')
    ->name('nomenclature_setcategory');

Route::get('/nomenclature/nomenclatureExcel', [Nomenclature::class, 'nomenclatureExcel'])
    ->middleware('auth')
    ->name('nomenclature_nomenclatureExcel');

Route::get('/nomenclature/tmcExcel', [Nomenclature::class, 'pdf'])
    ->middleware('auth')
    ->name('nomenclature_tmcExcel');

Route::get('/nomenclature/nomenclatureGet', [Nomenclature::class, 'nomenclatureGet'])
    ->name('nomenclature_nomenclatureGet');

Route::post('/nomenclature/search', [Nomenclature::class, 'search'])
    ->middleware('auth')
    ->name('nomenclature_search');

Route::get('/nomenclature/requests', [Nomenclature::class, 'requests'])
    ->middleware('auth')
    ->name('nomenclature_requests');

Route::get('/nomenclature/requests/{id}', [Nomenclature::class, 'requests_get'])
    ->middleware('auth')
    ->name('nomenclature_requestsget');

Route::post('/nomenclature/storage/expl', [Nomenclature::class, 'obosnovaniye'])
    ->middleware('auth')
    ->name('nomenclature_expl');

Route::get('/nomenclature/storage/pdf', [Nomenclature::class, 'pdf'])
    ->middleware('auth')
    ->name('nomenclature_pdf');

Route::get('/nomenclature/storage/clear', [Nomenclature::class, 'clearStorage'])
    ->middleware('auth')
    ->name('nomenclature_clearstorage');

Route::get('/nomenclature/storage/clearagreedone', [Nomenclature::class, 'clearagreedone'])
    ->middleware('auth')
    ->name('nomenclature_clearagreedone');

Route::get('/nomenclature/storage/clearagreedtwo', [Nomenclature::class, 'clearagreedtwo'])
    ->middleware('auth')
    ->name('nomenclature_clearagreedtwo');

Route::get('/nomenclature/storage/clearagreedthree', [Nomenclature::class, 'clearagreedthree'])
    ->middleware('auth')
    ->name('nomenclature_clearagreedthree');

Route::get('/nomenclature/repeat/{id}', [Nomenclature::class, 'repeatTmc'])
    ->middleware('auth')
    ->name('nomenclature_repeatTmc');
