<?php

use App\Http\Controllers\Admin\Angkatan\AngkatanController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\KontakController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Admin\Master\PesertaController;
use App\Http\Controllers\Admin\Mentor\MentorController;
use App\Http\Controllers\Admin\PejabatController;
use App\Http\Controllers\Admin\VisiMisiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AksiPerubahanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UploadController;
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

    Route::get('/preview-drive', [AdminController::class, 'preview'])->name('drive.preview');
    Route::get('/download-drive', [AdminController::class, 'download'])->name('drive.download');


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


    // Data Pesrta (khusus Admin)
    Route::middleware('permission:peserta.create')->group(function () {
        Route::prefix('peserta')->name('peserta.')->group(function () {
            // Route utama dengan parameter jenis
            Route::get('/{jenis}', [PesertaController::class, 'index'])
                ->where('jenis', 'pkn|latsar|pka|pkp')
                ->name('index');

            // Route create dengan parameter jenis
            Route::get('/{jenis}/create', [PesertaController::class, 'create'])
                ->where('jenis', 'pkn|latsar|pka|pkp')
                ->name('create');

            // Route edit dengan parameter jenis
            Route::get('/{jenis}/{id}/edit', [PesertaController::class, 'edit'])
                ->where('jenis', 'pkn|latsar|pka|pkp')
                ->name('edit');

            // Route update dengan parameter jenis
            Route::put('/{jenis}/{id}', [PesertaController::class, 'update'])
                ->where('jenis', 'pkn|latsar|pka|pkp')
                ->name('update');

            // Route destroy dengan parameter jenis
            Route::delete('/{jenis}/{id}', [PesertaController::class, 'destroy'])
                ->where('jenis', 'pkn|latsar|pka|pkp')
                ->name('destroy');

            // Route store (tidak perlu parameter jenis karena dari session)
            Route::post('/store', [PesertaController::class, 'store'])->name('store');

            // Route yang tidak butuh parameter jenis
            Route::get('/detail/{id}', [PesertaController::class, 'getDetail'])->name('detail');

            // Route Update Status peserta
            Route::post('/update-status/{id}', [PesertaController::class, 'updateStatus'])->name('update-status');
            Route::post('/resend-account-info/{id}', [PesertaController::class, 'resendAccountInfo'])->name('resend-account-info');
        });
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
        


    // Master Mentor Routes
    Route::middleware('permission:mentor.create')->group(function () {
        Route::prefix('mentor')->name('mentor.')->group(function () {
            Route::get('/', [MentorController::class, 'index'])->name('index');
            Route::get('/create', [MentorController::class, 'create'])->name('create');
            Route::post('/', [MentorController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [MentorController::class, 'edit'])->name('edit');
            Route::put('/{id}', [MentorController::class, 'update'])->name('update');
            Route::delete('/{id}', [MentorController::class, 'destroy'])->name('destroy');
        });
    });
    Route::get('/mentor/{id}/peserta', [MentorController::class, 'getPeserta'])->name('mentor.peserta');
    

    // Export routes
    
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