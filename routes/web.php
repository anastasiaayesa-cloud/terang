<?php

declare(strict_types=1);

use App\Http\Controllers\KwitansiController;
use App\Livewire\BuktiPengeluaransList;
use App\Livewire\BuktiPengeluaransUpload;
use App\Livewire\DaftarKegiatans\DaftarKegiatansIndex;
use App\Livewire\DokumenPerencanaans\DokumenPerencanaanForm;
use App\Livewire\DokumenPerencanaans\DokumenPerencanaanIndex;
use App\Livewire\Instansis\InstansisForm;
use App\Livewire\Instansis\InstansisIndex;
use App\Livewire\Kepegawaians\KepegawaiansForm;
use App\Livewire\Kepegawaians\KepegawaiansIndex;
use App\Livewire\LaporanKegiatans\LaporanKegiatanForm;
use App\Livewire\LaporanKegiatans\LaporanKegiatansIndex;
use App\Livewire\PengajuanPencairanDetail;
use App\Livewire\PengajuanPencairanForm;
use App\Livewire\PengajuanPencairansIndex;
use App\Livewire\Perencanaans\PerencanaanForm;
use App\Livewire\Perencanaans\PerencanaansIndex;
use App\Livewire\Persuratans\PersuratansForm;
use App\Livewire\Persuratans\PersuratansIndex;
use App\Livewire\UsulanPegawais\UsulanPegawaisForm;
use App\Livewire\UsulanPegawais\UsulanPegawaisIndex;
use App\Livewire\UsulanPembayaranForm;
use App\Livewire\UsulanPembayaransIndex;
use App\Livewire\Usulans\UsulansForm;
use App\Livewire\Usulans\UsulansIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {

    // Dashboard & Profile
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('profile', 'profile.edit')->name('profile.edit');

    /*
    |--------------------------------------------------------------------------
    | KEPEGAWAIAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('kepegawaians')->name('kepegawaians.')->group(function () {
        Route::livewire('/', KepegawaiansIndex::class)->name('index');
        Route::livewire('/create', KepegawaiansForm::class)->name('create');
        Route::livewire('/{kepegawaian}/edit', KepegawaiansForm::class)->name('edit');
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

    /*
    |--------------------------------------------------------------------------
    | PERENCANAAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('perencanaans')->name('perencanaans.')->group(function () {
        Route::get('/', PerencanaansIndex::class)->name('index');
        Route::get('/create', PerencanaanForm::class)->name('create');
        Route::get('/{perencanaan}/edit', PerencanaanForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | BUKTI PENGELUARAN (Standalone Menu)
    |--------------------------------------------------------------------------
    */
    Route::prefix('bukti-pengeluarans')->name('bukti-pengeluarans.')->group(function () {
        Route::get('/', BuktiPengeluaransList::class)->name('index');
        Route::get('/upload', BuktiPengeluaransUpload::class)->name('upload');
        Route::get('/{bukti}/edit', BuktiPengeluaransUpload::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | USULAN PEMBAYARAN (30% Biaya Penginapan)
    |--------------------------------------------------------------------------
    */
    Route::prefix('usulan-pembayarans')->name('usulan-pembayarans.')->group(function () {
        Route::get('/', UsulanPembayaransIndex::class)->name('index');
        Route::get('/create', UsulanPembayaranForm::class)->name('create');
        Route::get('/{usulanPembayaran}/edit', UsulanPembayaranForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | KEUANGAN - Pengajuan Pencairan Dana
    |--------------------------------------------------------------------------
    */
    Route::prefix('keuangan/pengajuan-pencairans')->name('keuangan.pengajuan-pencairans.')->group(function () {
        Route::get('/', PengajuanPencairansIndex::class)->name('index');
        Route::get('/create', PengajuanPencairanForm::class)->name('create');
        Route::get('/{id}', PengajuanPencairanDetail::class)->name('show');
        Route::get('/{id}/edit', PengajuanPencairanForm::class)->name('edit');
        Route::get('/{id}/print/{jenis}', KwitansiController::class.'@print')->name('print');
    });

    /*
    |--------------------------------------------------------------------------
    | USULAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('usulans')->name('usulans.')->group(function () {
        Route::get('/', UsulansIndex::class)->name('index');
        Route::get('/create', UsulansForm::class)->name('create');
        Route::get('/{usulan}/edit', UsulansForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | PERSURATAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('persuratans')->name('persuratans.')->group(function () {
        Route::get('/', PersuratansIndex::class)->name('index');
        Route::get('/create/{perencanaan_id}', PersuratansForm::class)->name('create');
        Route::get('/{persuratan}/edit', PersuratansForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | USULAN PEGAWAI
    |--------------------------------------------------------------------------
    */
    Route::prefix('usulan-pegawais')->name('usulan-pegawais.')->group(function () {
        Route::get('/', UsulanPegawaisIndex::class)->name('index');
        Route::get('/create/{usulan_id?}', UsulanPegawaisForm::class)->name('create');
        Route::get('/{usulanPegawai}/edit', UsulanPegawaisForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | DAFTAR KEGIATAN
    |--------------------------------------------------------------------------
    */
    Route::get('/daftar-kegiatans', DaftarKegiatansIndex::class)->name('daftar-kegiatans.index');

    /*
    |--------------------------------------------------------------------------
    | INSTANSI
    |--------------------------------------------------------------------------
    */
    Route::prefix('instansis')->name('instansis.')->group(function () {
        Route::get('/', InstansisIndex::class)->name('index');
        Route::get('/create', InstansisForm::class)->name('create');
        Route::get('/{instansi}/edit', InstansisForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | LAPORAN KEGIATAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('laporan-kegiatans')->name('laporan-kegiatans.')->group(function () {
        Route::get('/', LaporanKegiatansIndex::class)->name('index');
        Route::get('/create/{usulanId?}/{pegawaiId?}', LaporanKegiatanForm::class)->name('create');
        Route::get('/{laporanKegiatan}/edit', LaporanKegiatanForm::class)->name('edit');
    });
});

require __DIR__.'/auth.php';
