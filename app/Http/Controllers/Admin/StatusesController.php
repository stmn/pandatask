<?php

namespace App\Http\Controllers\Admin;

use App\Models\Status;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Momentum\Modal\Modal;

class StatusesController extends AdminController
{
    public function index(Request $request): Response
    {
        $items = Status::query()
            ->search($request->search)
            ->sortByString($request->sort)
            ->paginate($this->perPage());

        return Inertia::render('Admin/Statuses/List', [
            'items' => $items,
            'title' => 'Statuses'
        ]);
    }

    public function edit(Status $status): Modal
    {
        return $this->form([
            'status' => $status,
        ]);
    }

    public function update(Status $status): void
    {
        $this->save($status);
        $this->afterUpdate();
    }


    public function destroy(Status $status): void
    {
        $this->authorize('delete', $status);
        $status->delete();
        $this->afterDestroy();
    }

    protected function form(array $data = [
        'user' => new Status]): Modal
    {
        return Inertia::modal('Admin/Statuses/Form', $data + [])
            ->baseRoute('admin.statuses.index');
    }

    protected function save(Status $status = new Status()): void
    {
        $this->validate(request(), [
            'name' => 'required',
            'color' => 'required',
        ]);

        $status->fill(request()->all());
        $status->save();
    }
}
