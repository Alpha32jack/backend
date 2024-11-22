<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\studentController;

Route::get('/students',[studentController::class,'index'])->name('');

Route::get('/students/{id}', [studentController::class,'show'])->name('');

Route::post('/students',[studentController::class,'store']);

Route::put('/students/{id}',[studentController::class,'update']);

Route::delete('/students/{id}', [studentController::class,'destroy']);

Route::patch('/students/{id}/assign-sala', [StudentController::class, 'assignSala']);

Route::post('/students/login', [StudentController::class, 'login']);

Route::post('/students/{id}/profile-picture', [StudentController::class, 'uploadProfilePicture']);

Route::post('/students/verify-account', [StudentController::class, 'verifyAccount']);


Route::patch('/students/{id}/update-password', [StudentController::class, 'updatePassword']);

Route::post('/login', [studentController::class, 'login']);
