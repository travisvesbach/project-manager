<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProjectUsersController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');


Route::group(['middleware' => ['auth:sanctum', 'verified']], function() {

    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects');
    Route::get('/projects/create', [ProjectsController::class, 'create'])->name('projects.create');
    Route::get('/projects/{project}', [ProjectsController::class, 'show'])->name('projects.show');
    Route::post('/projects', [ProjectsController::class, 'store'])->name('projects.store');
    Route::patch('/projects/{project}', [ProjectsController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectsController::class, 'destroy'])->name('projects.destroy');
    Route::patch('/projects/{project}/updatesectionweights', [ProjectsController::class, 'updateSectionWeights'])->name('projects.updatesectionweights');
    Route::patch('/projects/{project}/updatetaskweights', [ProjectsController::class, 'updateTaskWeights'])->name('projects.updatetaskweights');

    Route::get('/tasks/create', [TasksController::class, 'create'])->name('tasks.create');
    Route::post('/projects/{project}/tasks', [TasksController::class, 'store'])->name('tasks.store');
    Route::get('/projects/{project}/tasks/{task}', [TasksController::class, 'show'])->name('tasks.show');
    Route::patch('/projects/{project}/tasks/{task}', [TasksController::class, 'update'])->name('tasks.update');
    Route::delete('/projects/{project}/tasks/{task}', [TasksController::class, 'destroy'])->name('tasks.destroy');

    Route::post('/projects/{project}/users', [ProjectUsersController::class, 'store']);
    Route::delete('/projects/{project}/users/{user}', [ProjectUsersController::class, 'destroy']);

    Route::post('/projects/{project}/sections', [SectionsController::class, 'store'])->name('sections.store');
    Route::patch('/projects/{project}/sections/{section}', [SectionsController::class, 'update'])->name('sections.update');
    Route::delete('/projects/{project}/sections/{section}', [SectionsController::class, 'destroy'])->name('sections.destroy');


    Route::patch('/notifications/{notificationId}', [NotificationsController::class, 'update'])->name('notifications.update');

    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
});
