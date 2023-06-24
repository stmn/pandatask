<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;

abstract class AdminController extends Controller
{
    public function create()
    {
        return $this->form();
    }

    public function store(): void
    {
        $this->save();
        $this->afterStore();
    }

    public function afterStore(): void
    {
        $this->message('success', 'Created successfully.');
    }

    public function afterUpdate(): void
    {
        $this->message('success', 'Updated successfully.');
    }

    public function afterDestroy(): void
    {
        $this->message('success', 'Deleted successfully.');
    }
}
