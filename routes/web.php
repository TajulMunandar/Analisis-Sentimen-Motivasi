<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LexiconController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\SentimentController;
use App\Http\Controllers\TweetsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('sign-in');
});

Route::redirect('/', '/login');

Route::prefix('/dashboard')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::resource('/users', UsersController::class);
    Route::resource('/process', PreprocessingController::class);
    Route::resource('/sentiment', SentimentController::class);
    Route::resource('/tweets', TweetsController::class);
    Route::resource('/lexicon', LexiconController::class);
    Route::get('/export-csv', [TweetsController::class, 'exportCsv'])->name('exportcsv');
    Route::post('/lexicon/import', [LexiconController::class, 'import'])->name('lexicon.import');
    Route::post('/users/reset-password', [UsersController::class, 'reset'])->name('resetpassword.resetPasswordAdmin');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
