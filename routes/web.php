<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Patient Routes
    Route::middleware(['checkRole:patient'])->group(function () {
        Route::get('/patient/dashboard', [DashboardController::class, 'patientDashboard']);
        Route::resource('appointments', AppointmentController::class);
    });

    // Doctor Routes
    Route::middleware(['checkRole:doctor'])->group(function () {
        Route::get('/doctor/dashboard', [DashboardController::class, 'doctorDashboard']);
        Route::put('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
    });

    // Admin Routes
    Route::middleware(['checkRole:admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard']);
        Route::resource('doctors', DoctorController::class);
    });

    Route::middleware(['auth', 'checkRole:doctor'])->group(function () {
        Route::post('/availability/update', [AvailabilityController::class, 'update'])->name('doctor.availability.update');
    });
});
