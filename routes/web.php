<?php

use App\Http\Controllers\EnvironmentController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [JobController::class, 'index']);

Route::get('/jobs/create', [JobController::class, 'create'])->middleware('auth');
Route::post('/jobs', [JobController::class, 'store'])->middleware('auth');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->middleware('auth')->name('jobs.edit');
Route::patch('/jobs/{job}', [JobController::class, 'update'])->middleware('auth');

Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'apply'])->middleware('auth')->name('jobs.apply');

Route::get('/search', SearchController::class);
Route::get('/tags/{tag:name}', TagController::class);

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);
    
    Route::get('/login', [SessionController::class, 'create']);
    Route::post('/login', [SessionController::class, 'store']);
});

Route::delete('/logout', [SessionController::class, 'destroy'])->middleware('auth');

Route::resource('environments', EnvironmentController::class)->except(['destroy']); 
Route::get('/environments', [EnvironmentController::class, 'index'])->name('environments.index');
Route::get('/environments/{environment}', [EnvironmentController::class, 'show'])->name('environments.show');
Route::post('environments/{environment}/add-users', [EnvironmentController::class, 'addUsersToEnvironment'])->name('environments.addUsers');

Route::post('tasks/{task}/add-users', [TaskController::class, 'addUsersToTask'])->name('tasks.addUsers');
Route::patch('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
});

Route::get('environments/{environment}/users', [TaskController::class, 'getUsersForEnvironment']);

Route::get('/environments/{environment}/create-task', [TaskController::class, 'createInEnvironment'])->name('tasks.createInEnvironment');

Route::delete('environments/{environment}', [EnvironmentController::class, 'destroy'])->name('environments.destroy');
Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

Route::get('/user/{userId}/environments', [RegisteredUserController::class, 'showEnvironments']);
Route::get('/environment/{environment}', [EnvironmentController::class, 'show'])->name('environment.show');
