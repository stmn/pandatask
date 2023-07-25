<?php

use App\Http\Controllers\Admin\GroupsController;
use App\Http\Controllers\Admin\PrioritiesController;
use App\Http\Controllers\Admin\StatusesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Project\ActivityController;
use App\Http\Controllers\Project\KanbanController;
use App\Http\Controllers\Project\OverviewController;
use App\Http\Controllers\Project\TasksController as ProjectTasksController;
use App\Http\Controllers\Project\TimesheetsController;
use App\Http\Controllers\Project\WikiController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TimerController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;
use Laravolt\Avatar\Avatar;

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
        Route::post('users/{user}/impersonate', [UsersController::class, 'impersonate'])->name('users.impersonate');
        Route::resource('groups', GroupsController::class)->except(['show']);
        Route::resource('priorities', PrioritiesController::class)->except(['show']);
        Route::resource('statuses', StatusesController::class)->except(['show']);
        Route::post('statuses/{status}/reorder', [StatusesController::class, 'reorder'])->name('statuses.reorder');


        Route::redirect('/', route('admin.users.index'));
    });

    // Main routes

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/save-settings', [DashboardController::class, 'saveSettings'])->name('dashboard.save-settings');

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
//dd(1);
    Route::group(['as' => 'project', 'prefix' => '/project/{project}/'], function () {
        Route::get('', fn() => redirect()->route('project.tasks', ['project' => request()->route('project')]));

        Route::get('overview', [OverviewController::class, 'index'])->name('.overview');
        Route::get('tasks', [ProjectTasksController::class, 'index'])->name('.tasks');

        Route::get('kanban', [KanbanController::class, 'index'])->name('.kanban');
        Route::get('kanban/{status}/load-more', [KanbanController::class, 'tasks'])->name('.kanban.tasks');

        Route::get('tasks/create', [ProjectTasksController::class, 'create'])->name('.tasks.create');
        Route::post('tasks/create', [ProjectTasksController::class, 'store'])->name('.tasks.store');

        Route::get('pages/', [WikiController::class, 'index'])->name('.pages');
        Route::get('pages/{page:slug_title?}', [WikiController::class, 'index'])->name('.pages.show');
        Route::post('pages/{page:slug_title?}', [WikiController::class, 'save'])->name('.pages.save');
        Route::delete('pages/{page:slug_title?}', [WikiController::class, 'delete'])->name('.pages.delete');

        Route::get('timesheets', [TimesheetsController::class, 'index'])->name('.timesheets');
        Route::get('timesheets/create', [TimesheetsController::class, 'form'])->name('.timesheets.create');
        Route::post('timesheets/create', [TimesheetsController::class, 'save'])->name('.timesheets.store');
        Route::get('timesheets/{time}/edit', [TimesheetsController::class, 'form'])->name('.timesheets.edit');
        Route::post('timesheets/{time}/edit', [TimesheetsController::class, 'save'])->name('.timesheets.update');


        Route::get('activity', [ActivityController::class, 'index'])->name('.activity');

        Route::get('tasks/{task:number}', [ProjectTasksController::class, 'show'])->name('.task');
        Route::post('tasks/{task:number}', [ProjectTasksController::class, 'update'])->name('.task.update');
    });

    Route::get('/leave-impersonation', [AuthenticatedSessionController::class, 'leaveImpersonation'])->name('leave-impersonation');
});

Route::get('storage/project-avatars/{project}.png', function (Project $project) {
    @mkdir(storage_path('app/public/project-avatars/'), 0777, true);

    $image = (new Avatar(config('laravolt.avatar')))
        ->create($project->name)
        ->save(storage_path('app/public/project-avatars/' . $project->id . '.png'));

    return response()->file($image->dirname . '/' . $image->basename);
});

Route::get('storage/user-avatars/{user}.png', function (\App\Models\User $user) {
    @mkdir(storage_path('app/public/user-avatars/'), 0777, true);

    $image = (new Avatar([
        ...config('laravolt.avatar'),
        ...['chars' => 2]
    ]))
        ->create($user->full_name)
        ->save(storage_path('app/public/user-avatars/' . $user->id . '.png'));

    return response()->file($image->dirname . '/' . $image->basename);
});

require __DIR__ . '/auth.php';
