<?php

use App\Actions\TaskHandler;
use App\Actions\TaskRetriever;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/tasks', TaskHandler::class)->name('task.send');
Route::get('/tasks/{job_id}', TaskRetriever::class)->name('task.retrieve');
