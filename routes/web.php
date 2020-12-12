<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;

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

    Route::get('/tasks/create', [TasksController::class, 'create'])->name('tasks.create');
    Route::post('/projects/{project}/tasks', [TasksController::class, 'store'])->name('tasks.store');
    Route::patch('/projects/{project}/tasks/{task}', [TasksController::class, 'update'])->name('tasks.update');

});
