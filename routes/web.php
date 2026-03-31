<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Kepegawaians\KepegawaiansForm;
use App\Livewire\Kepegawaians\KepegawaiansIndex;
use App\Livewire\DokumenPerencanaans\DokumenPerencanaanIndex;
use App\Livewire\DokumenPerencanaans\DokumenPerencanaanForm;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {

    // Dashboard & Profile
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('profile', fn() => 'Profile Page')->name('profile.edit');

    /*
    |--------------------------------------------------------------------------
    | KEPEGAWAIAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('kepegawaians')->name('kepegawaians.')->group(function () {
        Route::get('/', KepegawaiansIndex::class)->name('index');
        Route::get('/create', KepegawaiansForm::class)->name('create');
        Route::get('/{kepegawaian}/edit', KepegawaiansForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | DOKUMEN PERENCANAAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('dokumen-perencanaans')->name('dokumen-perencanaans.')->group(function () {
        
        // INDEX
        Route::get('/', DokumenPerencanaanIndex::class)->name('index');

        // CREATE
        Route::get('/create', DokumenPerencanaanForm::class)->name('create');

        // EDIT
        Route::get('/{dokumenperencanaan_id}/edit', DokumenPerencanaanForm::class)->name('edit');
    });
});

require __DIR__ . '/auth.php';
