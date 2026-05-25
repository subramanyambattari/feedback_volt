<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// simple health check route (no DB access) to validate the app boots in production
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => env('APP_NAME', 'feedback'),
        'env' => env('APP_ENV', 'production'),
    ], 200);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/registration', [AuthController::class, 'showRegistration'])->name('register');
    Route::post('/registration', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/notifications', [AdminController::class, 'sendNotification'])->name('admin.notifications.send');
    Route::get('/admin/technicians', [AdminController::class, 'technicians'])->name('admin.technicians');
    Route::post('/admin/technicians', [AdminController::class, 'storeTechnician'])->name('admin.technicians.store');
    Route::patch('/admin/technicians/assign/{feedback}', [AdminController::class, 'assignTechnician'])->name('admin.technicians.assign');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::patch('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profile/password', [AccountController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/notifications', [AccountController::class, 'notifications'])->name('notifications');
    Route::get('/emergency', [AccountController::class, 'emergency'])->name('emergency');
    Route::get('/complaint-tracking', [AccountController::class, 'complaintTracking'])->name('complaints.tracking');
    Route::patch('/complaint-tracking/{feedback}/complete', [AccountController::class, 'completeAssignedComplaint'])->name('complaints.complete');
    Route::get('/ai-assistant', [AccountController::class, 'assistant'])->name('assistant');
    Route::post('/ai-assistant/ask', [AccountController::class, 'askAssistant'])->name('assistant.ask');
    Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/responses', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/responses/{feedback}/edit', [FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::put('/responses/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/responses/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
