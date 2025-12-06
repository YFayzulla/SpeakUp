<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\DeptStudentController;
use App\Http\Controllers\ExtraTeacherController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupExtraController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RefreshController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherAdminPanel;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TestResultController;
use App\Http\Controllers\WaitersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

// --- AUTHENTICATED USERS (Common) ---
Route::middleware('auth')->group(function () {
    Route::get('/', [Controller::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    // --- TEST ROUTES (TUZATILDI) ---
    Route::controller(TestResultController::class)->prefix('Test')->group(function () {
        Route::get('/', 'index')->name('test');
        Route::get('/{id}/show', 'showResults')->name('test.show');
    });

    // --- PDF REPORTS ---
    Route::controller(PdfController::class)->group(function () {
        Route::get('/student/pdf/{id}', 'history');
        Route::get('/dept/pdf', 'RoomListPDF');
        Route::get('/assessment/pdf/{date}', 'Assessment');
        Route::get('/teacher/pdf', 'teacher');
        Route::get('/group/pdf', 'group');
        Route::get('/student/pdf-list', 'student');
    });

    // --- EXCEL EXPORT ---
    Route::get('export-attendances/{id}', [GroupExtraController::class, 'export'])->name('export.attendances');

    // --- GROUPS & WAITERS ---
    Route::resource('group', GroupController::class);
    Route::controller(GroupController::class)->prefix('group')->name('group.')->group(function(){
        Route::get('{id}/room', 'makeGroup')->name('create.room');
    });

    Route::controller(GroupExtraController::class)->group(function () {
        Route::delete('/delete-multiple', 'deleteMultiple')->name('deleteMultiple');
        Route::post('group/change/{id}', 'change_group')->name('student.change.group');
        Route::get('group/student/{id}', 'show')->name('group.students');
    });

    Route::get('waiters', [WaitersController::class, 'index'])->name('waiters.index');

    // --- STUDENTS & DEPT ---
    Route::resource('student', StudentController::class);
    Route::post('student/dept', [Controller::class, 'search'])->name('student.search');

    Route::resource('dept', DeptStudentController::class);
    Route::get('payment-receipt/{paymentId}', [DeptStudentController::class, 'showReceipt'])->name('payment.receipt');
    Route::get('refresh/{id}/update', [RefreshController::class, 'update'])->name('refresh.update');

    // --- TEACHERS ---
    Route::resource('teacher', TeacherController::class);
    Route::controller(ExtraTeacherController::class)->prefix('teacher/group')->name('teacher_group.')->group(function () {
        Route::delete('delete/{id}', 'group_delete')->name('delete');
        Route::put('{id}/store', 'add_group')->name('store');
    });

    // --- FINANCE ---
    Route::controller(FinanceController::class)->prefix('finance')->name('finance.')->group(function () {
        Route::get('/', 'index')->name('other');
        Route::post('/store', 'store')->name('store');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| SHARED ROUTES (User || Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user|admin'])->group(function () {
    Route::get('group/assessment/{id}', [GroupExtraController::class, 'attendance'])->name('group.attendance');
    Route::delete('attendance/delete/{id}', [ExtraTeacherController::class, 'attendanceDelete'])->name('attendance.delete');
});

/*
|--------------------------------------------------------------------------
| TEACHER ROUTES (User only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::resource('assessment', AssessmentController::class);

    Route::controller(TeacherAdminPanel::class)->group(function () {
        Route::get('attendance/lists', 'attendanceIndex')->name('attendance.index');
        Route::get('groups', 'group')->name('attendance');
        Route::get('attendance/{id}', 'attendance')->name('attendance.check');
        Route::post('attendance/submit/{id}', 'attendance_submit')->name('attendance.submit');
    });
});
