<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// ── Root ──────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// ── Auth Routes ───────────────────────────────────────────────────
Route::controller(AuthController::class)->group(function () {

    // Login pages
    Route::get('/admin/login', 'showAdminLogin')->name('admin.login');
    Route::get('/lecturer/login', 'showLecturerLogin')->name('lecturer.login');

    // Shared login POST + logout
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');

    // Lecturer self-registration (whitelist-gated)
    Route::get('/register', 'showRegister')->name('register.name');
    Route::post('/register', 'register')->name('register');

    // Admin self-registration (master-code-gated)
    Route::get('/admin/register', 'showAdminRegister')->name('admin.register');
    Route::post('/admin/register', 'registerAdmin')->name('admin.register.post');

    // OTP verification
    Route::get('/verify-otp/{staff_id}', 'showVerifyOTP')->name('verify.otp');
    Route::post('/verify-otp', 'verifyOTP')->name('verify.otp.post');
    Route::get('/resend-otp/{staff_id}', 'resendOTP')->name('resend.otp');
});

// ── Authenticated Routes ──────────────────────────────────────────
Route::middleware('auth')->group(function () {


    // Student Registration Hub
Route::get('/admin/students',
    [StudentController::class, 'index'])->name('admin.students');

Route::get('/admin/students/register',
    [StudentController::class, 'create'])->name('admin.students.create');

Route::post('/admin/students',
    [StudentController::class, 'store'])->name('admin.students.store');

Route::post('/admin/students/import-excel',
    [StudentController::class, 'importExcel'])->name('admin.students.import-excel');

Route::post('/admin/students/import-zip',
    [StudentController::class, 'importZip'])->name('admin.students.import-zip');

Route::get('/admin/students/template',
    [StudentController::class, 'downloadTemplate'])->name('admin.students.template');

    // Edit assigned lecturer details
Route::get('/admin/lecturer/{lecturer}/edit',
    [LecturerController::class, 'edit'])->name('admin.lecturer.edit');

Route::put('/admin/lecturer/{lecturer}',
    [LecturerController::class, 'update'])->name('admin.lecturer.update');

// Student Management
Route::get('/admin/course/{course}/students',
    [AcademicController::class, 'courseStudents'])->name('admin.course.students');

Route::get('/admin/student/{student}/edit',
    [AcademicController::class, 'editStudent'])->name('admin.student.edit');

Route::put('/admin/student/{student}',
    [AcademicController::class, 'updateStudent'])->name('admin.student.update');

Route::delete('/admin/student/{student}',
    [AcademicController::class, 'deleteStudent'])->name('admin.student.delete');

Route::post('/admin/course/{course}/students/bulk-delete',
    [AcademicController::class, 'bulkDeleteStudents'])->name('admin.students.bulk-delete');

// Lecturer management
Route::delete('/admin/course/{course}/lecturer/{lecturer}',
    [AcademicController::class, 'removeLecturer'])->name('admin.academic.remove-lecturer');



    Route::delete('/lecturer/attendance/{session}/delete',
    [AttendanceController::class, 'deleteSession'])->name('lecturer.attendance.delete');
    // Admin — Course Management
Route::get('/admin/courses',
    [CourseController::class, 'index'])->name('admin.courses');

Route::get('/admin/courses/create',
    [CourseController::class, 'create'])->name('admin.courses.create');

Route::post('/admin/courses',
    [CourseController::class, 'store'])->name('admin.courses.store');

Route::get('/admin/courses/{course}/edit',
    [CourseController::class, 'edit'])->name('admin.courses.edit');

Route::put('/admin/courses/{course}',
    [CourseController::class, 'update'])->name('admin.courses.update');

Route::delete('/admin/courses/{course}',
    [CourseController::class, 'destroy'])->name('admin.courses.destroy');

Route::get('/admin/faculty/{faculty}/programs',
    [CourseController::class, 'getProgramsByFaculty'])->name('admin.faculty.programs');

    // Attendance
Route::get('/lecturer/course/{course}/attendance',
    [AttendanceController::class, 'index'])->name('lecturer.attendance');

Route::get('/lecturer/course/{course}/attendance/descriptors',
    [AttendanceController::class, 'getFaceDescriptors'])->name('lecturer.attendance.descriptors');

Route::post('/lecturer/course/{course}/attendance/start',
    [AttendanceController::class, 'startSession'])->name('lecturer.attendance.start');

Route::post('/lecturer/attendance/mark',
    [AttendanceController::class, 'markPresent'])->name('lecturer.attendance.mark');

Route::post('/lecturer/attendance/end',
    [AttendanceController::class, 'endSession'])->name('lecturer.attendance.end');

Route::get('/lecturer/attendance/{session}/export',
    [AttendanceController::class, 'export'])->name('lecturer.attendance.export');

Route::get('/lecturer/course/{course}/attendance/history',
    [AttendanceController::class, 'history'])->name('lecturer.attendance.history');

    // Dashboards
    Route::get('/admin/dashboard', [DashboardController::class , 'admin'])->name('admin.dashboard');
    Route::get('/lecturer/dashboard', [DashboardController::class , 'lecturer'])->name('lecturer.dashboard');

    // Admin — Lecturer Management
    Route::get('/admin/lecturers', [LecturerController::class , 'index'])->name('admin.lecturers');
    Route::get('/admin/lecturers/create', [LecturerController::class , 'create'])->name('admin.lecturers.create');
    Route::post('/admin/lecturers', [LecturerController::class , 'store'])->name('admin.lecturers.store');

    // Admin — Academic Management
    Route::prefix('admin/academic')->group(function () {
            Route::get('/faculties',
            [AcademicController::class , 'faculties'])->name('admin.academic.faculties');

            Route::get('/faculty/{faculty}',
            [AcademicController::class , 'showFaculty'])->name('admin.academic.faculty');

            Route::get('/program/{program}/levels',
            [AcademicController::class , 'showLevels'])->name('admin.academic.program.levels');

            Route::get('/program/{program}',
            [AcademicController::class , 'showProgram'])->name('admin.academic.program');

            Route::get('/course/{course}',
            [AcademicController::class , 'showCourse'])->name('admin.academic.course');

            Route::post('/course/{course}/assign-lecturer',
            [AcademicController::class , 'assignLecturer'])->name('admin.academic.assign-lecturer');

            Route::post('/course/{course}/add-student',
            [AcademicController::class , 'addStudent'])->name('admin.academic.add-student');

            Route::post('/course/{course}/import-students',
            [AcademicController::class , 'importStudents'])->name('admin.academic.import-students');

            Route::get('/download-enrollment-template',
            [AcademicController::class , 'downloadTemplate'])->name('admin.academic.download-template');

            Route::post('/student/{student}/upload-face',
            [AcademicController::class , 'uploadFace'])->name('admin.academic.upload-face');
        }
        );

        // Lecturer — Course attendance view
        Route::get('/lecturer/course/{course}',
        [AcademicController::class , 'lecturerCourse'])->name('lecturer.course');
    });