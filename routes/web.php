<?php

use App\Http\Controllers\Admin\GroupsController;
use App\Http\Controllers\Admin\PrioritiesController;
use App\Http\Controllers\Admin\StatusesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Project\ActivitiesController;
use App\Http\Controllers\Project\OverviewController;
use App\Http\Controllers\Project\TaskController;
use App\Http\Controllers\Project\TasksController as ProjectTasksController;
use App\Http\Controllers\Project\TimesheetsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TimerController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(callback: function () {
    // Admin routes

    Route::group(['as' => 'admin.', 'prefix' => '/admin/', 'middleware' => 'admin'], function () {
        Route::resource('users', UsersController::class)->except(['show']);
        Route::resource('groups', GroupsController::class)->except(['show']);
        Route::resource('priorities', PrioritiesController::class)->except(['show']);
        Route::resource('statuses', StatusesController::class)->except(['show']);


        Route::redirect('/', route('admin.users.index'));
    });

    // Main routes

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects');

    Route::get('/projects/create', [ProjectsController::class, 'form'])->name('projects.create');
    Route::get('/projects/{project}/edit', [ProjectsController::class, 'form'])->name('projects.edit');
    Route::post('/projects/create', [ProjectsController::class, 'save'])->name('projects.store');
    Route::post('/projects/{project}/edit', [ProjectsController::class, 'save'])->name('projects.update');

    Route::get('/tasks', [TasksController::class, 'index'])->name('tasks');

//    Route::get('/users/create', [ProjectsController::class, 'form'])->name('users.create');
//    Route::post('/users/create', [ProjectsController::class, 'save'])->name('users.create');

    // Timer

    Route::group(['as' => 'timer.', 'prefix' => '/timer/'], function () {
        Route::post('start', [TimerController::class, 'start'])->name('start');
        Route::post('stop', [TimerController::class, 'stop'])->name('stop');
    });

    // Profile

    Route::group(['as' => 'profile.', 'prefix' => '/profile/'], function () {
        Route::get('', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('', [ProfileController::class, 'update'])->name('update');
        Route::delete('', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Project routes

    Route::group(['as' => 'project.', 'prefix' => '/project/{project}/'], function () {
        Route::get('overview', [OverviewController::class, 'index'])->name('overview');
        Route::get('tasks', [ProjectTasksController::class, 'index'])->name('tasks');

        Route::get('tasks/create', [ProjectTasksController::class, 'create'])->name('tasks.create');
        Route::post('tasks/create', [ProjectTasksController::class, 'store'])->name('tasks.store');

        Route::get('timesheets', [TimesheetsController::class, 'index'])->name('timesheets');
        Route::get('timesheets/create', [TimesheetsController::class, 'form'])->name('timesheets.create');
        Route::post('timesheets/create', [TimesheetsController::class, 'save'])->name('timesheets.store');
        Route::get('timesheets/{time}/edit', [TimesheetsController::class, 'form'])->name('timesheets.edit');
        Route::post('timesheets/{time}/edit', [TimesheetsController::class, 'save'])->name('timesheets.update');


        Route::get('activities', [ActivitiesController::class, 'index'])->name('activity');

        Route::get('tasks/{task:number}', [TaskController::class, 'index'])->name('task');
        Route::post('tasks/{task:number}', [TaskController::class, 'update'])->name('task.update');
    });
});

require __DIR__ . '/auth.php';
