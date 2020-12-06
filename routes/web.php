<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProjectsController;

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

});
