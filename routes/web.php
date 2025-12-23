<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Routes untuk web (form pendaftaran)
Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/pendaftaran/store', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pendaftaran/success', function () {
    return view('pendaftaran.success'); // Asumsi ada view success.blade.php
})->name('pendaftaran.success');

// Routes untuk API (digunakan di JavaScript untuk load data dinamis)
Route::get('/api/angkatan/{id_jenis_pelatihan}', [PendaftaranController::class, 'apiAngkatan']);
// Route::get('/api/kabupaten/{id_provinsi}', [PendaftaranController::class, 'apiKabupaten']);
// Route::get('/api/provinsi', [PendaftaranController::class, 'apiProvinsi']);


// Route Landing Page
Route::get('/', function () {return view('welcome');})->name('home');
Route::get('/profil', function () {return view('profil');})->name('profil');
Route::get('/publikasi', function () {return view('publikasi');})->name('publikasi');


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
});

// Proxy untuk bypass CORS
Route::get('/proxy/provinces', function () {
    $response = file_get_contents('https://wilayah.id/api/provinces.json');
    return response($response)->header('Content-Type', 'application/json');
});

Route::get('/proxy/regencies/{code}', function ($code) {
    $url = "https://wilayah.id/api/regencies/{$code}.json";
    $response = file_get_contents($url);
    return response($response)->header('Content-Type', 'application/json');
});
