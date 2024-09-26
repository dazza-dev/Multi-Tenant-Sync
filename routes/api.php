<?php

use App\Http\Controllers\JobExecutionsController;
use App\Http\Controllers\JobExecutionsLogsController;
use App\Http\Controllers\ProjectsController;
use Illuminate\Support\Facades\Route;

Route::apiResource('projects', ProjectsController::class);
Route::get('jobs-available', [JobExecutionsController::class, 'jobsAvailable']);
Route::get('job-executions/{project}', [JobExecutionsController::class, 'index']);
Route::get('job-executions/show/{jobExecution}', [JobExecutionsController::class, 'show']);
Route::get('job-executions/logs/{jobExecution}', [JobExecutionsLogsController::class, 'index']);
Route::post('execute-job/{project}', [JobExecutionsController::class, 'executeJob']);
Route::get('getBatch/{batchId}', [JobExecutionsController::class, 'getBatch']);
