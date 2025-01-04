<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('responsecache')->group(function () {
    Route::get('/demo', function () {
        $timestamp = now()->toDateTimeString();
        Log::info("Serving fresh response at: $timestamp");
        return response()->json([
            'message' => 'Hello, this is a cached response!',
            'timestamp' => $timestamp
        ]);
    });

    Route::get('/greet/{name}', function ($name) {
        return response()->json([
            'message' => "Hello, $name!",
            'timestamp' => now()->toDateTimeString(),
        ]);
    });

    Route::get('/products', function (Request $request) {
        $category = $request->query('category', 'all');
        return response()->json([
            'message' => "Showing products for category: $category",
            'timestamp' => now()->toDateTimeString(),
        ]);
    });
});

// When using the route middleware you can specify the number of seconds these routes should be cached:
Route::get('/fresh-data', function () {
    $timestamp = now()->toDateTimeString();
    Log::info("Refreshing response at: $timestamp");
    return response()->json([
        'message' => 'Hello, this is a cached response!',
        'timestamp' => $timestamp
    ]);
})->middleware('responsecache:60');


Route::get('/dynamic-data', function () {
    $timestamp = now()->toDateTimeString();
    Log::info("No cache for this response at: $timestamp");
    return response()->json([
        'data' => 'This response will not be cached.',
        'timestamp' => $timestamp,
    ]);
})->middleware('doNotCacheResponse');
