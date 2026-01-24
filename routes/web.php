<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\DashboardController;



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');
    
    // Master Data
    Route::resource('classes', \App\Http\Controllers\ClassRoomController::class)->parameters(['classes' => 'class']);
    Route::resource('teachers', \App\Http\Controllers\TeacherController::class);
    Route::post('students/import', [\App\Http\Controllers\StudentController::class, 'import'])->name('students.import');
    Route::get('students/template', [\App\Http\Controllers\StudentController::class, 'downloadTemplate'])->name('students.template');
    Route::get('students/export', [\App\Http\Controllers\StudentController::class, 'export'])->name('students.export');
    Route::post('students/{student}/reset-password', [\App\Http\Controllers\StudentController::class, 'resetPassword'])->name('students.reset_password');
    Route::get('students/promotion', [\App\Http\Controllers\StudentController::class, 'promote'])->name('students.promote');
    Route::post('students/promotion', [\App\Http\Controllers\StudentController::class, 'promoteStore'])->name('students.promote_store');
    Route::post('students/bulk-destroy', [\App\Http\Controllers\StudentController::class, 'bulkDestroy'])->name('students.bulk_destroy');
    Route::resource('students', \App\Http\Controllers\StudentController::class);
    
    // Global Masters (Ex-Super Admin)
    Route::resource('habits', \App\Http\Controllers\HabitController::class);
    Route::resource('academic-years', \App\Http\Controllers\AcademicYearController::class);
    Route::post('academic-years/{id}/activate', [\App\Http\Controllers\AcademicYearController::class, 'activate'])->name('academic-years.activate');
    
    // Schools & Users (Ex-Super Admin)
    Route::resource('schools', \App\Http\Controllers\SchoolController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);

    // Parent Management
    Route::post('parents/{parent}/reset-password', [\App\Http\Controllers\ParentManagementController::class, 'resetPassword'])->name('admin.parents.reset_password');
    Route::resource('parents', \App\Http\Controllers\ParentManagementController::class)->names('admin.parents');

    // Reports
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::post('reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
    Route::get('reports/print/{id}', [\App\Http\Controllers\ReportController::class, 'printStudentReport'])->name('students.print_report');
    Route::post('reports/export-pdf', [\App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.exportPdf');
    Route::get('reports/habit-stats', [\App\Http\Controllers\ReportController::class, 'habitStats'])->name('reports.habitStats');

    // Settings & Logs
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('admin.settings.update');
    Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::delete('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'destroyAll'])->name('activity-logs.destroyAll');
});

Route::middleware(['auth', 'role:guru'])->prefix('guru')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'guru'])->name('dashboard.guru');
    Route::resource('journals', \App\Http\Controllers\TeacherJournalController::class)->names([
        'index' => 'teacher.journals.index',
        'store' => 'teacher.journals.store',
        'create' => 'teacher.journals.create',
        'show' => 'teacher.journals.show',
        'update' => 'teacher.journals.update',
        'destroy' => 'teacher.journals.destroy',
        'edit' => 'teacher.journals.edit',
    ]);

    // Reports
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('teacher.reports.index');
    Route::post('reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('teacher.reports.export');
    Route::post('reports/export-pdf', [\App\Http\Controllers\ReportController::class, 'exportPdf'])->name('teacher.reports.exportPdf');
    Route::get('reports/habit-stats', [\App\Http\Controllers\ReportController::class, 'habitStats'])->name('teacher.reports.habitStats');
});

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'siswa'])->name('dashboard.siswa');
    Route::resource('journals', \App\Http\Controllers\JournalController::class);
});

Route::middleware(['auth', 'role:orang_tua'])->prefix('orang-tua')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ParentController::class, 'dashboard'])->name('dashboard.orang_tua');
    Route::get('/journals', [\App\Http\Controllers\ParentController::class, 'index'])->name('parent.journals.index');
    Route::get('/journals/{id}', [\App\Http\Controllers\ParentController::class, 'showJournal'])->name('parent.journals.show');
    Route::post('/journals/{id}/confirm', [\App\Http\Controllers\ParentController::class, 'storeConfirmation'])->name('parent.journals.confirm');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

require __DIR__.'/auth.php';
