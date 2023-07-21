<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

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
        $this->success('Created successfully.');
    }

    public function afterUpdate(): void
    {
        $this->success('Updated successfully.');
    }

    public function afterDestroy(): void
    {
        $this->success('Deleted successfully.');
    }
}
