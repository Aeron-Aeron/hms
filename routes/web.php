<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;

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

Route::get('/', [WelcomeController::class, 'index']);

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

    // Default dashboard route that redirects based on role
    Route::get('/dashboard', function () {
        switch(auth()->user()->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'doctor':
                return redirect()->route('doctor.dashboard');
            case 'patient':
                return redirect()->route('patient.dashboard');
            default:
                return redirect()->route('login');
        }
    })->name('dashboard');

    // Admin routes
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // Doctor routes
    Route::middleware(['auth', 'role:doctor'])->group(function () {
        Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
    });

    // Patient routes
    Route::middleware(['auth', 'role:patient'])->group(function () {
        Route::get('/patient/dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('register', [CustomRegisterController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('register', [CustomRegisterController::class, 'register']);
});
