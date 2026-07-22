<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\KaderController;

// 1. Root & Guest Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. Authentication Protected Routes
Route::middleware(['auth'])->group(function () {

    // 2.1 Warga (Family Portal) Routes
    Route::middleware(['role:warga'])->prefix('warga')->name('warga.')->group(function () {
        Route::get('/dashboard', [WargaController::class, 'dashboard'])->name('dashboard');
        Route::post('/queue', [WargaController::class, 'takeQueue'])->name('queue.take');
        Route::get('/queue/status', [WargaController::class, 'queueStatus'])->name('queue.status');
        Route::post('/profile', [WargaController::class, 'updateProfile'])->name('profile.update');
        Route::get('/member/{id}', [WargaController::class, 'memberDetail'])->name('member.detail');
        Route::post('/member', [WargaController::class, 'storeMember'])->name('member.store');
        Route::post('/notifications/read', [WargaController::class, 'readNotifications'])->name('notifications.read');
    });

    // 2.2 Kader (Volunteer Portal) Routes
    Route::middleware(['role:kader'])->prefix('kader')->name('kader.')->group(function () {
        Route::get('/dashboard', [KaderController::class, 'dashboard'])->name('dashboard');
        Route::get('/global-search', [KaderController::class, 'globalSearch'])->name('global-search');
        
        // Active Queue Control
        Route::get('/queue/poll-data', [KaderController::class, 'queuePollData'])->name('queue.poll-data');
        Route::post('/queue/{id}/call', [KaderController::class, 'callQueue'])->name('queue.call');
        Route::post('/queue/{id}/skip', [KaderController::class, 'skipQueue'])->name('queue.skip');
        Route::post('/queue/{id}/complete', [KaderController::class, 'completeQueue'])->name('queue.complete');
        
        // Families Management
        Route::get('/families', [KaderController::class, 'families'])->name('families.index');
        Route::post('/families', [KaderController::class, 'storeFamily'])->name('families.store');
        Route::get('/families/{id}', [KaderController::class, 'showFamily'])->name('families.show');
        
        // Family Members Management
        Route::post('/families/{familyId}/members', [KaderController::class, 'storeMember'])->name('members.store');
        Route::post('/members/{id}/update', [KaderController::class, 'updateMember'])->name('members.update');
        Route::delete('/members/{id}', [KaderController::class, 'deleteMember'])->name('members.delete');
        Route::post('/members/{id}/verify/{action}', [KaderController::class, 'verifyMember'])->name('members.verify');
        
        // Health Checking Entries
        Route::get('/members/{memberId}/health-check', [KaderController::class, 'createHealthCheck'])->name('health.check');
        Route::post('/members/{memberId}/health-check', [KaderController::class, 'storeHealthCheck'])->name('health.check.store');
        
        // Reporting & Exports
        Route::get('/reports', [KaderController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [KaderController::class, 'exportCsv'])->name('reports.export');
        
        // Schedules Management
        Route::get('/schedules', [KaderController::class, 'schedules'])->name('schedules.index');
        Route::post('/schedules', [KaderController::class, 'storeSchedule'])->name('schedules.store');
        Route::delete('/schedules/{id}', [KaderController::class, 'deleteSchedule'])->name('schedules.delete');
        
        // Announcements Management
        Route::get('/announcements', [KaderController::class, 'announcements'])->name('announcements.index');
        Route::post('/announcements', [KaderController::class, 'storeAnnouncement'])->name('announcements.store');
        Route::delete('/announcements/{id}', [KaderController::class, 'deleteAnnouncement'])->name('announcements.delete');
        
        // Notifications Management
        Route::get('/notifications', [KaderController::class, 'notifications'])->name('notifications.index');
        Route::post('/notifications', [KaderController::class, 'storeNotification'])->name('notifications.store');
        
        // Security Audit Logs
        Route::get('/audit-logs', [KaderController::class, 'auditLogs'])->name('audit-logs');
    });

});
