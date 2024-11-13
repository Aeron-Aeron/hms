<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Patient\HealthProblemController;
use App\Http\Controllers\Patient\DoctorRecommendationController;
use App\Http\Controllers\Doctor\DoctorAppointmentController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\HealthProblemController as PatientHealthProblemController;
use App\Http\Controllers\Patient\DoctorController;

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
        return redirect()->route(auth()->user()->role . '.dashboard');
    })->name('dashboard');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::put('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.updateRole');
        Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('admin.appointments.index');
        Route::put('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('admin.appointments.updateStatus');
    });

    // Doctor routes
    Route::middleware(['role:doctor'])->group(function () {
        Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])
            ->name('doctor.dashboard');
    });

    // Patient routes
    Route::middleware(['role:patient'])->group(function () {
        Route::get('/patient/dashboard', [PatientDashboardController::class, 'index'])
            ->name('patient.dashboard');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Appointment routes
        Route::resource('appointments', AppointmentController::class);

        // Health problems routes
        Route::resource('health-problems', HealthProblemController::class);

        // Doctor recommendation route
        Route::get('/doctors/recommended', [DoctorController::class, 'recommended'])
            ->name('doctors.recommended');
    });

    Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->group(function () {
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
        Route::get('/appointments', [DoctorAppointmentController::class, 'index'])->name('doctor.appointments.index');
        Route::put('/appointments/{appointment}/status', [DoctorAppointmentController::class, 'updateStatus'])->name('doctor.appointments.updateStatus');
        Route::put('/appointments/{appointment}/reschedule', [DoctorAppointmentController::class, 'reschedule'])->name('doctor.appointments.reschedule');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('register', [CustomRegisterController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('register', [CustomRegisterController::class, 'register']);
});
