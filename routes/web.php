<?php

use App\Http\Controllers\Admin\Angkatan\AngkatanController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Admin\Master\PesertaController;
use App\Http\Controllers\Admin\Mentor\MentorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
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
Route::get('/', function () {return view('welcome');})->name('home');
Route::get('/profil', function () {return view('profil');})->name('profil');
Route::get('/publikasi', function () {return view('publikasi');})->name('publikasi');


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
        });
    });


    // Master Angkatan Routes
    Route::middleware('permission:angkatan.create')->group(function () {
        Route::prefix('angkatan')->name('angkatan.')->group(function () {
            Route::get('/', [AngkatanController::class, 'index'])->name('index');
            Route::get('/create', [AngkatanController::class, 'create'])->name('create');
            Route::post('/', [AngkatanController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AngkatanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AngkatanController::class, 'update'])->name('update');
            Route::delete('/{id}', [AngkatanController::class, 'destroy'])->name('destroy');
        });
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
    });

    // Route Update Akun dan Password
    Route::prefix('admin/akun')->name('admin.akun.')->group(function () {
        Route::get('/', [AuthController::class, 'index'])->name('index');
        Route::put('/update-password', [AuthController::class, 'updatePassword'])->name('update-password');
    });

    
});


// Routes untuk API Wilayah Indonesia (digunakan di form pendaftaran)
Route::get('/api/get-provinces', [PendaftaranController::class, 'getProvinces']);
Route::get('/api/get-regencies/{provinceId}', [PendaftaranController::class, 'getRegencies']);

// Routes untuk API (digunakan di JavaScript untuk load data dinamis)
Route::get('/api/angkatan/{id_jenis_pelatihan}', [PendaftaranController::class, 'apiAngkatan']);

// Route untuk mendapatkan data mentor 
Route::get('/api/mentors', [PendaftaranController::class, 'getMentors'])->name('api.mentors');


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


