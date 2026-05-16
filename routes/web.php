<?php

declare(strict_types=1);

use App\Http\Controllers\KeuanganPreviewController;
use App\Livewire\Admin\ManajemenAkses;
use App\Livewire\Admin\RoleManager;
use App\Livewire\BuktiPengeluaransList;
use App\Livewire\BuktiPengeluaransUpload;
use App\Livewire\DaftarKegiatans\DaftarKegiatansIndex;
use App\Livewire\DokumenPerencanaans\DokumenPerencanaanForm;
use App\Livewire\DokumenPerencanaans\DokumenPerencanaanIndex;
use App\Livewire\Instansis\InstansisForm;
use App\Livewire\Instansis\InstansisIndex;
use App\Livewire\Kepegawaians\KepegawaiansForm;
use App\Livewire\Kepegawaians\KepegawaiansIndex;
use App\Livewire\Keuangans\KeuanganForm;
use App\Livewire\Keuangans\KeuangansBayar;
// use App\Livewire\PengajuanPencairanDetail;
// use App\Livewire\PengajuanPencairanForm;
// use App\Livewire\PengajuanPencairansIndex;
use App\Livewire\Keuangans\KeuangansIndex;
use App\Livewire\Keuangans\KeuangansPreview;
use App\Livewire\LaporanKegiatans\LaporanKegiatansForm;
use App\Livewire\LaporanKegiatans\LaporanKegiatansIndex;
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
    Route::prefix('kepegawaians')->name('kepegawaians.')->middleware(['auth', 'can:kepegawaian.view'])->group(function () {
        Route::livewire('/', KepegawaiansIndex::class)->name('index');
        Route::livewire('/create', KepegawaiansForm::class)->name('create');
        Route::livewire('/{kepegawaian}/edit', KepegawaiansForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | DOKUMEN PERENCANAAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('dokumen-perencanaans')->name('dokumen-perencanaans.')->middleware(['auth', 'can:dokumen-perencanaan.view'])->group(function () {

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
    Route::prefix('perencanaans')->name('perencanaans.')->middleware(['auth', 'can:perencanaan.view'])->group(function () {
        Route::get('/', PerencanaansIndex::class)->name('index');
        Route::get('/create', PerencanaanForm::class)->name('create');
        Route::get('/{perencanaan}/edit', PerencanaanForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | BUKTI PENGELUARAN (Standalone Menu)
    |--------------------------------------------------------------------------
    */
    Route::prefix('bukti-pengeluarans')->name('bukti-pengeluarans.')->middleware(['auth', 'can:bukti-pengeluaran.view'])->group(function () {
        Route::get('/', BuktiPengeluaransList::class)->name('index');
        Route::get('/upload', BuktiPengeluaransUpload::class)->name('upload');
        Route::get('/{bukti}/edit', BuktiPengeluaransUpload::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | USULAN PEMBAYARAN (30% Biaya Penginapan)
    |--------------------------------------------------------------------------
    */
    Route::prefix('usulan-pembayarans')->name('usulan-pembayarans.')->middleware(['auth', 'can:usulan-pembayaran.view'])->group(function () {
        Route::get('/', UsulanPembayaransIndex::class)->name('index');
        Route::get('/create', UsulanPembayaranForm::class)->name('create');
        Route::get('/{usulanPembayaran}/edit', UsulanPembayaranForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | KEUANGAN - Pengajuan Pencairan Dana
    |--------------------------------------------------------------------------
    */
    Route::prefix('keuangans')->name('keuangans.')->middleware(['auth', 'can:keuangan.view'])->group(function () {
        Route::get('/', KeuangansIndex::class)->name('index');
        Route::get('/create', KeuanganForm::class)->name('create');
        Route::get('/{usulan_id}/{pegawai_id}/bayar', KeuangansBayar::class)->name('bayar');
        Route::get('/{usulan_id}/{pegawai_id}/preview', KeuangansPreview::class)->name('preview');
        Route::get('/{usulan_id}/{pegawai_id}/preview/cetak', [KeuanganPreviewController::class, 'cetak'])->name('preview.cetak');
    });

    /*
    |--------------------------------------------------------------------------
    | USULAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('usulans')->name('usulans.')->middleware(['auth', 'can:usulan.view'])->group(function () {
        Route::get('/', UsulansIndex::class)->name('index');
        Route::get('/create', UsulansForm::class)->name('create');
        Route::get('/{usulan}/edit', UsulansForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | PERSURATAN
    |--------------------------------------------------------------------------
    */
    Route::prefix('persuratans')->name('persuratans.')->middleware(['auth', 'can:persuratan.view'])->group(function () {
        Route::get('/', PersuratansIndex::class)->name('index');
        Route::get('/create/{perencanaan_id}', PersuratansForm::class)->name('create');
        Route::get('/{persuratan}/edit', PersuratansForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | USULAN PEGAWAI
    |--------------------------------------------------------------------------
    */
    Route::prefix('usulan-pegawais')->name('usulan-pegawais.')->middleware(['auth', 'can:usulan-pegawai.view'])->group(function () {
        Route::get('/', UsulanPegawaisIndex::class)->name('index');
        Route::get('/create/{usulan_id?}', UsulanPegawaisForm::class)->name('create');
        Route::get('/{usulanPegawai}/edit', UsulanPegawaisForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | DAFTAR KEGIATAN
    |--------------------------------------------------------------------------
    */
    Route::get('/daftar-kegiatans', DaftarKegiatansIndex::class)->middleware(['auth', 'can:daftar-kegiatans.view'])->name('daftar-kegiatans.index');

    /*
    |--------------------------------------------------------------------------
    | INSTANSI
    |--------------------------------------------------------------------------
    */
    Route::prefix('instansis')->name('instansis.')->middleware(['auth', 'can:instansi.view'])->group(function () {
        Route::get('/', InstansisIndex::class)->name('index');
        Route::get('/create', InstansisForm::class)->name('create');
        Route::get('/{instansi}/edit', InstansisForm::class)->name('edit');
    });

    /*
        |--------------------------------------------------------------------------
        | LAPORAN KEGIATAN
        |--------------------------------------------------------------------------
        */
    Route::prefix('laporan-kegiatans')->name('laporan-kegiatans.')->middleware(['auth', 'can:laporan-kegiatans.view'])->group(function () {
        Route::livewire('/', LaporanKegiatansIndex::class)->name('index');
        Route::livewire('/create/{usulanId?}/{pegawaiId?}', LaporanKegiatansForm::class)->name('create');
        Route::livewire('/{laporan_kegiatans}/edit', LaporanKegiatansForm::class)->name('edit');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN - Roles & Permissions
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'can:role-management.view'])->group(function () {
        Route::get('/role-manager', RoleManager::class)->name('role-manager');
        Route::get('/manajemen-akses', ManajemenAkses::class)->name('manajemen-akses');
    });

});

require __DIR__.'/auth.php';
require __DIR__.'/auth.php';
