<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\DebugController;

// Controllers for each role
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Patient\PatientDashboardController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAppointmentController;

// Doctor Controllers
use App\Http\Controllers\Doctor\DoctorAppointmentController;

// Patient Controllers
use App\Http\Controllers\Patient\HealthProblemController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\DoctorController;
use App\Http\Controllers\Patient\RatingController;
use App\Http\Controllers\Patient\DiseasePredictorController;

// Testing & Debug Routes (should be disabled in production)
Route::prefix('debug')->group(function () {
    Route::get('/test', function() {
        return 'Test route works!';
    });
    Route::get('/role', [DebugController::class, 'checkRole'])->name('debug.role');
    Route::get('/controller', [TestController::class, 'index']);
    Route::get('/auth', function() {
        return response()->json([
            'auth_check' => auth()->check(),
            'session_active' => session()->isStarted(),
            'session_id' => session()->getId(),
            'user' => auth()->user() ? auth()->user()->only(['id', 'name', 'email', 'role']) : null
        ]);
    });
});

// Public Routes
Route::get('/', [WelcomeController::class, 'index']);

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [CustomRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [CustomRegisterController::class, 'register'])->name('custom.register');
});

// Authenticated Routes
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Main Dashboard Route
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            'patient' => redirect()->route('patient.dashboard'),
            default => abort(403, 'Invalid user role.'),
        };
    })->name('dashboard');

    // Profile Routes
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Admin Routes
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            // User Management
            Route::controller(AdminUserController::class)->group(function () {
                Route::get('/users', 'index')->name('users.index');
                Route::put('/users/{user}/role', 'updateRole')->name('users.updateRole');
            });

            // Appointment Management
            Route::controller(AdminAppointmentController::class)->group(function () {
                Route::get('/appointments', 'index')->name('appointments.index');
                Route::get('/appointments/{appointment}', 'show')->name('appointments.show');
                Route::put('/appointments/{appointment}/status', 'updateStatus')->name('appointments.updateStatus');
                Route::delete('/appointments/{appointment}', 'destroy')->name('appointments.destroy');
            });
        });

    // Doctor Routes
    Route::middleware('role:doctor')
        ->prefix('doctor')
        ->name('doctor.')
        ->group(function () {
            Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');

            Route::controller(DoctorAppointmentController::class)->group(function () {
                Route::get('/appointments', 'index')->name('appointments.index');
                Route::get('/appointments/{appointment}', 'show')->name('appointments.show');
                Route::put('/appointments/{appointment}/status', 'updateStatus')->name('appointments.updateStatus');
                Route::put('/appointments/{appointment}/reschedule', 'reschedule')->name('appointments.reschedule');
            });
        });

    // Patient Routes
    Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');

        // Add these doctor routes
        Route::controller(DoctorController::class)->prefix('doctors')->name('doctors.')->group(function () {
            Route::get('/recommended', 'recommended')->name('recommended');
            Route::get('/{doctor}', 'show')->name('show');
        });

        Route::resource('health-problems', HealthProblemController::class);
        Route::resource('appointments', PatientAppointmentController::class);
        Route::post('/ratings/{rating}/vote', [RatingController::class, 'vote'])->name('ratings.vote');
        Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
        Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
        Route::get('/symptom-checker', [DiseasePredictorController::class, 'index'])
             ->name('disease-predictor.index');
        Route::post('/symptom-checker/predict', [DiseasePredictorController::class, 'predict'])
             ->name('disease-predictor.predict');
        Route::post('/symptoms/check', [PatientDashboardController::class, 'checkSymptoms'])
             ->name('symptoms.check');
        Route::get('/specialists/{disease}', [PatientDashboardController::class, 'findSpecialists'])
             ->name('specialists.find');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
        Route::get('/patient/dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
    });
});
