<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', [EventController::class, 'index']);
Route::get('/events/create', [EventController::class, 'create']);