<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\RecruiterApplicationController;
use App\Http\Controllers\RecruiterJobController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', [JobController::class, 'home'])->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{jobListing}', [JobController::class, 'show'])->name('jobs.show');

// Guests only
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logged-in users only
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Send each user to the dashboard that matches their role
    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->role . '.dashboard');
    })->name('dashboard');

    // Resume download (access is decided by JobApplicationPolicy)
    Route::get('/applications/{jobApplication}/resume', [ApplicationController::class, 'resume'])
        ->name('applications.resume');

    // Admin area
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

            Route::get('/users', [AdminController::class, 'users'])->name('users.index');
            Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

            Route::get('/jobs', [AdminController::class, 'jobs'])->name('jobs.index');
            Route::patch('/jobs/{jobListing}/toggle', [AdminController::class, 'toggleJob'])->name('jobs.toggle');
            Route::delete('/jobs/{jobListing}', [AdminController::class, 'destroyJob'])->name('jobs.destroy');
        });

    // Candidate area
    Route::middleware('role:candidate')
        ->prefix('candidate')
        ->name('candidate.')
        ->group(function () {
            Route::get('/', [ApplicationController::class, 'index'])->name('dashboard');
            Route::post('/jobs/{jobListing}/apply', [ApplicationController::class, 'store'])->name('applications.store');
            Route::delete('/applications/{jobApplication}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
        });

    // Recruiter area
    Route::middleware('role:recruiter')
        ->prefix('recruiter')
        ->name('recruiter.')
        ->group(function () {
            Route::get('/', fn () => redirect()->route('recruiter.jobs.index'))->name('dashboard');

            Route::patch('jobs/{jobListing}/toggle', [RecruiterJobController::class, 'toggle'])
                ->name('jobs.toggle');

            Route::get('jobs/{jobListing}/applications', [RecruiterApplicationController::class, 'index'])
                ->name('jobs.applications');

            Route::patch('applications/{jobApplication}/status', [RecruiterApplicationController::class, 'updateStatus'])
                ->name('applications.status');

            Route::resource('jobs', RecruiterJobController::class)
                ->parameters(['jobs' => 'jobListing'])
                ->except('show');
        });
});
