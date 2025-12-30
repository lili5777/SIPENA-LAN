<?php

use App\Http\Controllers\Admin\Master\PesertaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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


// Routes untuk web (form pendaftaran)
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/pendaftaran/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pendaftaran/success', [PendaftaranController::class, 'success'])->name('pendaftaran.success');


// Route untuk form partial
Route::get('/form-partial/{type}', [PendaftaranController::class, 'formPartial'])->name('form.partial');


// Routes untuk API (digunakan di JavaScript untuk load data dinamis)
Route::get('/api/angkatan/{id_jenis_pelatihan}', [PendaftaranController::class, 'apiAngkatan']);


// Route Authentication
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'proses_login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// Route Protected Area
Route::middleware('auth')->group(function () {


    // Route Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    
    
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

    // Route Peserta PKN
    // Peserta PKN TK II
    Route::get('/peserta/pkn-tk2', [PesertaController::class, 'index'])->name('peserta.pkn-tk2');
    Route::get('/peserta/detail/{id}', [PesertaController::class, 'getDetail'])->name('peserta.detail');
    Route::post('/peserta/update-status/{id}', [PesertaController::class, 'updateStatus'])->name('peserta.update-status');
    Route::get('/peserta/tambah-peserta-pkn-tk2', [PesertaController::class, 'create'])->name('peserta.tambah-peserta-pkn-tk2');
    Route::post('/peserta/store', [PesertaController::class, 'store'])->name('peserta.store');
    Route::get('/peserta/{id}/edit', [PesertaController::class, 'edit'])->name('peserta.edit');
    Route::put('/peserta/{id}', [PesertaController::class, 'update'])->name('peserta.update');
});


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

Route::get('/api/mentors', [PendaftaranController::class, 'getMentors'])->name('api.mentors');
