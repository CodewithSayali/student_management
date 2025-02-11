<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', [LoginController::class, 'adminlogin'])->name('login');


Route::post('/admin-login', [LoginController::class, 'login'])->name('admin.login');
Route::middleware(['AdminMiddleware'])->group(function () {
    Route::get('/index', [StudentController::class, 'index'])->name('students');
    Route::get('/add-student', [StudentController::class, 'create'])->name('students.create');
    Route::post('/store-student', [StudentController::class, 'store'])->name('students.store');
    Route::get('/edit-student/{id}', [StudentController::class, 'edit'])->name('students.edit');
    Route::post('/update-student/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}/delete', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
