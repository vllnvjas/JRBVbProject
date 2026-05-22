<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\Administrator\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PSUController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;

Route::get('/', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.submit');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('session.auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::get('/about', function () {
        return view('about');
    })->name('about');

    Route::get('/demo', [PageController::class, 'demo'])->name('demo');

    Route::get('/first-login/change-password', [UserController::class, 'showFirstLoginPasswordForm'])->name('first-login.password.form');
    Route::post('/first-login/change-password', [UserController::class, 'updateFirstLoginPassword'])->name('first-login.password.update');
    Route::get('/student/profile', [UserController::class, 'studentProfile'])->name('student.profile');
    Route::post('/manageStudents', [UserController::class, 'manageStudents'])->name('manageStudents');

    Route::middleware('role.admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/degrees', [DashboardController::class, 'degreesIndex'])->name('degrees.index');
        Route::post('/degrees', [DashboardController::class, 'storeDegree'])->name('degrees.store');
        Route::get('/degrees/{degree}/edit', [DashboardController::class, 'editDegree'])->name('degrees.edit');
        Route::put('/degrees/{degree}', [DashboardController::class, 'updateDegree'])->name('degrees.update');
        Route::delete('/degrees/{degree}', [DashboardController::class, 'deleteDegree'])->name('degrees.delete');
        
        // Admin management
        Route::get('/admins', [DashboardController::class, 'adminsIndex'])->name('admins.index');
        Route::get('/admins/create', [DashboardController::class, 'createAdmin'])->name('admins.create');
        Route::post('/admins', [DashboardController::class, 'storeAdmin'])->name('admins.store');
        Route::get('/admins/{admin}/edit', [DashboardController::class, 'editAdmin'])->name('admins.edit');
        Route::put('/admins/{admin}', [DashboardController::class, 'updateAdmin'])->name('admins.update');
        Route::delete('/admins/{admin}', [DashboardController::class, 'deleteAdmin'])->name('admins.delete');
        
        Route::get('/students', [DashboardController::class, 'studentsIndex'])->name('students.index');
        Route::get('/students/create', [DashboardController::class, 'createStudent'])->name('students.create');
        Route::post('/students', [DashboardController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/{student}/edit', [DashboardController::class, 'editStudent'])->name('students.edit');
        Route::put('/students/{student}', [DashboardController::class, 'updateStudent'])->name('students.update');
        Route::delete('/students/{student}', [DashboardController::class, 'deleteStudent'])->name('students.delete');
        
        Route::get('/teachers', [DashboardController::class, 'teachersIndex'])->name('teachers.index');
        Route::get('/teachers/create', [DashboardController::class, 'createTeacher'])->name('teachers.create');
        Route::post('/teachers', [DashboardController::class, 'storeTeacher'])->name('teachers.store');
        Route::get('/teachers/{teacher}/edit', [DashboardController::class, 'editTeacher'])->name('teachers.edit');
        Route::put('/teachers/{teacher}', [DashboardController::class, 'updateTeacher'])->name('teachers.update');
        Route::delete('/teachers/{teacher}', [DashboardController::class, 'deleteTeacher'])->name('teachers.delete');
    });

    Route::middleware('role.teacher')->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Teacher\TeacherController::class, 'dashboard'])->name('dashboard');
        // teacher profile (manage own details)
        Route::get('/profile/edit', [\App\Http\Controllers\Teacher\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Teacher\ProfileController::class, 'update'])->name('profile.update');

        Route::get('/students', [\App\Http\Controllers\Teacher\StudentController::class, 'index'])->name('students.index');
        Route::get('/students/create', [\App\Http\Controllers\Teacher\StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [\App\Http\Controllers\Teacher\StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [\App\Http\Controllers\Teacher\StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [\App\Http\Controllers\Teacher\StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [\App\Http\Controllers\Teacher\StudentController::class, 'destroy'])->name('students.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Students Resource Controller
    |--------------------------------------------------------------------------
    */
    Route::resource('/students', StudentController::class);

    Route::get('/degrees', [DegreeController::class, 'index'])->name('degrees.index');
    Route::post('/degrees', [DegreeController::class, 'store'])->name('degrees.store');
    Route::get('/degrees/{degree}/edit', [DegreeController::class, 'edit'])->name('degrees.edit');
    Route::put('/degrees/{degree}', [DegreeController::class, 'update'])->name('degrees.update');
    Route::delete('/degrees/{degree}', [DegreeController::class, 'destroy'])->name('degrees.destroy');
    Route::get('/user_profile', [PageController::class, 'userProfile'])->name('user_profile');
    Route::get('/user_posts', [PageController::class, 'userPosts'])->name('user_posts');
    Route::get('/student_courses', [PageController::class, 'studentCourses'])->name('student_courses');

    /*
    |--------------------------------------------------------------------------
    | Client Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/greetings', [ClientController::class, 'displayGreetings'])->name('greetings');
    Route::get('/profile', [ClientController::class, 'clientProfile'])->name('profile');
    Route::get('/dashboard', [ClientController::class, 'clientDashboard'])->name('dashboard');
    Route::get('/aboutUs', [ClientController::class, 'clientAboutUs'])->name('aboutus');
    Route::get('/client', [ClientController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | PSU Example Route
    |--------------------------------------------------------------------------
    */
    Route::get('/student/{name}/{course}', [PSUController::class, 'student'])->name('Student');

    Route::get('/maintenance', [PageController::class, 'maintenance'])->name('maintenance');

    Route::middleware('group_middleware')->group(function () {
        Route::get('/profile', [ClientController::class, 'clientProfile'])->name('profile');
        Route::get('/dashboard', [ClientController::class, 'clientDashboard'])->name('dashboard');
        Route::get('/aboutUs', [ClientController::class, 'clientAboutUs'])->name('aboutus');
    });
});


