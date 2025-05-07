<?php

use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\SentimentController;
use App\Http\Controllers\TweetsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/scrape', [TweetsController::class, 'scrape']);
Route::get('/process', [PreprocessingController::class, 'getData']);
Route::post('/preprocess', [PreprocessingController::class, 'store']);
Route::get('/preprocessed-tweets', [SentimentController::class, 'getPreprocessedTweets']);
Route::get('/lexicons', [SentimentController::class, 'getLexicons']);
Route::post('/save-sentiment', [SentimentController::class, 'saveSentiments']);
