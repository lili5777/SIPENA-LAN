<?php

use App\Http\Controllers\Admin\Angkatan\AngkatanController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\EvaluatorController;
use App\Http\Controllers\PengujiController;
use App\Http\Controllers\Admin\KontakController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Admin\Master\PesertaController;
use App\Http\Controllers\Admin\Mentor\MentorController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\Admin\PejabatController;
use App\Http\Controllers\Admin\VisiMisiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AksesPenilaianController;
use App\Http\Controllers\AksiPerubahanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DetailIndikatorController;
use App\Http\Controllers\ExportNilaiController;
use App\Http\Controllers\GelombangController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\IndikatorNilaiController;
use App\Http\Controllers\IndikatorPenilaianController;
use App\Http\Controllers\JenisNilaiController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UploadNilaiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

Route::get('/tes', function () {
    try {
        Storage::disk('google')->put('test.txt', 'Hello Google Drive from Laravel!');
        $exists = Storage::disk('google')->exists('test.txt');
        return "✅ Google Drive Connected! File exists: " . ($exists ? 'YES' : 'NO');
    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});


Route::get('/up', [UploadController::class, 'index'])->name('upload.index');
Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');





// Route Landing Page
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/berita-simpel/{id}', [LandingController::class, 'showBerita'])->name('berita.detail');
Route::get('/profil', [LandingController::class, 'profil'])->name('profil');
Route::get('/publikasi', [LandingController::class, 'publikasi'])->name('publikasi');


// Route Proses Pendaftaran
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/pendaftaran/update-data', [PendaftaranController::class, 'updateData'])->name('pendaftaran.updateData');
Route::get('/pendaftaran/success', [PendaftaranController::class, 'success'])->name('pendaftaran.success');
Route::post('/form-partial/{type}', [PendaftaranController::class, 'formPartial'])->name('form.partial');
Route::post('/api/verify-nip', [PendaftaranController::class, 'verifyNip'])->name('api.verifyNip');


// Route Authentication
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'proses_login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// Route Protected Area
Route::middleware('auth')->group(function () {

    Route::get('/admin/dashboard/map-data', [AdminController::class, 'mapData'])->name('admin.dashboard.mapData');

    Route::get('/preview-drive', [AdminController::class, 'preview'])->name('drive.preview');
    Route::get('/download-drive', [AdminController::class, 'download'])->name('drive.download');

    // ── Penilaian Mandiri (peserta) ───────────────────────────────
    Route::middleware(['auth'])->group(function () {
    Route::get('/penilaian-mandiri',        [UploadNilaiController::class, 'index']) ->name('penilaian-mandiri.index');
    Route::post('/penilaian-mandiri',       [UploadNilaiController::class, 'store']) ->name('penilaian-mandiri.store');
    });
 
    // ── Verifikasi Nilai (pic / evaluator / admin) ────────────────
    Route::middleware(['auth'])->prefix('verifikasi-nilai')->name('verifikasi-nilai.')->group(function () {
    Route::get('/',                [UploadNilaiController::class, 'indexVerifikasi'])->name('index');
    Route::post('/{id}/approve',   [UploadNilaiController::class, 'approve'])        ->name('approve');
    Route::post('/{id}/reject',    [UploadNilaiController::class, 'reject'])         ->name('reject');
    Route::get('/{id}/file',       [UploadNilaiController::class, 'previewFile'])    ->name('file');
    });

    Route::get('/upload-nilai/{id}/file', [UploadNilaiController::class, 'getFile'])->name('upload-nilai.file');


    // Halaman form export foto
    Route::get('/admin/export/foto', [ExportController::class, 'foto'])->name('export.foto');
    Route::post('/admin/export/foto/proses', [ExportController::class, 'exportFoto'])->name('admin.export.foto');
    // routes/web.php
    Route::get('/admin/export/foto/stats', [ExportController::class, 'fotoStats'])->name('export.foto.stats');


    // Route Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Route Edit Data Peserta (khusus Peserta)
    Route::get('/dashboard/edit', [AdminController::class, 'editData'])->name('admin.dashboard.edit');
    Route::post('/dashboard/update', [AdminController::class, 'updateData'])->name('admin.dashboard.update');
    
    
    // Route Role
    Route::middleware('permission:role.create')->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::delete('/roles/{id}', [RoleController::class, 'delete'])->name('roles.delete');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    });

    
    // Route User
    Route::middleware('permission:user.create')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete')->middleware('permission:user.read');

        Route::get('users/{user}/pic-access', [UserController::class, 'getPicAccess'])->name('users.pic-access');
        Route::post('users/{user}/pic-access', [UserController::class, 'updatePicAccess'])->name('users.update-pic-access');
    });

    Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/',          [PermissionController::class, 'index'])->name('index');
    Route::get('/create',    [PermissionController::class, 'create'])->name('create');
    Route::post('/',         [PermissionController::class, 'store'])->name('store');
    Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('edit');
    Route::put('/{permission}',      [PermissionController::class, 'update'])->name('update');
    Route::delete('/{permission}',   [PermissionController::class, 'destroy'])->name('destroy');
});


    // Data Peserta (khusus Admin)
    Route::prefix('peserta')->name('peserta.')->group(function () {
        
        // READ - index & detail
        Route::get('/{jenis}', [PesertaController::class, 'index'])
            ->where('jenis', 'pkn|latsar|pka|pkp')
            ->name('index')
            ->middleware('permission:peserta.read');

        Route::get('/detail/{id}', [PesertaController::class, 'getDetail'])
            ->name('detail')
            ->middleware('permission:peserta.read');

        // CREATE
        Route::get('/{jenis}/create', [PesertaController::class, 'create'])
            ->where('jenis', 'pkn|latsar|pka|pkp')
            ->name('create')
            ->middleware('permission:peserta.create');

        Route::post('/store', [PesertaController::class, 'store'])
            ->name('store')
            ->middleware('permission:peserta.create');

        // UPDATE
        Route::get('/{jenis}/{id}/edit', [PesertaController::class, 'edit'])
            ->where('jenis', 'pkn|latsar|pka|pkp')
            ->name('edit')
            ->middleware('permission:peserta.update');

        Route::put('/{jenis}/{id}', [PesertaController::class, 'update'])
            ->where('jenis', 'pkn|latsar|pka|pkp')
            ->name('update')
            ->middleware('permission:peserta.update');

        Route::post('/update-status/{id}', [PesertaController::class, 'updateStatus'])
            ->name('update-status')
            ->middleware('permission:peserta.update');

        Route::post('/resend-account-info/{id}', [PesertaController::class, 'resendAccountInfo'])
            ->name('resend-account-info')
            ->middleware('permission:peserta.update');

        // DELETE
        Route::delete('/{jenis}/{id}', [PesertaController::class, 'destroy'])
            ->where('jenis', 'pkn|latsar|pka|pkp')
            ->name('destroy')
            ->middleware('permission:peserta.delete');
    });

    // Master Kelompok Routes
   Route::prefix('kelompok')->name('kelompok.')->group(function () {

        // ✅ Static routes DULU (tanpa parameter)
        Route::get('/', [KelompokController::class, 'index'])->name('index')->middleware('permission:kelompok.read');
        Route::get('/create', [KelompokController::class, 'create'])->name('create')->middleware('permission:kelompok.create');
        Route::post('/', [KelompokController::class, 'store'])->name('store')->middleware('permission:kelompok.create');
        Route::get('/api/angkatan-by-jenis', [KelompokController::class, 'getAngkatanByJenis'])->name('angkatan-by-jenis')->middleware('permission:kelompok.read');

        // ✅ Wildcard routes BELAKANGAN (dengan parameter {kelompok})
        Route::get('/{kelompok}', [KelompokController::class, 'show'])->name('show')->middleware('permission:kelompok.read');
        Route::get('/{kelompok}/edit', [KelompokController::class, 'edit'])->name('edit')->middleware('permission:kelompok.update');
        Route::put('/{kelompok}', [KelompokController::class, 'update'])->name('update')->middleware('permission:kelompok.update');
        Route::delete('/{kelompok}', [KelompokController::class, 'destroy'])->name('destroy')->middleware('permission:kelompok.delete');
        Route::get('/{kelompok}/kelola-peserta', [KelompokController::class, 'kelolaPeserta'])->name('kelola-peserta')->middleware('permission:kelompok.update');
        Route::post('/{kelompok}/tambah-peserta', [KelompokController::class, 'tambahPeserta'])->name('tambah-peserta')->middleware('permission:kelompok.update');
        Route::post('/{kelompok}/lepas-peserta', [KelompokController::class, 'lepasPeserta'])->name('lepas-peserta')->middleware('permission:kelompok.update');
    });


   // Master Angkatan Routes
    Route::prefix('angkatan')->name('angkatan.')->group(function () {
        Route::get('/', [AngkatanController::class, 'index'])->name('index')->middleware('permission:angkatan.read');
        Route::get('/create', [AngkatanController::class, 'create'])->name('create')->middleware('permission:angkatan.create');
        Route::post('/', [AngkatanController::class, 'store'])->name('store')->middleware('permission:angkatan.create');
        Route::get('/{id}/edit', [AngkatanController::class, 'edit'])->name('edit')->middleware('permission:angkatan.update');
        Route::put('/{id}', [AngkatanController::class, 'update'])->name('update')->middleware('permission:angkatan.update');
        Route::delete('/{id}', [AngkatanController::class, 'destroy'])->name('destroy')->middleware('permission:angkatan.delete');
    });

    // Master Coach Routes
    Route::get('coach/check-nama', [CoachController::class, 'checkNama'])->name('coach.check-nama');
    Route::prefix('coach')->name('coach.')->group(function () {
        Route::get('/', [CoachController::class, 'index'])->name('index')->middleware('permission:coach.read');
        Route::get('/create', [CoachController::class, 'create'])->name('create')->middleware('permission:coach.create');
        Route::post('/', [CoachController::class, 'store'])->name('store')->middleware('permission:coach.create');
        Route::get('/{id}/edit', [CoachController::class, 'edit'])->name('edit')->middleware('permission:coach.update');
        Route::put('/{id}', [CoachController::class, 'update'])->name('update')->middleware('permission:coach.update');
        Route::delete('/{id}', [CoachController::class, 'destroy'])->name('destroy')->middleware('permission:coach.delete');
    });


    // Route tambahan untuk Penguji
    Route::get('penguji/export', [PengujiController::class, 'export'])->name('penguji.export');
    Route::get('penguji/{id}/whatsapp', [PengujiController::class, 'sendWhatsapp'])->name('penguji.whatsapp');
    Route::post('penguji/{id}/generate-password', [PengujiController::class, 'generatePassword'])->name('penguji.generate-password');
    Route::get('penguji/check-nama', [PengujiController::class, 'checkNama'])->name('penguji.check-nama');
    // Master Penguji Routes
    Route::prefix('penguji')->name('penguji.')->group(function () {
        Route::get('/', [PengujiController::class, 'index'])->name('index')->middleware('permission:penguji.read');
        Route::get('/create', [PengujiController::class, 'create'])->name('create')->middleware('permission:penguji.create');
        Route::post('/', [PengujiController::class, 'store'])->name('store')->middleware('permission:penguji.create');
        Route::get('/{id}/edit', [PengujiController::class, 'edit'])->name('edit')->middleware('permission:penguji.update');
        Route::put('/{id}', [PengujiController::class, 'update'])->name('update')->middleware('permission:penguji.update');
        Route::delete('/{id}', [PengujiController::class, 'destroy'])->name('destroy')->middleware('permission:penguji.delete');
    });

    // Master Evaluator Routes
    Route::prefix('evaluator')->name('evaluator.')->group(function () {
        Route::get('/', [EvaluatorController::class, 'index'])->name('index')->middleware('permission:evaluator.read');
        Route::get('/create', [EvaluatorController::class, 'create'])->name('create')->middleware('permission:evaluator.create');
        Route::post('/', [EvaluatorController::class, 'store'])->name('store')->middleware('permission:evaluator.create');
        Route::get('/{id}/edit', [EvaluatorController::class, 'edit'])->name('edit')->middleware('permission:evaluator.update');
        Route::put('/{id}', [EvaluatorController::class, 'update'])->name('update')->middleware('permission:evaluator.update');
        Route::delete('/{id}', [EvaluatorController::class, 'destroy'])->name('destroy')->middleware('permission:evaluator.delete');
    });

    // Master Mentor Routes
    Route::prefix('mentor')->name('mentor.')->group(function () {
        Route::get('/', [MentorController::class, 'index'])->name('index')->middleware('permission:mentor.read');
        Route::get('/create', [MentorController::class, 'create'])->name('create')->middleware('permission:mentor.create');
        Route::post('/', [MentorController::class, 'store'])->name('store')->middleware('permission:mentor.create');
        Route::get('/{id}/edit', [MentorController::class, 'edit'])->name('edit')->middleware('permission:mentor.update');
        Route::put('/{id}', [MentorController::class, 'update'])->name('update')->middleware('permission:mentor.update');
        Route::delete('/{id}', [MentorController::class, 'destroy'])->name('destroy')->middleware('permission:mentor.delete');
    });

    Route::get('/mentor/{id}/peserta', [MentorController::class, 'getPeserta'])->name('mentor.peserta');
    Route::get('mentor/preview-duplicates', [MentorController::class, 'previewDuplicates'])->name('mentor.previewDuplicates');
    Route::post('mentor/cleanup-duplicates', [MentorController::class, 'cleanupDuplicates'])->name('mentor.cleanupDuplicates');


    Route::prefix('indikator-penilaian')->name('indikator-penilaian.')->group(function () {

    // Step 1: Pilih Jenis Pelatihan
    Route::get('/', [IndikatorPenilaianController::class, 'index'])->name('index');

    // Step 2: Jenis Nilai (nested under jenis pelatihan)
    Route::prefix('{jenisPelatihan}/jenis-nilai')->name('jenis-nilai.')->group(function () {
        Route::get('/',        [JenisNilaiController::class, 'index'])->name('index');
        Route::post('/',       [JenisNilaiController::class, 'store'])->name('store');
        Route::put('/{id}',    [JenisNilaiController::class, 'update'])->name('update');
        Route::delete('/{id}', [JenisNilaiController::class, 'destroy'])->name('destroy');
    });

    // Step 3: Indikator Nilai
    Route::prefix('{jenisPelatihan}/jenis-nilai/{jenisNilai}/indikator')->name('indikator.')->group(function () {
        Route::get('/',        [IndikatorNilaiController::class, 'index'])->name('index');
        Route::post('/',       [IndikatorNilaiController::class, 'store'])->name('store');
        Route::put('/{id}',    [IndikatorNilaiController::class, 'update'])->name('update');
        Route::delete('/{id}', [IndikatorNilaiController::class, 'destroy'])->name('destroy');
    });

    // Step 4: Detail Indikator
    Route::prefix('{jenisPelatihan}/jenis-nilai/{jenisNilai}/indikator/{indikatorNilai}/detail')->name('detail-indikator.')->group(function () {
        Route::get('/',        [DetailIndikatorController::class, 'index'])->name('index');
        Route::post('/',       [DetailIndikatorController::class, 'store'])->name('store');
        Route::put('/{id}',    [DetailIndikatorController::class, 'update'])->name('update');
        Route::delete('/{id}', [DetailIndikatorController::class, 'destroy'])->name('destroy');
    });

    
});

    Route::prefix('nilai')->name('nilai.')->middleware(['auth'])->group(function () {
    Route::get('/{jenis}',              [NilaiController::class, 'index'])        ->name('index');
    Route::get('/{jenis}/rekap',        [NilaiController::class, 'rekap'])        ->name('rekap');
    Route::get('/get-data/{pesertaId}', [NilaiController::class, 'getData'])      ->name('getData');
    Route::post('/simpan',              [NilaiController::class, 'simpanNilai'])  ->name('simpan');
    Route::post('/simpan-catatan',      [NilaiController::class, 'simpanCatatan'])->name('simpanCatatan');
    });

    Route::prefix('akses-penilaian')->name('akses-penilaian.')->middleware(['auth'])->group(function () {
    Route::get('/',                         [AksesPenilaianController::class, 'index'])      ->name('index');
    Route::get('/{jenisPelatihanId}',       [AksesPenilaianController::class, 'kelola'])     ->name('kelola');
    Route::post('/{jenisPelatihanId}/bulk', [AksesPenilaianController::class, 'simpanBulk'])->name('simpan-bulk');
    Route::post('/reset',                   [AksesPenilaianController::class, 'reset'])      ->name('reset');
    Route::post('/simpan',                  [AksesPenilaianController::class, 'simpan'])     ->name('simpan');
    });
    

    // Export routes
    Route::get('admin/export/nilai/preview', [ExportNilaiController::class, 'previewData'])
    ->name('admin.export.nilai.preview');
    // Export Nilai Peserta
    Route::get('/admin/export/nilai-peserta',          [ExportNilaiController::class, 'index'])
        ->name('admin.export.nilaipeserta');
    Route::get('/admin/export/nilai-peserta/download', [ExportNilaiController::class, 'export'])
        ->name('admin.export.nilai.download');
    Route::get('/admin/export/nilai-peserta/preview', [ExportNilaiController::class, 'preview'])
        ->name('admin.export.nilai.preview');
    
    Route::prefix('admin/export')->name('admin.export.')->group(function () {
        Route::get('/data-peserta', [ExportController::class, 'index'])->name('datapeserta')->middleware('permission:export.data');
        Route::get('/peserta', [ExportController::class, 'exportPeserta'])->name('peserta')->middleware('permission:export.data');
        // Route baru untuk komposisi
        Route::get('/komposisi-peserta', [ExportController::class, 'indexKomposisi'])->name('komposisipeserta')->middleware('permission:export.komposisi');
        Route::get('/komposisi', [ExportController::class, 'exportKomposisi'])->name('komposisi')->middleware('permission:export.komposisi');
        //  Route export Absen
        Route::get('/absen-peserta', [ExportController::class, 'indexAbsen'])->name('absenpeserta')->middleware('permission:export.absen');
        Route::get('/absen', [ExportController::class, 'exportAbsen'])->name('absen')->middleware('permission:export.absen');

        // Halaman Export Sertifikat  
        Route::get('/sertifikat', [ExportController::class, 'viewExportSertifikat'])
            ->name('sertifikat.view');

        // Proses Export Sertifikat
        Route::get('/sertifikat/pdf', [ExportController::class, 'exportSertifikat'])
            ->name('sertifikat');
    });

    Route::resource('gelombang', GelombangController::class);
    Route::get('gelombang/{gelombang}/kelola-angkatan', [GelombangController::class, 'kelolaAngkatan'])->name('gelombang.kelola-angkatan');
    Route::post('gelombang/{gelombang}/tambah-angkatan', [GelombangController::class, 'tambahAngkatan'])->name('gelombang.tambah-angkatan');
    Route::post('gelombang/{gelombang}/lepas-angkatan', [GelombangController::class, 'lepasAngkatan'])->name('gelombang.lepas-angkatan');

    
    // Export Jadwal Seminar
    Route::get('/export/jadwal-seminar', [ExportController::class, 'indexJadwalSeminar'])
        ->name('admin.export.jadwal-seminar.index');

    Route::get('/export/jadwal-seminar/download', [ExportController::class, 'exportJadwalSeminar'])
        ->name('admin.export.jadwal-seminar');

    Route::get('/import/peserta', [ImportController::class, 'showImportForm'])->name('admin.import.peserta');
    Route::post('/import/peserta', [ImportController::class, 'importPeserta'])->name('admin.import.peserta.process');
    Route::get('/import/peserta/template', [ImportController::class, 'downloadTemplate'])->name('admin.import.peserta.template');

    // Route Update Akun dan Password
    Route::prefix('admin/akun')->name('admin.akun.')->group(function () {
        Route::get('/', [AuthController::class, 'index'])->name('index');
        Route::put('/update-password', [AuthController::class, 'updatePassword'])->name('update-password');
    });
    Route::put('/admin/akun/update-phone', [AuthController::class, 'updatePhone'])
    ->name('admin.akun.update-phone');

    Route::get('/aktifitas', [AdminController::class, 'histori'])->name('aktifitas.index');


    // Route::get('/aksi-perubahan', [AksiPerubahanController::class, 'index'])->name('aksiperubahan');
    // Route::post('/aksi-perubahan/store', [AksiPerubahanController::class, 'store'])->name('aksiperubahan.store');
    // Route::put('/aksi-perubahan/{id}', [AksiPerubahanController::class, 'update'])->name('aksiperubahan.update');
    // Route::delete('/aksi-perubahan/{id}', [AksiPerubahanController::class, 'destroy'])->name('aksiperubahan.destroy');
    Route::get('/aksi-perubahan', [AksiPerubahanController::class, 'index'])->name('aksiperubahan.index');
    Route::post('/aksi-perubahan/store', [AksiPerubahanController::class, 'store'])->name('aksiperubahan.store');
    Route::put('/aksi-perubahan/{id}', [AksiPerubahanController::class, 'update'])->name('aksiperubahan.update');
    Route::put('/aksi-perubahan/{id}/upload-pengesahan', [AksiPerubahanController::class, 'uploadPengesahan'])->name('aksiperubahan.upload-pengesahan');
    Route::delete('/aksi-perubahan/{id}', [AksiPerubahanController::class, 'destroy'])->name('aksiperubahan.destroy');

    Route::get('/peserta/report/{id?}', [AdminController::class, 'generateReport'])->name('admin.peserta.report');
    Route::get('/peserta/data/{id?}', [AdminController::class, 'generateDatapeserta'])->name('admin.peserta.data');

    Route::get('/peserta/{jenis}/{id}/swap', [PesertaController::class, 'showSwapForm'])
        ->name('peserta.swap.form');

    Route::post('/peserta/{jenis}/{id}/swap', [PesertaController::class, 'swapAngkatan'])
        ->name('peserta.swap.process');

    // Route untuk AJAX - HARUS tanpa parameter {jenis} atau dengan optional parameter
    Route::post('/peserta/get-peserta-angkatan', [PesertaController::class, 'getPesertaAngkatan'])
        ->name('peserta.get-peserta-angkatan');
    
    Route::get('/admin/peserta/{jenis}/get-peserta-angkatan', [PesertaController::class, 'getPesertaAngkatan'])
        ->name('peserta.getPesertaAngkatan');

    Route::post('/admin/peserta/{jenis}/swap-ndh', [PesertaController::class, 'swapNdh'])
        ->name('peserta.swapNdh');
    
    Route::post('/peserta/{jenis}/bulk-delete', [PesertaController::class, 'bulkDelete'])->name('peserta.bulkDelete');

    // Visi Misi Routes
    Route::prefix('visi-misi')->name('visi-misi.')->group(function () {
        Route::get('/', [VisiMisiController::class, 'index'])->name('index');
        Route::get('/visi/create', [VisiMisiController::class, 'createVisi'])->name('visi.create');
        Route::post('/visi', [VisiMisiController::class, 'storeVisi'])->name('visi.store');
        Route::get('/visi/edit', [VisiMisiController::class, 'editVisi'])->name('visi.edit');
        Route::put('/visi/{visi}', [VisiMisiController::class, 'updateVisi'])->name('visi.update');

        Route::get('/misi/create', [VisiMisiController::class, 'createMisi'])->name('misi.create');
        Route::post('/misi', [VisiMisiController::class, 'storeMisi'])->name('misi.store');
        Route::get('/misi/{misi}/edit', [VisiMisiController::class, 'editMisi'])->name('misi.edit');
        Route::put('/misi/{misi}', [VisiMisiController::class, 'updateMisi'])->name('misi.update');
        Route::delete('/misi/{misi}', [VisiMisiController::class, 'destroyMisi'])->name('misi.destroy');
    });

    // Route untuk Kontak
    Route::prefix('kontak')->name('kontak.')->group(function () {
        Route::get('/', [KontakController::class, 'index'])->name('index');
        Route::get('/form', [KontakController::class, 'createOrEdit'])->name('form');
        Route::post('/store', [KontakController::class, 'store'])->name('store');
    });

    // Route untuk Pejabat
    Route::resource('pejabat', PejabatController::class);
    Route::post('pejabat/update-positions', [PejabatController::class, 'updatePositions'])->name('pejabat.updatePositions');
    Route::get('berita', [BeritaController::class, 'index'])->name('berita.index');
    Route::get('berita/create', [BeritaController::class, 'create'])->name('berita.create');
    Route::post('berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::get('berita/{id}', [BeritaController::class, 'show'])->name('berita.show');
    Route::get('berita/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
    Route::put('berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');
    Route::post('berita/upload-image', [BeritaController::class, 'uploadImage'])->name('berita.uploadImage');

    
});


// Routes untuk API Wilayah Indonesia (digunakan di form pendaftaran)
Route::get('/api/get-provinces', [PendaftaranController::class, 'getProvinces']);
Route::get('/api/get-regencies/{provinceId}', [PendaftaranController::class, 'getRegencies']);

// Routes untuk API (digunakan di JavaScript untuk load data dinamis)
Route::get('/api/angkatan/{id_jenis_pelatihan}', [PendaftaranController::class, 'apiAngkatan']);

// Route untuk mendapatkan data mentor 
Route::get('/api/mentors', [PendaftaranController::class, 'getMentors'])->name('api.mentors');

Route::get('/api/get-available-ndh', [PendaftaranController::class, 'getAvailableNdh']);
Route::get('/api/peserta/available-ndh', [PesertaController::class, 'getAvailableNdhForPeserta']);

// Route untuk get mentors dengan search (AJAX)
Route::get('/admin/peserta/{jenis}/get-mentors', [PesertaController::class, 'getMentors'])
    ->name('peserta.getMentors');
Route::get('/admin/dashboard/get-mentors', [AdminController::class, 'getMentors'])
    ->name('admin.dashboard.getMentors');

// Proxy untuk bypass CORS (API Wilayah Indonesia)
// Provinsi
Route::get('/proxy/provinces', function () {
    $response = file_get_contents('https://wilayah.id/api/provinces.json');
    return response($response)->header('Content-Type', 'application/json');
});
// Kota/Kabupaten
Route::get('/proxy/regencies/{code}', function ($code) {
    $url = "https://wilayah.id/api/regencies/{$code}.json";
    $response = file_get_contents($url);
    return response($response)->header('Content-Type', 'application/json');
});


// made by ali