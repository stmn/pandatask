<?php

use Pandatask\Installer\Controllers\InstallerController;

Route::get('/installer', [InstallerController::class, 'index'])->name('dashboard');
