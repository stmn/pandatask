<?php

namespace Pandatask\Installer\Controllers;

use Inertia\Inertia;

class InstallerController
{
    public function index()
    {
        return Inertia::render('../src/installer/resources/js/Pages/Installer');
//        return view('installer::index');
    }
}
